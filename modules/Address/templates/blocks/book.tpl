<div id="address-block">
	<div class="col-md-4">
		<div class="form-group">
			<label class="control-label">{translateToken value="Country"}</label>
			<select class="select2-me form-control" name="country_code" data-format-as="country-list">
				{foreach $T_COUNTRY_CODES as $key => $code}
					<option value="{$key}">{$code}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="control-label">{translateToken value="Phone Number"}</label>
			<input name="phone" value="" type="text" placeholder="{translateToken value="Phone Number"}" class="form-control" 
			data-type-field="phone" data-country-selector=":input[name='country_code']" 
			/>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="control-label">{translateToken value="Postal Code"}</label>
			<input name="postal_code" value="" type="text" placeholder="{translateToken value="Zipcode"}" class="form-control" data-rule-Postal Code="true" />
		</div>
	</div>
	<div class="col-md-8">
		<div class="form-group">
			<label class="control-label">{translateToken value="Street or P.O. Box"}</label>
			<input name="address" value="" type="text" placeholder="{translateToken value="Address Line 1"}" class="form-control" data-rule-minlength="5" />
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="control-label">{translateToken value="Street Number"}</label>
			<input name="street_number" value="" type="text" placeholder="{translateToken value="Street Number"}" class="form-control" data-rule-minlength="1" />
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="control-label">{translateToken value="District"}</label>
			<input name="address2" value="" type="text" placeholder="{translateToken value="District"}" class="form-control" data-rule-minlength="3" />
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="control-label">{translateToken value="City"}</label>
			<input name="city" value="" type="text" placeholder="{translateToken value="City"}" class="form-control" data-rule-minlength="3" />
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="control-label">{translateToken value="State"}</label>
			<input name="state" value="" type="text" placeholder="{translateToken value="State"}" class="form-control" />
		</div>
	</div>
</div>
