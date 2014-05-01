{extends file="layout/login.tpl"}
{block name="content"}
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
		{$T_LOGIN_FORM.javascript}
		<form {$T_LOGIN_FORM.attributes}>
			{$T_LOGIN_FORM.hidden}
			<h3 class="form-title">{translateToken value='Login to your account'}</h3>
			{if isset($T_MESSAGE) && $T_MESSAGE|@count > 0} 
				<div class="alert alert-{$T_MESSAGE.type}">
					<button class="close" data-dismiss="alert"></button>
					<span>{$T_MESSAGE.message}</span>
				</div>
			{/if}
			<div class="form-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">{$T_LOGIN_FORM.login.label}</label>
				<div class="input-icon">
					<i class="icon-user"></i>
					<input class="{$T_LOGIN_FORM.login.class}" type="{$T_LOGIN_FORM.login.type}" autocomplete="off" placeholder="{$T_LOGIN_FORM.login.label}" name="{$T_LOGIN_FORM.login.name}" id="{$T_LOGIN_FORM.login.name}"/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">{$T_LOGIN_FORM.password.label}</label>
				<div class="input-icon">
					<i class="icon-lock"></i>
					<input class="{$T_LOGIN_FORM.password.class}" type="{$T_LOGIN_FORM.password.type}" autocomplete="off" placeholder="{$T_LOGIN_FORM.password.label}" name="{$T_LOGIN_FORM.password.name}" id="{$T_LOGIN_FORM.password.name}" />
				</div>
			</div>
			<div class="form-actions">
				<label class="checkbox">
					<input type="{$T_LOGIN_FORM.remember.type}" name="{$T_LOGIN_FORM.remember.name}" value="1"/> {translateToken value='Remember Me'}
				</label>
				<button name="{$T_LOGIN_FORM.submit_login.name}" type="submit" class="btn green pull-right" value="{$T_LOGIN_FORM.submit_login.value}" >
					{$T_LOGIN_FORM.submit_login.value}<i class="m-icon-swapright m-icon-white"></i>
				</button>
			</div>

			{if $T_CONFIGURATION.password_reminder && !$T_CONFIGURATION.only_ldap}
			<div class="forget-password">
				<h4>{translateToken value="Forgot your password?"}</h4>
				<p>
					{translateToken value='Click'} <a href="javascript:;"  id="forget-password">{translateToken value='here'}</a> {translateToken value='to reset your password'}
				</p>
			</div>
			{/if}
			{if $T_CONFIGURATION.signup && !$T_CONFIGURATION.only_ldap}
				<div class="create-account">
					<p>
						{translateToken value="Don't have an account?"}
						<a href="/signup" id="register-btn" >{translateToken value='Create an account'}</a>
					</p>
				</div>
			{/if}
<!--
    <div class="login_footer">
		{if $T_CONFIGURATION.lessons_directory == 1}
			<p style=" color: #848484; float: right;font-size: 11px; margin: 6px 0 0;">
				<a href = "{$smarty.server.PHP_SELF}?ctg=lessons">{$smarty.const._LESSONSLIST}</a>
			</p>
		{/if}
    </div>
-->
		</form>
		<!-- END LOGIN FORM -->        
		<!-- BEGIN FORGOT PASSWORD FORM -->


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
		<!-- END FORGOT PASSWORD FORM -->
		<!-- BEGIN REGISTRATION FORM -->
		<!-- END REGISTRATION FORM -->
	</div>
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		&copy; Copyright 2014 • WiseFlex Knowledge Systems LLC.
	</div>
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