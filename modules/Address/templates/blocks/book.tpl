<div id="address-block">
	<div class="form-group">
		<label class="control-label">{translateToken value="Country"}</label>
		<select class="select2-me form-control" name="country_code" data-format-as="country-list">
			{foreach $T_COUNTRY_CODES as $key => $code}
				<option value="{$key}">{$code}</option>
			{/foreach}
		</select>
	</div>
	<div class="form-group">
		<label class="control-label">{translateToken value="Phone Number"}</label>
		<input name="phone" value="" type="text" placeholder="{translateToken value="Phone Number"}" 	class="form-control" 
		data-type-field="phone" data-country-selector=":input[name='country_code']" 
		/>
		<!--
		data-country-field="country_code" 
		data-country-value="US" 
		-->

	</div>
	<div class="form-group">
		<label class="control-label">{translateToken value="Zipcode"}</label>
		<input name="zip" value="" type="text" placeholder="{translateToken value="Zipcode"}" class="form-control" data-rule-zipcode="true" />
	</div>
	<div class="form-group">
		<label class="control-label">{translateToken value="Address Line 1"}</label>
		<input name="address" value="" type="text" placeholder="{translateToken value="Address Line 1"}" class="form-control" data-rule-required="true" data-rule-minlength="3" />
	</div>
	<div class="form-group">
		<label class="control-label">{translateToken value="Address Line 2"}</label>
		<input name="address2" value="" type="text" placeholder="{translateToken value="Address Line 2"}" class="form-control" data-rule-minlength="3" />
	</div>
	<div class="form-group">
		<label class="control-label">{translateToken value="City"}</label>
		<input name="city" value="" type="text" placeholder="{translateToken value="City"}" class="form-control" data-rule-minlength="3" />
	</div>
	<div class="form-group">
		<label class="control-label">{translateToken value="State"}</label>
		<input name="state" value="" type="text" placeholder="{translateToken value="State"}" class="form-control" data-rule-minlength="3" />
	</div>
</div>
