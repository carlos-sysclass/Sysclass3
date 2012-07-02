<!-- <a href = "{$smarty.server.PHP_SELF}?ctg=contact">{$smarty.const._CONTACTUS}</a>  -->
<div class="login_corpo">
	<div class="login_top">
    	<img src="themes/sysclass3/images/login_logo.png" />
    </div>
    <div class="login_centro">
		{$T_LOGIN_FORM.javascript}
		<form {$T_LOGIN_FORM.attributes}>
			{$T_LOGIN_FORM.hidden}
    		<input type="{$T_LOGIN_FORM.login.type}" class="{$T_LOGIN_FORM.login.class}" name="{$T_LOGIN_FORM.login.name}" id="{$T_LOGIN_FORM.login.name}" value="{$smarty.const.__USER_TEXT}" />
    		<input type="{$T_LOGIN_FORM.password.type}" class="{$T_LOGIN_FORM.password.class}" name="{$T_LOGIN_FORM.password.name}" id="{$T_LOGIN_FORM.password.name}" value="{$smarty.const.__PASS_TEXT}" />
    		<input type="text" class="{$T_LOGIN_FORM.password.class}" name="_{$T_LOGIN_FORM.password.name}" id="_{$T_LOGIN_FORM.password.name}" value="{$smarty.const.__PASS_TEXT}" />

            <button name="{$T_LOGIN_FORM.submit_login.name}" type="submit" class="event-conf" value="{$T_LOGIN_FORM.submit_login.value}" >
                <img src="images/transp.png"  class="imgs_cont" width="29" height="29" />
                <span>{$T_LOGIN_FORM.submit_login.value}</span>
            </button>
        </form>
    </div>
    <div class="login_footer">
    <!-- 
    	<input type="checkbox" />
        <p style=" color: #848484; float: left;font-size: 11px; margin: 6px 0 0;">Lembrar</p>
 	-->
        {if $T_CONFIGURATION.password_reminder && !$T_CONFIGURATION.only_ldap}
			<p style=" color: #848484; float: right;font-size: 11px; margin: 6px 0 0;">
				<a href = "{$smarty.server.PHP_SELF}?ctg=reset_pwd">{$smarty.const._FORGOTPASSWORD}</a>
			</p>
		{/if}
		{if $T_CONFIGURATION.signup && !$T_CONFIGURATION.only_ldap}
			<p style=" color: #848484; float: right;font-size: 11px; margin: 6px 0 0;">
				<a href = "{$smarty.server.PHP_SELF}?ctg=signup">{$smarty.const._DONTHAVEACCOUNT}</a>
			</p>
		{/if}
		{if $T_CONFIGURATION.lessons_directory == 1}
			<p style=" color: #848484; float: right;font-size: 11px; margin: 6px 0 0;">
				<a href = "{$smarty.server.PHP_SELF}?ctg=lessons">{$smarty.const._LESSONSLIST}</a>
			</p>
		{/if}
    </div>
    <div style="clear:both"></div>
</div>