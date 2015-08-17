{extends file="layout/login.tpl"}
{block name="content"}
	<div class="content">
		<div class="inside-logo">
			<img src="{Plico_GetResource file='img/logo.png'}" alt="" style="max-width: 100%" />
		</div>
		<!-- BEGIN LOGIN FORM -->
		<form class="login-form" action="/login" method="post">

		<input type="hidden" name="requested_uri" value="{$T_REQUESTED_URI}" />
			<h3 class="form-title">{translateToken value="Login to your account"}</h3>
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
					<input type="text" id="login" name="login" placeholder="{translateToken value="Login"}" autocomplete="off" class="form-control" data-rule-required="true">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">{$T_LOGIN_FORM.password.label}</label>
				<div class="input-icon">
					<i class="icon-lock"></i>
					<input type="password" id="password" name="password" placeholder="Password" autocomplete="off" class="form-control placeholder-no-fix">
				</div>
			</div>
			<div class="form-actions">
				<div class="form-group">
					<input type="checkbox" name="remeber" value="1"/>
					<label class="checkbox">{translateToken value="Remember Me"}</label>
					<button name="submit_login" type="submit" class="btn green pull-right" value="Click to access" >{translateToken value="Click to access"}
						<i class="m-icon-swapright m-icon-white"></i>
					</button>
				</div>
			</div>
			{if
				$T_CONFIGURATION.enable_facebook_login ||
				$T_CONFIGURATION.enable_linkedin_login ||
				$T_CONFIGURATION.enable_googleplus_login
			}
			<div class="login-options">
				<h4>Or login with</h4>
				<ul class="social-icons">
					{if $T_CONFIGURATION.enable_facebook_login}
					<li>
						<a class="facebook" data-original-title="facebook" href="#">
						</a>
					</li>
					{/if}
					{if $T_CONFIGURATION.enable_googleplus_login}
					<li>
						<a class="googleplus" data-original-title="Goole Plus" href="#">
						</a>
					</li>
					{/if}
					{if $T_CONFIGURATION.enable_linkedin_login}
					<li>
						<a class="linkedin" data-original-title="Linkedin" href="#">
						</a>
					</li>
					{/if}
				</ul>
			</div>
			{/if}
			{if $T_CONFIGURATION.enable_forgot_form}
			<div class="forget-password">
				<h4>{translateToken value="Forgot your password?"}</h4>
				<p>
					{translateToken value="Click"} <a href="javascript:;" id="forget-password">{translateToken value="here"}</a> {translateToken value="to reset your password"}
				</p>
			</div>
			{/if}
			{if $T_CONFIGURATION.enable_public_signup}
				<div class="create-account">
					<p>
						{translateToken value="Don't have an account?"}
						<a href="/signup" id="register-btn" >{translateToken value="Create an account"}</a>
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
		<form class="forget-form" action="/login/reset" method="post">
			<h3 >{translateToken value="Forget your password?"}</h3>
			<p>{translateToken value="Enter your e-mail address below to reset your password."}</p>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">
					{$T_RESET_PASSWORD_FORM.login_or_pwd.label}
				</label>
				<div class="input-icon">
					<i class="fa fa-envelope"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" id="email" />
				</div>
			</div>
			<div class="form-actions">
				<button type="button" id="back-btn" class="btn">
					<i class="m-icon-swapleft"></i>{translateToken value="Back"}
				</button>
				<button type="submit" class="btn green pull-right">
					{translateToken value="Submit"}<i class="m-icon-swapright m-icon-white"></i>
				</button>
			</div>
		</form>
		<!-- END FORGOT PASSWORD FORM -->
		<!-- BEGIN REGISTRATION FORM -->
		<!-- END REGISTRATION FORM -->
	</div>
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		&copy; Copyright 2015 • WiseFlex Knowledge Systems LLC.
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
