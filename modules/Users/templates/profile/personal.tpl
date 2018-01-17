	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">{translateToken value="First name"}</label>
			<input name="name" readonly="readonly" value="{$T_EDIT_USER.name}" type="text" placeholder="{translateToken value="Name"}" class="form-control" />
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">{translateToken value="Last name"}</label>
			<input name="surname" readonly="readonly" value="{$T_EDIT_USER.surname}" type="text" placeholder="{translateToken value="Last name"}" class="form-control" />
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label>{translateToken value="Email"}</label>
			<div>
				<input name="email" reavalue="{$T_EDIT_USER.email}" type="text" placeholder="{translateToken value="Email"}" class="form-control">
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			<label class="control-label">{translateToken value="Birthday"}</label>
			<div>
				<input type="text" readonly="readonly" name="birthday" value="" data-format="date" data-format-from="isodate" class="form-control date-picker">
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label class="control-label">{translateToken value="Age"}</label>
			<div>
				<input type="text" readonly="readonly" name="age" value="{$T_EDIT_USER.birthday|calculate_age}" class="form-control">
			</div>
		</div>
	</div>
	
	{if (isset($T_EDIT_USER.attrs) &&  ($T_EDIT_USER.attrs|@count > 0))}
		{foreach $T_EDIT_USER.attrs as $key => $value}
			{if $key != 'zip_code' && $key != 'address' && $key != 'area_of_study' && $key != 'english_communication' && $key != 'courses' && $key != 'higher_school' && $key != 'secondary_school' && $key != 'how_did_you_learn_about' && $key != 'i_am_currently' &&  $key != 'my_calling' && $key != 'enroll_agreement' }
			<div class="col-md-6">
				<div class="form-group">
						<label class="control-label">{translateToken value=$key|user_attrs_translate}</label>
						<input{if $key != 'gender'} readonly="readonly"{/if} name="{$key}" value="{$value}" type="text" placeholder="{translateToken value="$key|user_attrs_translate"}" class="form-control" />
				</div>
			</div>
			{/if}
		{/foreach}
	{/if}
	
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">{translateToken value="Phone Number"}</label>
			<input name="phone" value="" type="text" placeholder="{translateToken value="Phone number"}" class="form-control" 
			data-type-field="phone" data-country-selector=":input[name='country_code']" 
			/>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">{translateToken value="System Language"}</label>
			<select name="language_id" class="form-control select2-me" data-placeholder="{translateToken value="Select..."}">
			{foreach $T_LANGUAGES as $key => $value}
				<option value="{$value.id}">{$value.name}</option>
			{/foreach}
			</select>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label class="control-label">{translateToken value="Timezone"}</label>
			<select name="timezone" class="form-control select2-me" data-placeholder="{translateToken value="Select..."}">
			{foreach $T_TIMEZONES as $key => $value}
				<option value="{$key}" {if $value.id == $T_EDIT_USER.timezone}selected="selected"{/if}>{$value.name}</option>
			{/foreach}
			</select>
		</div>
	</div>
	
	<!--
	<div class="col-md-12">
		<div class="form-group">
			<label class="control-label">{translateToken value="About you"}</label>
			<textarea class="form-control" name="short_description" rows="4" placeholder="{translateToken value="Talk about you.."}">{$T_EDIT_USER.short_description}</textarea>
		</div>
	</div>
	-->
	<!--
	Senha (deixe em branco para não alterar):
	Confirmar senha:

	Instituição de Ensino
	Polo de Apoio
	Grupo:
	Tipo de usuário:
	Usuário ativo:
	Data de registro no sistema: 16/02/2011
	RG
	CPF
	Cep
	Endereço
	Número
	Complemento
	Bairro
	Cidade
	Estado
	Telefone
	Celular

	<div class="form-group">
		<label class="control-label">Mobile Number</label>
		<input type="text" placeholder="+1 646 580 DEMO (6284)" class="form-control" />
	</div>
	<div class="form-group">
		<label class="control-label">Interests</label>
		<input type="text" placeholder="Design, Web etc." class="form-control" />
	</div>
	<div class="form-group">
		<label class="control-label">Occupation</label>
		<input type="text" placeholder="Web Developer" class="form-control" />
	</div>
	<div class="form-group">
		<label class="control-label">About</label>
		<textarea class="form-control" rows="3" placeholder="We are KeenThemes!!!"></textarea>
	</div>
	<div class="form-group">
		<label class="control-label">Website Url</label>
		<input type="text" placeholder="http://www.mywebsite.com" class="form-control" />
	</div>
	<div class="margiv-top-10">
		<a href="#" class="btn green">Save Changes</a>
		<a href="#" class="btn default">Cancel</a>
	</div>
-->
