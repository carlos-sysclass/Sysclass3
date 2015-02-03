<div class="login_corpo">
	<div class="login_top">
    	<img src="themes/SysClass3/images/login_logo.png" />
    </div>
    <div class="login_centro">
		{$T_RESET_PASSWORD_FORM.javascript}
		<form {$T_RESET_PASSWORD_FORM.attributes}>
            {$T_RESET_PASSWORD_FORM.hidden}
            <input type="{$T_RESET_PASSWORD_FORM.login_or_pwd.type}" class="{$T_RESET_PASSWORD_FORM.login_or_pwd.class}" name="{$T_RESET_PASSWORD_FORM.login_or_pwd.name}" id="{$T_RESET_PASSWORD_FORM.login_or_pwd.name}" value="{$smarty.const.__RESET_TEXT}" />
            <button name="{$T_RESET_PASSWORD_FORM.submit_reset_password.name}" type="submit" class="event-conf" value="{$TT_RESET_PASSWORD_FORM.submit_reset_password.value}" >
                <img src="images/transp.png"  class="imgs_cont" width="29" height="29" />
                <span>{$T_RESET_PASSWORD_FORM.submit_reset_password.value}</span>
            </button>
        </form>
    </div>
   
    <div style="clear:both">
    {if $T_RESET_PASSWORD_FORM.login_or_pwd.error}<div class = "error">{$T_RESET_PASSWORD_FORM.login_or_pwd.error}</div>{/if}
    </div>
</div>
