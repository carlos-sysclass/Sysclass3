{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-institution" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<h3 class="form-section">{translateToken value='General'}</h3>
		<div class="form-group">
			<label class="control-label">{translateToken value='Name'}</label>
			<input name="name" value="" type="text" placeholder="Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
		</div>
		<div class="form-group">
			<label class="control-label">{translateToken value='Full Name'}</label>
			<input name="formal_name" type="text" placeholder="Full Name" class="form-control" data-rule-required="true" data-rule-minlength="10" />
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label">{translateToken value='Observations'}</label>
			<textarea class="wysihtml5 form-control placeholder-no-fix" id="observations" name="observations" rows="6" placeholder="{translateToken value='Put your observations here...'}" data-rule-required="true"></textarea>
		</div>

		
		{foreach $T_BLOCK_TEMPLATES as $template}
			{include file=$template}
		{/foreach}
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value='Save Changes'}</button>
	</div>
</form>
{/block}