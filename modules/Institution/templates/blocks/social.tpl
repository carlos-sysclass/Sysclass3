{if ($T_SHOW_COUNTRY)}
	<div class="form-group">
		<label class="control-label">{translateToken value="Country"}</label>
		<select class="select2-me form-control" name="country" data-format-as="country-list" data-rule-required="true">
			{foreach $T_COUNTRY_CODES as $key => $code}
				<option value="{$key}">{$code}</option>
			{/foreach}
		</select>
	</div>
{/if}

{if ($T_SHOW_LANGUAGE)}
	<div class="form-group">
		<label class="control-label">{translateToken value="Language"}</label>
		<select name="{$T_SHOW_LANGUAGE}" class="form-control select2-me" data-placeholder="{translateToken value="Select..."}">
		{foreach $T_LANGUAGES as $key => $value}
			<option value="{$value.code}">{$value.name}</option>
		{/foreach}
		</select>
	</div>
{/if}

<div class="form-group">
	<label class="control-label">{translateToken value="Website"}</label>
	<input name="website" value="" type="text" placeholder="Website" class="form-control" data-rule-url="true" />
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">{translateToken value="Facebook"}</label>
			<div class="input-group">
				<span class="input-group-text btn-info">  https://www.facebook.com/  </span>
				<input name="facebook" value="" type="text" placeholder="Facebook" class="form-control" />
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">{translateToken value="Linked In"}</label>
			<div class="input-group">
				<span class="input-group-text btn-info">  https://www.linkedin.com/  </span>
				<input name="linkedin" value="" type="text" placeholder="Linked in" class="form-control" />
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">{translateToken value="Skype Account"}</label>
				<input name="skype" value="" type="text" placeholder="Skype" class="form-control" />
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">{translateToken value="Google+ Account"}</label>
			<div class="input-group">
				<span class="input-group-text btn-info">  https://plus.google.com/</span>
				<input name="googleplus" value="" type="text" placeholder="Google Plus" class="form-control" />
			</div>
		</div>
	</div>
</div>
