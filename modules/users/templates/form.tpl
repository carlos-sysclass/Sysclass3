{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-{$T_MODULE_ID}" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<h3 class="form-section">{translateToken value="General"}</h3>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Name"}</label>
					<input name="name" value="" type="text" placeholder="{translateToken value="Name"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Surname"}</label>
					<input name="surname" value="" type="text" placeholder="{translateToken value='Surname'}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value='Email'}</label>
					<input name="email" value="" type="text" placeholder="{translateToken value='Email'}" class="form-control" data-rule-required="true" data-rule-email="true" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Primary Group"}</label>
					<select class="select2-me form-control input-block-level" name="group_id" data-rule-required="1" data-rule-min="1"  data-placeholder="{translateToken value='Primary Group'}" data-url="/module/groups/items/me/select2">
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Primary Language"}</label>
					<select class="select2-me form-control input-block-level" name="language_id" data-rule-required="1" data-rule-min="1"  data-placeholder="{translateToken value='Language'}" data-url="/module/translate/items/me/select2">
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<div class="radio-list">
						<label class="radio-inline">
							<input type="radio" name="active" value="1"> {translateToken value="Yes"}
						</label>
						<label class="radio-inline">
							<input type="radio" name="active" value="0"> {translateToken value="No"}
						</label>
					</div>
				</div>

			</div>
		</div>
		<!--
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Name"}</label>
					<input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Login"}</label>
					<div class="input-group">

						<input name="login" value="" type="text" placeholder="Login" class="form-control" data-rule-required="true" data-rule-login="true" data-rule-check-duplicate="true" />
						<span class="input-group-btn">
							<a href="javascript:;" class="btn blue" id="username1_checker">
							<i class="fa fa-check"></i> Check </a>
						</span>
					</div>
				</div>
			</div>
		</div>
		-->
		<div class="clearfix"></div>

		{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
		    {foreach $T_SECTION_TPL['permission'] as $template}
		        {include file=$template}
		    {/foreach}
		{/if}
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save Changes"}</button>
	</div>
</form>
{/block}
