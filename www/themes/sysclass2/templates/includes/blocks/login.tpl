<div class="container_24" style="margin-top: 50px;">
	<div class="grid_8 prefix_8" align="center">
		<img border="0" alt="Magester" title="Magester" src="themes/SysClass2/images/logo_login.png">
	</div>
	<div class="clear"></div>
	{$T_LOGIN_FORM.javascript}
	<form {$T_LOGIN_FORM.attributes}>
		{$T_LOGIN_FORM.hidden}
			<div class="grid_8 prefix_8" align="center">
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
					<div class="button_container">
						<button class="flatButton" type="submit" name="{$T_LOGIN_FORM.submit_login.name}" value="{$T_LOGIN_FORM.submit_login.value}">
							<span>{$T_LOGIN_FORM.submit_login.value}</span>
						</button>
					</div>
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
	</form>
</div>