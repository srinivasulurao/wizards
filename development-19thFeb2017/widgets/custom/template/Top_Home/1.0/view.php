
<rn:meta controller_path="standard/utils/Blank" /><body align="center" topmargin="0" marginwidth="0" marginheight="0"> 
<div id="divContainer">
	<div id="divHeaderBar"> <?/* Opening tag for "#divContainer", closing div tag in footer */?>
		<div id="divHeader">
			<img id="imgMobileSearchIcon" src="/euf/assets/images/Search_icon.png" width="17px" height="16px" border="0" align="absmiddle">
			<div id="rn_searchheader"><rn:widget path="custom/search/SimpleSearch" /></div>
			<div id="divWizardsIcon"><a href="https://wizards.custhelp.com" title="Wizards" alt="Wizards"><img src="/euf/assets/images/wizards-footer_icon.png" width="121px" height="76px" border="0" align="absmiddle"></a></div>
			<div id="divAccountLinks">
				<img src="/euf/assets/images/HeaderProfile_Icon.png" width="23px" height="18px" border="0" align="absmiddle">
				 <rn:condition logged_in="true">
					 #rn:msg:WELCOME_BACK_LBL# <rn:field name="contacts.first_name"/>
					<span>&nbsp;|&nbsp;<span>					
					<rn:widget path="/login/LogoutLink2" label="[LOGOUT]"/>
				<rn:condition_else />				
					<a href="#rn:config:PTA_EXTERNAL_LOGIN_URL#?p_next_page=home" title="Sign-in" alt="SIGN IN">SIGN IN</a>
					<span>&nbsp;|&nbsp;<span>
					<a href="#rn:config:PTA_EXTERNAL_LOGIN_URL#?p_next_page=home" title="Sign-up" alt="SIGN UP">SIGN UP</a>
				</rn:condition>
				<img id="imgDesktopSearchIcon" src="/euf/assets/images/Search_icon.png" width="17px" height="16px" border="0" align="absmiddle">
			</div>
		</div>
	</div>
