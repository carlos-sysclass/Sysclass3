<form role="form" class="" method="post" action="{$T_FORM_ACTIONS.personal}">
	<div class="form-body">
		<div class="form-group">
			<label class="control-label">First Name</label>
			<input name="name" value="{$T_EDIT_USER.name}" type="text" placeholder="Name" class="form-control" />
		</div>

		<div class="form-group">
			<label class="control-label">Last Name</label>
			<input name="surname" value="{$T_EDIT_USER.surname}" type="text" placeholder="Surname" class="form-control" />
		</div>
		<div class="form-group">
			<label>Email Address</label>
			<div class="input-group">
				<span class="input-group-addon"><i class="icon-envelope"></i></span>
				<input name="email" value="{$T_EDIT_USER.email}" type="text" placeholder="Email Address" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">Birthday</label>
			<div class="input-group">                                       
				<span class="input-group-addon"><i class="icon-calendar"></i></span>
				<input type="text" name="data_nascimento" value="{$T_EDIT_USER.data_nascimento}" readonly class="form-control datepick" data-format="{$T_SETTINGS_.js_date_fmt}" data-date-view-mode="years">
			</div>
		</div>

		<div class="form-group">
			<label class="control-label">Language</label>
			<select name="languages_NAME" class="form-control select2-me" data-placeholder="Select...">
			{foreach $T_LANGUAGES as $key => $value}
				<option value="{$key}" {if $key == $T_EDIT_USER.languages_NAME}selected="selected"{/if}>{$value}</option>
			{/foreach}
			</select>
		</div>
		<div class="form-group">
			<label class="control-label">Fuso horário</label>
			<select name="timezone" class="form-control select2-me" data-placeholder="Select...">
			{foreach $T_TIMEZONES as $key => $value}
				<option value="{$key}" {if $key == $T_EDIT_USER.timezone}selected="selected"{/if}>{$value}</option>
			{/foreach}
			</select>
		</div>
		<div class="form-group">
			<label class="control-label">About You</label>
			<textarea class="form-control" name="short_description" rows="4" placeholder="Talk about you..">{$T_EDIT_USER.short_description}</textarea>
		</div>
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
	</div>
	<div class="margin-top-10">
		<button class="btn green" type="submit">Save Changes</button>
	</div>
</form>