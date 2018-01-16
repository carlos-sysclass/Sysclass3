{extends file="layout/login.tpl"}
{block name="content"}
	<div class="content">
		<div class="inside-logo">
			<img src="{Plico_GetResource file='img/logo-login.png'}" alt="" style="max-width: 100%" />
		</div>
		<!-- BEGIN LOGIN FORM -->
		<form class="login-form" action="/login" method="post">

		<input type="hidden" name="requested_uri" value="{$T_REQUESTED_URI}" />
			<!--<h3 class="form-title">{translateToken value="Login to your account"}</h3>-->

			{if isset($T_MESSAGE) && $T_MESSAGE|@count > 0}
				<div class="alert alert-{$T_MESSAGE.type}">
					<button class="close" data-dismiss="alert"></button>
					<span>{$T_MESSAGE.message}</span>
				</div>
			{/if}

			{if isset($messages) && $messages|@count > 0}
				{foreach $messages as $type => $message}
				<div class="alert alert-{$type}">
					<button class="close" data-dismiss="alert"></button>
					<span>{$message}</span>
				</div>
				{/foreach}
			{/if}

			<div class="form-group">
				<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
				<label class="control-label visible-ie8 visible-ie9">{translateToken value="Login or email"}</label>
				<div class="input-icon">
					<i class="ti-user"></i>
					<input type="text" id="login" name="login" placeholder="{translateToken value="Login or email"}" autocomplete="off" class="form-control" data-rule-required="true">
				</div>
			</div>
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">{translateToken value="Password"}</label>
				<div class="input-icon">
					<i class="ti-lock"></i>
					<input type="password" id="password" name="password" placeholder="{translateToken value="Password"}" autocomplete="off" class="form-control placeholder-no-fix">
				</div>
			</div>
			<div class="form-actions">
				<div class="form-group">
					<!--
					<input type="checkbox" name="remeber" value="1"/>
					<label class="checkbox">{translateToken value="Remember me"}</label>
					-->
					<button name="submit_login" type="submit" class="btn btn-success pull-right" value="Click to access" ><i class="ti-arrow-right"></i>{translateToken value="Click to access"}
						
					</button>
					{if	$T_CONFIGURATION.enable_facebook_login}
					<button name="submit_login" type="submit" class="btn btn-primary" value="Click to access" ><i class="ti-facebook"></i>acessar utilizando o facebook
						
					</button>
					{/if}
					{if $T_CONFIGURATION.enable_forgot_form}
					<button type="button" class="btn btn-danger pull-right" value="{translateToken value="Click"} {translateToken value="here"} {translateToken value="to reset your password"}" id="forget-password" ><i class="ti-reload"></i>{translateToken value="Forgot your password"}
					</button>

					{/if}
				</div>
			</div>
			{if
				$T_CONFIGURATION.enable_linkedin_login ||
				$T_CONFIGURATION.enable_googleplus_login
			}
<!-- 			<div class="login-options">
				<h4>Or login with</h4>
				<ul class="social-icons">
					{if $T_CONFIGURATION.enable_facebook_login}
					<li>
						<a class="facebook social-icon-color" data-original-title="facebook" href="#">
							<i class="fa fa-facebook"></i>
						</a>
					</li>
					{/if}
					{if $T_CONFIGURATION.enable_googleplus_login}
					<li>
						<a class="googleplus social-icon-color" data-original-title="Goole Plus" href="#">
						</a>
					</li>
					{/if}
					{if $T_CONFIGURATION.enable_linkedin_login}
					<li>
						<a class="linkedin social-icon-color" data-original-title="Linkedin" href="#">
						</a>
					</li>
					{/if}
				</ul>
			</div>
<!--      
		    {/if}
			 {if $T_CONFIGURATION.signup_enable}
				<div class="create-account">
					<p>
						{translateToken value="Don't have an account?"}
						<a href="https://lucent.institute/enroll.php" >{translateToken value="Click to create yours now."}</a>
					</p>
				</div>
			{/if}
			{if $T_CONFIGURATION.trouble_login_enable}
				<div class="create-account">
					<p>
						{translateToken value="Trouble logging in"}
						<a href="https://{$T_SYSCONFIG.deploy.environment}.sysclass.com/contact" id="register-btn" >{translateToken value="Please, click here"}</a>
					</p>
				</div>
			{/if}
		</form>					

		<!-- END LOGIN FORM -->

		<!-- BEGIN FORGOT PASSWORD FORM -->
		<form class="forget-form" action="/password-reset" method="post">
			<h3 >{translateToken value="Forgot your password"}</h3>
			<p>{translateToken value="Enter your email to reset your password."}</p>

			{if isset($T_MESSAGE) && $T_MESSAGE|@count > 0}
				<div class="alert alert-{$T_MESSAGE.type}">
					<button class="close" data-dismiss="alert"></button>
					<span>{$T_MESSAGE.message}</span>
				</div>
			{/if}
			
			<div class="form-group">
				<label class="control-label visible-ie8 visible-ie9">
					{translateToken value="Email"}
				</label>
				<div class="input-icon">
					<i class="fa fa-envelope"></i>
					<input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="{translateToken value="Email"}" name="email" id="email" />
				</div>
			</div>
			<div class="form-actions">
				<button type="button" id="back-btn" class="btn btn-default pull-right">
					<i class="ti-arrow-left"></i> {translateToken value="Back"}
				</button>
				<button type="submit" class="btn green pull-right" value="Submit" ><i class="ti-arrow-right"></i>{translateToken value="Submit"}
				</button>
			</div>
		</form>
		<!-- END FORGOT PASSWORD FORM -->

		<!-- BEGIN COPYRIGHT -->
		<div class="contentcopyright">
			<div class="copyright">
				&copy; 2018 • WiseFlex Knowledge Systems LLC. <!-- <br>{$T_SYSCONFIG.deploy.base_version}  Build {$T_SYSCONFIG.deploy.build_number}
				<span class="badge badge-primary">{$T_SYSCONFIG.deploy.branch}</span>  -->
			</div>
		</div>
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
