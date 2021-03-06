<rn:meta title="#rn:msg:CREATE_NEW_ACCT_HDG#" template="standard.php" login_required="false" redirect_if_logged_in="account/overview" force_https="true" />
<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:CREATE_AN_ACCOUNT_CMD#</h1>
</div>
<div id="rn_PageContent" class="rn_CreateAccount">
    <div class="rn_Padding">
        <form id="rn_CreateAccount" method="post" action="" onsubmit="return false;">
            <div id="rn_ErrorLocation"></div>
            <rn:widget path="input/FormInput" name="contacts.email" required="true" validate_on_blur="true" initial_focus="true"/>
            <rn:widget path="input/FormInput" name="contacts.login" required="true" validate_on_blur="true"/>
            <rn:widget path="input/FormInput" name="Contact.NewPassword" require_validation="true" label_input="#rn:msg:PASSWORD_LBL#" label_validation="#rn:msg:VERIFY_PASSWD_LBL#"/>
            <rn:widget path="input/ContactNameInput" required="true"/>
            <rn:widget path="input/CustomAllInput" table="contacts" always_show_mask="true"/>
            <rn:widget path="input/FormSubmit" label_button="#rn:msg:CREATE_ACCT_CMD#" on_success_url="/app/account/overview" error_location="rn_ErrorLocation"/>
        </form>
    </div>
</div>