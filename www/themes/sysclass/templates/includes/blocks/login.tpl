{$T_LOGIN_FORM.javascript}
<form {$T_LOGIN_FORM.attributes}>
{$T_LOGIN_FORM.hidden}
<div style="min-height: 420px; min-width: 320px;">
	<div class="round_all clearfix" id="login_box">
		<label class="fields">
			<strong>{$T_LOGIN_FORM.login.label}</strong>
			{$T_LOGIN_FORM.login.html}
			{if $T_LOGIN_FORM.login.error}<div class = "error">{$T_LOGIN_FORM.login.error}</div>{/if}
		</label>
		<label class="fields">
			<strong>{$T_LOGIN_FORM.password.label}</strong>
			{$T_LOGIN_FORM.password.html}
			{if $T_LOGIN_FORM.password.error}<div class = "error">{$T_LOGIN_FORM.password.error}</div>{/if}
		</label>
		<button class="button_colour round_all" type="submit" name="{$T_LOGIN_FORM.submit_login.name}" value="{$T_LOGIN_FORM.submit_login.value}">
			<img width="24" height="24" src="/themes/sysclass/images/icons/small/white/locked_2.png" alt="">
			<span>{$T_LOGIN_FORM.submit_login.value}</span>
		</button>
		<div class="round_bottom" id="bar">
			{if $T_CONFIGURATION.password_reminder && !$T_CONFIGURATION.only_ldap}
				<a href = "{$smarty.server.PHP_SELF}?ctg=reset_pwd">{$smarty.const._FORGOTPASSWORD}</a>
			{/if}
			{if $T_CONFIGURATION.signup && !$T_CONFIGURATION.only_ldap}
				<a href = "{$smarty.server.PHP_SELF}?ctg=signup">{$smarty.const._DONTHAVEACCOUNT}</a>
			{/if}
			<a href = "{$smarty.server.PHP_SELF}?ctg=contact">{$smarty.const._CONTACTUS}</a>
			{if $T_CONFIGURATION.lessons_directory == 1}
				<a href = "{$smarty.server.PHP_SELF}?ctg=lessons">{$smarty.const._LESSONSLIST}</a>
			{/if}
		</div>		
	</div>    
</div>

{if $T_OPEN_FACEBOOK_SESSION && !$T_NO_FACEBOOK_LOGIN}
	<div class = "loginFacebookFormRow">
		<div class = "formLabel">
        	<div class = "facebookHeader">{$smarty.const._LOGINWITHYOURFACEBOOKACCOUNT}</div>
		</div>
		<div class = "formElement">
			<div style="margin-left:9px;margin-top:3px">
				<fb:login-button onlogin="top.location='index.php';"></fb:login-button>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		FB.init("{$T_FACEBOOK_API_KEY}", "facebook/xd_receiver.htm");
	</script>
{/if}
</form>