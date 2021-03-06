<?php
namespace Custom\Models;
class Preptaloginmy_model extends \RightNow\Models\Base
{
    //  This information was added to the 12.5 upgrade of the Wizards site  --  ed huth
    //
    //  Incident History 
    //  09/05/2012  Modified program to reflect changes requested to wsdl via incident 120827-000168
    //  
    //  This model is used both on the wizards interface and the wotc_internal interfaces
    //  The endpoint to the wsdl server may need to be changed for the testing sites
    //  There are also associcated Config verbs that should be updated to point to the Site
    //  Config verbs are PTA_ERROR_URL and PTA_EXTERNAL_LOGIN_URL     
    
    
    function __construct()
    {
        parent::__construct();
        
        # Non Production Sites will use a different endpoint then production    -- Request by customer Crystal Carrow to have -pro, -tst and -tst2 sites along with upgrades, 
	
        if (preg_match("/\-\-upg/", $_SERVER['HTTP_HOST']) || preg_match("/\-\-upgrade/", $_SERVER['HTTP_HOST'])|| preg_match("/\-\-lclone--upgrade/", $_SERVER['HTTP_HOST'])|| preg_match("/\-\-lclone/", $_SERVER['HTTP_HOST']))
		{
                  // used for all non production sites   Changed for 12.5 upgrade  http://70.102.136.183/WOTCAuthService/sso.asmx?wsdl was old server pre 12.5 
                  //$this->endpoint = 'https://mgiqa1ssows.onlinegaming.wizards.com/WOTCAuthService/sso.asmx?wsdl';
				  //$this->endpoint = 'https://mgiqa1ssows.onlinegaming.wizards.com/WOTCAuthService/sso.asmx?wsdl';
                  $this->endpoint = 'http://70.102.136.233/WOTCAuthService/sso.asmx?wsdl';
                  //$this->RESTFULendpoint = 'partnersso.onlinegaming.wizards.com:5540/Orchestrator/OracleRightNow/web/';
				  //$this->RESTFULendpoint = 'partnersso.onlinegaming.wizards.com:5540/Orchestrator/OracleRightNow/Web/';
				  $this->RESTFULendpoint = 'mgir02orch.onlinegaming.wizards.com:5540/Orchestrator/OracleRightNow/web/';
        }
		else
		{
                 // this is the production ip     
                 $this->endpoint   = 'http://10.9.17.83/wotcauthservice/sso.asmx?wsdl';
                 $this->RESTFULendpoint = 'partnersso.onlinegaming.wizards.com:5540/Orchestrator/OracleRightNow/Web/';				 
        }
             
        $this->is_wsdl    = true;
    }


    function decodeptaloginmy(&$pta)
    {    
        // These parameters are coming over in POST instead of GET
        $p_li = $_REQUEST['p_li'];
        $token = $_REQUEST['token'];
        $p_status = $_REQUEST['p_status'];

	if ($token) 
	    {
                $session_id = $this->urlsafe_decode($token);
		
                if ($p_status=="Restricted")
                    {    
                    if ($this->validateSession($session_id, "Restricted"))
                       $bSessionValidate = 1;
                    else
                       $bSessionValidate = 0;
                    }
                else    
                    {    
                      
                    if ($this->validateSession($session_id, "Standard"))
                        $bSessionValidate = 1;
                    else
                        $bSessionValidate = 0;
                    }

	        // if session is valid then continue with PTA	
                if ($bSessionValidate) 
                   {
                   // set p_li that is posted in so standard 
                   // functionality can continue after hook
                     $pta['data']['p_li'] = $p_li;                
                    }
                else
                   {
                     // no login token
                     $next_page = "https://" . $_SERVER['HTTP_HOST'] . "/app/" . $pta['data']['redirect_to'];
                     $next_page = urlencode($next_page);

                     $ext_page = getConfig(PTA_EXTERNAL_LOGIN_URL);
                     $ext_page = sprintf("%s?redirectUrl=%s", $ext_page, $next_page);

                     $red_return = http_redirect($ext_page);
                     exit;
                    }
	    }
    } 
    
    /**
     * Function to call back to wotc and test the tokin passed in
     *
     * @@param $str String, Token that is POSTED to PTA hook
     * @@param $str String, Type of validation we are doing
     * @@return String result from wotc
     */
    function validateSessionSOAP($id, $type='SESSION_TYPE_NULL')
    {   
        if (extension_loaded("curl"))
        {
            //echo "cURL extension is loaded<br>";
        }    
        else
        {
            //echo "cURL extension is not available<br>";
              load_curl();
            //echo "downloaded<br>";
        }
 
        require_once('nusoap/lib/nusoap.php');
        
        $username = "onlinegaming\_wdssowscusthelp";
        $password = "P29d18Ln5";

        $client = new soapclient($this->endpoint, $this->is_wsdl);
		
        $client->setCredentials($username, $password, 'basic');

        $err = $client->getError();
     
        if ($err) 
        {
            logMessage($err);

            print("Authentication Error: There was an error with the transmission when attempting to validate your session. Please try again later.<p>");
            print_r($err);
            
            exit;
        }

        if ($type=='Restricted')
        {
            $param = array('encryptedSessionId' => $id); 
            $res = $client->call('IsRestrictedSessionIdValid', array($param));
            logMessage($res);
            
            return (isset($res['IsRestrictedSessionIdValidResult'])) ? $res['IsRestrictedSessionIdValidResult'] : 0;        
        }
        else
        {
            $param = array('sessionId' => $id);     
            $res = $client->call('IsSessionValid', array($param));
            logMessage($res);        
            return (isset($res['IsSessionValidResult'])) ? $res['IsSessionValidResult'] : 0;
        }
    }


    /**
     * Function to call back to wotc and test the tokin passed in
     *
     * @@param $str String, Token that is POSTED to PTA hook
     * @@param $str String, Type of validation we are doing
     * @@return String result from wotc
     */
    function validateSession($sessionId, $type='SESSION_TYPE_NULL')
    {   
		global $_SERVER, $_REQUEST;
		$bValid = false;
		$sFatalError = "";
		
		try
		{			
			if (extension_loaded("curl"))
			{
				logMessage("cURL extension is loaded");
			}    
			else
			{
				logMessage("cURL extension is not available");
                                load_curl();
				logMessage("downloaded");
			}		
			
			$ipAddress = $_SERVER['REMOTE_ADDR'];
			$interface_name = str_replace(array("/cgi-bin/", ".cfg/scripts"), array("", ""), get_cfg_var("doc_root"));
			if ($interface_name == "wotc_internal")
				$applicationName="OracleRightNowInternalPortal";		
			else
				$applicationName="OracleRightNowCustomerPortal";
			
			if ($type=='Restricted')
				$method = "IsRestrictedSessionValidRestful"; 
			else
				$method = "IsSessionValidRestful"; 

			//$sessionId="13e87696-a3cd-4f0c-968c-ef30c87ab571";							
			$url="https://".$this->RESTFULendpoint."IsSessionValidRestful"."?sessionId=".$sessionId."&applicationName=".$applicationName."&ipAddress=".$ipAddress;		
			logMessage($url);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/soap+xml; charset=utf-8'));
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_POST, false);
	
			$res = curl_exec($ch);
			logMessage(sprintf("\n---\nresult:\n%s\n---\n", $res));
			//print($res."<br>");
	
			if (curl_errno($ch)) 
				$sFatalError = "CURL Error Code " . curl_errno($ch) ." - ". curl_error($ch);
			else
			{
				if (strpos($res, "<IsTrue>true</IsTrue>")>0)
				{			
					$bValid = true;
				}
				elseif (strpos($res, "<IsTrue>false</IsTrue>")>0)
				{			
					$bValid = false;
				}
				else
				{					
					$sFatalError = "Invalid Response: ".$url."\n".$res."\n";
					$bValid = false;
				}
			}
        }
        catch(Exception $e)
        {
			$sFatalError .= "Fatal Exception: ".$e->getMessage();  	
        }
        
		if ($sFatalError)
		{
			logMessage($sFatalError); 			
			$CI = get_instance();   
			$this->load->library('CustomLog');
			$CI->customlog->init(LogTypes::CP, "CustomPTA", LogSeverities::Error, false, NULL, NULL);
			$this->customlog->severity = LogSeverities::Fatal;
			$this->customlog->create($sFatalError);			
		}
		
        return $bValid;
    }
        
    /**
     * Function to urlsafe encode a string using base 64
     *
     * @@param $str String  A plain text string that needs to be base64/urlsafe encoded
     * @@return String The encoded string (with 3 character replacement)
     */
    private function urlsafe_encode($str) { return(strtr(base64_encode($str), array('+' => '_', '/' => '-', '=' => '*'))); }    

    /**
     * Function to urlsafe decode a string using base 64
     *
     * @@param $str String  A base64 string that has been modified with the 3 string replacements
     * @@return String The unencoded string
     */
    private function urlsafe_decode($str) { return(base64_decode(strtr($str, array('_' => '+', '-' => '/', '*' => '=')))); }

}