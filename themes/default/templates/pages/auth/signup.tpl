{extends file="layout/login.tpl"}
{block name="content"}
	<div class="content">
		<div class="inside-logo">
			<img src="{Plico_GetResource file='img/logo.png'}" alt="" style="max-width: 100%" />
		</div>
		<!-- BEGIN LOGIN FORM -->
		<form id="signup-form" role="form" class="signup-form form-validate" method="post" action="/signup">
			<h3 class="form-title">{translateToken value="Create a new account"}</h3>
			<div class="form-body">
				<div class="alert alert-danger hidden">
					<button class="close" data-dismiss="alert"></button>
					<span>{translateToken value="There are some errors"}</span>
				</div>

				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">{translateToken value="Name"}</label>
					<div class="input-icon">
						<i class="fa fa-user"></i>
						<input name="name" value="" type="text" placeholder="{translateToken value="Name"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">{translateToken value="Last name"}</label>
					<div class="input-icon">
						<i class="fa fa-user"></i>
						<input name="surname" value="" type="text" placeholder="{translateToken value='Surname'}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label visible-ie8 visible-ie9">{translateToken value='Email'}</label>
					<div class="input-icon">
						<i class="fa fa-envelope"></i>
						<input name="email" value="" type="text" placeholder="{translateToken value='Email'}" class="form-control" data-rule-required="true" data-rule-email="true" />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label  visible-ie8 visible-ie9">{translateToken value="Main language"}</label>
						<select class="select2-me form-control input-block-level" name="language_id" data-rule-required="1" data-rule-min="1"  data-placeholder="{translateToken value='Primary Language'}">
							<option value="">{translateToken value="Main Language"}</option>
							{foreach $T_LANGUAGES as $lang}
								<option value="{$lang.id}">{$lang.name}</option>
							{/foreach}
						</select>
				</div>
			</div>
			<div class="form-actions nobg">
				<a href="/" class="btn btn-default">
					<i class="ti-arrow-left"></i> {translateToken value="Back"}
				</a>

				<button class="btn btn-primary pull-right" type="submit">
					<i class="ti-arrow-up"></i>
					{translateToken value="Create my account"}
				</button>
			</div>
		</form>
		<!-- END LOGIN FORM -->
		<div class="copyright">
			&copy; 2017 • WiseFlex Knowledge Systems LLC. <br />
			<span style="color: black">
				{$T_SYSCONFIG.deploy.base_version} 
				Build {$T_SYSCONFIG.deploy.build_number}
			</span> 
			<span class="badge badge-primary">{$T_SYSCONFIG.deploy.branch}</span> 
		</div>
	</div>
{/block}
