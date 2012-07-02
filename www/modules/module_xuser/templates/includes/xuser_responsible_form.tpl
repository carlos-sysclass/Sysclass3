{foreach key="resp_key" item="resp_form" from=$T_MODULE_XUSER_BASIC_RESPONSIBLE_FORMS}

	{capture name="t_responsible_form"}
	<div class="blockContents">
		
		<h2>{$T_RESPONSIBLE_TYPES[$resp_key]}</h2>
		<hr />
		{$resp_form.javascript}
		<form {$resp_form.attributes}>
			{$resp_form.hidden}
			<div class="grid_8">
				<label>{$resp_form.res_name.label}:</label>
				{$resp_form.res_name.html}
	
				<label>{$resp_form.res_surname.label}:</label>
				{$resp_form.res_surname.html}
				
				<label>{$resp_form.res_email.label}:</label>
				{$resp_form.res_email.html}
	
				<label>{$resp_form.res_data_nascimento.label}</label>
				{$resp_form.res_data_nascimento.html}
				
				<label>{$resp_form.res_rg.label}</label>
				{$resp_form.res_rg.html}
				<label>{$resp_form.res_cpf.label}</label>
				{$resp_form.res_cpf.html}
				<label>{$resp_form.res_telefone.label}</label>
				{$resp_form.res_telefone.html}
				<label>{$resp_form.res_celular.label}</label>
				{$resp_form.res_celular.html}
			</div>
	      	<div class="grid_8">
				<label>{$resp_form.res_cep.label}</label>
				{$resp_form.res_cep.html}
				<label>{$resp_form.res_endereco.label}</label>
				{$resp_form.res_endereco.html}
				<label>{$resp_form.res_numero.label}</label>
				{$resp_form.res_numero.html}
				<label>{$resp_form.res_complemento.label}</label>
				{$resp_form.res_complemento.html}
				<label>{$resp_form.res_bairro.label}</label>
				{$resp_form.res_bairro.html}
				<label>{$resp_form.res_cidade.label}</label>
				{$resp_form.res_cidade.html}
				<label>{$resp_form.res_uf.label}</label>
				{$resp_form.res_uf.html}
			</div>
			<div class="clear"></div>
			<div class="grid_24" style="margin-top: 20px;">
				<button class="button_colour round_all" type="submit" name="{$resp_form.res_submit_xuser.name}" value="{$resp_form.res_submit_xuser.value}">
					<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
					<span>{$resp_form.res_submit_xuser.value}</span>
				</button>
			</div>
		</form>
	</div>
	
	{/capture}
	
	{$smarty.capture.t_responsible_form}
	
{/foreach}
