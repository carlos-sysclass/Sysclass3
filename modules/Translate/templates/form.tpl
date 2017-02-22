{extends file="layout/default.tpl"}
{block name="content"}
<form id="form-translate" role="form" class="form-validate" method="post" action="{$T_FORM_ACTION}">
	<div class="form-body">
		<h3 class="form-section">{translateToken value="General"}</h3>

		<div class="form-group">
			<label class="control-label">{translateToken value="Name in English”}</label>
			<input name="name" value="" type="text" placeholder="Name in English” class="form-control" data-rule-required="true" data-rule-minlength="3" />
		</div>
		<div class="form-group">
			<label class="control-label">{translateToken value="Native Name"}</label>
			<input name="local_name" value="" type="text" placeholder="Local Name" class="form-control" data-rule-required="true" data-rule-minlength="3" />
		</div>
		<div class="form-group">
			<label class="control-label">{translateToken value="Country"}</label>
			<select class="select2-me form-control" name="country_code" data-format-as="country-list">
				{foreach $T_COUNTRY_CODES as $key => $code}
					<option value="{$key}">{$code}</option>
				{/foreach}
			</select>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Active"}</label>
					<select class="select2-me form-control" name="active">
						<option value="1">{translateToken value="Yes"}</option>
						<option value="0">{translateToken value="No"}</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="RTL (Right-to-Left) ?"}</label>
					<select class="select2-me form-control" name="rtl">
						<option value="0">{translateToken value="No"}</option>
						<option value="1">{translateToken value="Yes"}</option>
					</select>
				</div>
			</div>
		</div>
		<h3 class="form-section">
			<i class="icon-reorder"></i>
			{translateToken value="Eletronic Translation"}
		</h3>
		<div class="form-group">
			<label class="control-label">{translateToken value="Eletronic language to translate"}</label>
			<select class="select2-me form-control" name="code">
				{foreach $T_LANGUAGE_CODES as $key => $code}
					<option value="{$key}">{$code}</option>
				{/foreach}
			</select>
		</div>
		<!--
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Start date"}</label>
					<input class="form-control input-small date-picker"  size="16" type="text" name="timestamp[date]" data-update="timestamp" data-format="date" data-format-from="unix-timestamp" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Start time"}</label>
					<input type="text" class="form-control timepicker-24 input-medium" name="timestamp[time]" data-update="timestamp" data-format="time" data-format-from="unix-timestamp">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Completion date"}</label>
					<input class="form-control input-small date-picker"  size="16" type="text" name="expire[date]" data-update="expire" data-format="date" data-format-from="unix-timestamp" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">{translateToken value="Expiration time"}</label>
					<input type="text" class="form-control timepicker-24 input-medium" name="expires[time]" data-update="expires" data-format="time" data-format-from="unix-timestamp">
				</div>
			</div>
		</div>
		-->
		{if (isset($T_SECTION_TPL['permission']) &&  ($T_SECTION_TPL['permission']|@count > 0))}
		    {foreach $T_SECTION_TPL['permission'] as $template}
		        {include file=$template}
		    {/foreach}
		{/if}
	</div>
	<div class="form-actions nobg">
		<button class="btn btn-success" type="submit">{translateToken value="Save changes"}</button>
	</div>
</form>
{/block}