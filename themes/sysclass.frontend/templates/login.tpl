{extends file="index.tpl"}
{block name="login-form"}
	{$T_LOGIN_FORM.javascript}
	<form {$T_LOGIN_FORM.attributes}>
		{$T_LOGIN_FORM.hidden}
		{if isset($T_MESSAGE) && $T_MESSAGE|@count > 0} 
			<div class="alert alert-{$T_MESSAGE.type}">
				<button class="close" data-dismiss="alert"></button>
				<span>{$T_MESSAGE.message}</span>
			</div>
		{/if}

		<div class="element_from_left" style="opacity: 1; left: 0px;">            
		
			<input class="{$T_LOGIN_FORM.login.class}" type="{$T_LOGIN_FORM.login.type}" autocomplete="off" placeholder="{$T_LOGIN_FORM.login.label}" name="{$T_LOGIN_FORM.login.name}" id="{$T_LOGIN_FORM.login.name}"/>

			<input class="{$T_LOGIN_FORM.password.class}" type="{$T_LOGIN_FORM.password.type}" autocomplete="off" placeholder="{$T_LOGIN_FORM.password.label}" name="{$T_LOGIN_FORM.password.name}" id="{$T_LOGIN_FORM.password.name}" />
 
		</div>
        <button name="{$T_LOGIN_FORM.submit_login.name}" type="submit" class="sysclass-button medium outline" value="{$T_LOGIN_FORM.submit_login.value}" >
			{$T_LOGIN_FORM.submit_login.value}<i class="m-icon-swapright m-icon-white"></i>
		</button>

<!--
		{if $T_CONFIGURATION.password_reminder && !$T_CONFIGURATION.only_ldap}
			<div class="forget-password">
				<h4>{translateToken value="Forgot your password?"}</h4>
				<p>
					{translateToken value='Click'} <a href="javascript:;"  id="forget-password">{translateToken value='here'}</a> {translateToken value='to reset your password'}
				</p>
			</div>
		{/if}
-->
    </form>   
<!--
		{$T_RESET_PASSWORD_FORM.javascript}
		<form {$T_RESET_PASSWORD_FORM.attributes}>
			{$T_RESET_PASSWORD_FORM.hidden}
			<h3 >{translateToken value="Forget your password?"}</h3>
			<p>{translateToken value="Enter your e-mail address below to reset your password."}</p>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">{$T_RESET_PASSWORD_FORM.login_or_pwd.label}</label>
				<div class="input-icon">
					<i class="icon-envelope"></i>
					<input class="{$T_RESET_PASSWORD_FORM.login_or_pwd.class}" type="{$T_RESET_PASSWORD_FORM.login_or_pwd.type}" autocomplete="off" placeholder="{$T_RESET_PASSWORD_FORM.login_or_pwd.label}" name="{$T_RESET_PASSWORD_FORM.login_or_pwd.name}" id="{$T_RESET_PASSWORD_FORM.login_or_pwd.name}" />
				</div>

			</div>
			<div class="form-actions">
				<button type="button" id="back-btn" class="btn"><i class="m-icon-swapleft"></i> {translateToken value="Back"}</button>
				<button type="submit" class="btn green pull-right">{translateToken value="Submit"}<i class="m-icon-swapright m-icon-white"></i></button>
			</div>
		</form>
-->		
	{if $T_OPEN_LOGIN_SECTION == 'reset'}
		<style type="text/css">
			.login .content .forget-form {
		    	display: block;
			}
			.login .content .login-form {
				display: none;
			}
		</style>
	{/if}
	<!-- END COPYRIGHT -->
{/block}