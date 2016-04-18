{extends file="layout/login.tpl"}
{block name="content"}
	<div class="content">
		<div class="inside-logo">
			<img src="{Plico_GetResource file='img/logo.png'}" alt="" style="max-width: 100%" />
		</div>
		<!-- BEGIN LOGIN FORM -->
		<form id="signup-form" role="form" class="signup-form form-validate" method="post" action="{$T_FORM_ACTION}">
			<h4 class="form-title">{translateToken value="Please type login and password below"}</h4>
			<div class="form-body">
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

				{if !$T_DISABLE_LOGIN}
					<div class="form-group">
						<label class="control-label visible-ie8 visible-ie9">{translateToken value="Login"}</label>
						<div class="input-icon">
							<i class="fa fa-user"></i>
							<input type="text" id="login" name="login" value="{$T_USER.login}" placeholder="{translateToken value="Login"}" autocomplete="off" class="form-control placeholder-no-fix" data-rule-required="true" data-rule-minlength="4">
						</div>
					</div>
				{else}
					<div class="form-group">
						<label class="control-label visible-ie8 visible-ie9">{translateToken value="Login"}</label>
						<div class="input-icon">
							<i class="fa fa-user"></i>
							<input type="text" value="" autocomplete="off" class="form-control placeholder-no-fix" readonly="readonly">
						</div>
					</div>
				{/if}

				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">{translateToken value="New Password"}</label>
					<div class="input-icon">
						<i class="fa fa-asterisk"></i>
						<input type="password" id="password" name="password" value="" placeholder="{translateToken value="New Password"}" autocomplete="off" class="form-control placeholder-no-fix" data-rule-required="true" data-rule-minlength="4">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">{translateToken value="Confirm"}</label>
					<div class="input-icon">
						<i class="fa fa-repeat"></i>
						<input type="password" id="password-confirm" name="password-confirm" value=""  placeholder="{translateToken value='Confirm'}" autocomplete="off" class="form-control" 
							data-rule-required="true" data-rule-minlength="4" data-rule-equalTo="#password" 
							data-msg-equalTo="{translateToken value='The passwords doesn\'t match!'}"
						/>
					</div>
				</div>
			</div>
			<div class="form-actions nobg">
				<button class="btn btn-primary pull-right" type="submit">
					<i class="m-icon-swapup m-icon-white"></i>
					{translateToken value="Reset my Pass"}
				</button>
			</div>
		</form>
		<!-- END LOGIN FORM -->
		<div class="copyright">
			&copy; 2016 • WiseFlex Knowledge Systems LLC. <br />
			<span style="color: black">
				{$T_SYSCONFIG.deploy.base_version} 
				Build {$T_SYSCONFIG.deploy.build_number}
			</span> 
			<span class="badge badge-primary">{$T_SYSCONFIG.deploy.branch}</span> 
		</div>
	</div>
{/block}
