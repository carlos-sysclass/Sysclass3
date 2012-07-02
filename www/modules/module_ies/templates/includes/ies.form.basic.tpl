<div class="blockContents" style="float:left;">
 	{$T_IES_BASIC_FORM.javascript}
	<form {$T_IES_BASIC_FORM.attributes}>
		{$T_IES_BASIC_FORM.hidden}
		<div class="grid_12">	
			<label>{$T_IES_BASIC_FORM.nome.label}</label> 
			{$T_IES_BASIC_FORM.nome.html}
			{$T_IES_BASIC_FORM.nome.error}
			
			<label>{$T_IES_BASIC_FORM.razao_social.label}</label> 
			{$T_IES_BASIC_FORM.razao_social.html}
			{$T_IES_BASIC_FORM.razao_social.error}
			
			<label>{$T_IES_BASIC_FORM.contato.label}</label> 
			{$T_IES_BASIC_FORM.contato.html}
			{$T_IES_BASIC_FORM.contato.error}
			
			<label>{$T_IES_BASIC_FORM.telefone.label}</label> 
			{$T_IES_BASIC_FORM.telefone.html}
			{$T_IES_BASIC_FORM.telefone.error}
			
			<label>{$T_IES_BASIC_FORM.celular.label}</label> 
			{$T_IES_BASIC_FORM.celular.html}
			{$T_IES_BASIC_FORM.celular.error}
			
			<label>{$T_IES_BASIC_FORM.active.label}</label> 
			{$T_IES_BASIC_FORM.active.html}
			{$T_IES_BASIC_FORM.active.error}
			
			<label>{$T_IES_BASIC_FORM.observacoes.label}</label> 
			{$T_IES_BASIC_FORM.observacoes.html}
			{$T_IES_BASIC_FORM.observacoes.error}
			
		</div>
		<div class="grid_12">
			<label>{$T_IES_BASIC_FORM.cep.label}</label> 
			{$T_IES_BASIC_FORM.cep.html}
			{$T_IES_BASIC_FORM.cep.error}
			
			<label>{$T_IES_BASIC_FORM.endereco.label}</label> 
			{$T_IES_BASIC_FORM.endereco.html}
			{$T_IES_BASIC_FORM.endereco.error}

			<label>{$T_IES_BASIC_FORM.numero.label}</label> 
			{$T_IES_BASIC_FORM.numero.html}
			{$T_IES_BASIC_FORM.numero.error}
			
			<label>{$T_IES_BASIC_FORM.complemento.label}</label> 
			{$T_IES_BASIC_FORM.complemento.html}
			{$T_IES_BASIC_FORM.complemento.error}
			
			<label>{$T_IES_BASIC_FORM.bairro.label}</label> 
			{$T_IES_BASIC_FORM.bairro.html}
			{$T_IES_BASIC_FORM.bairro.error}
			
			<label>{$T_IES_BASIC_FORM.cidade.label}</label> 
			{$T_IES_BASIC_FORM.cidade.html}
			{$T_IES_BASIC_FORM.cidade.error}
			
			<label>{$T_IES_BASIC_FORM.uf.label}</label> 
			{$T_IES_BASIC_FORM.uf.html}
			{$T_IES_BASIC_FORM.uf.error}
		</div>
		<div class="grid_24">
			<button class="button_colour round_all" type="submit" name="{$T_IES_BASIC_FORM.submit_ies.name}" value="{$T_IES_BASIC_FORM.submit_ies.value}">
				<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
				<span>{$T_IES_BASIC_FORM.submit_ies.value}</span>
			</button>
		</div>
	</form>
</div>