{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-course" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<h3 class="form-section">{translateToken value="General"}</h3>
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
							<a href="javascript:;" class="btn btn-warning" id="username1_checker">
							<i class="fa fa-check"></i> Check </a>
						</span>
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
