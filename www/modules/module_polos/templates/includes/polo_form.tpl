<div class="blockContents" style="float: left;">
 	{$T_MODULE_POLOS_FORM.javascript}
	<form {$T_MODULE_POLOS_FORM.attributes}>
		{$T_MODULE_POLOS_FORM.hidden}
		<div class="grid_12">	
			<label>{$T_MODULE_POLOS_FORM.nome.label}</label> 
			{$T_MODULE_POLOS_FORM.nome.html}
			{$T_MODULE_POLOS_FORM.nome.error}
			
			<label>{$T_MODULE_POLOS_FORM.razao_social.label}</label> 
			{$T_MODULE_POLOS_FORM.razao_social.html}
			{$T_MODULE_POLOS_FORM.razao_social.error}
			
			<label>{$T_MODULE_POLOS_FORM.ies_id.label}</label> 
			{$T_MODULE_POLOS_FORM.ies_id.html}
			{$T_MODULE_POLOS_FORM.ies_id.error}
			
			<label>{$T_MODULE_POLOS_FORM.contato.label}</label> 
			{$T_MODULE_POLOS_FORM.contato.html}
			{$T_MODULE_POLOS_FORM.contato.error}
			
			<label>{$T_MODULE_POLOS_FORM.telefone.label}</label> 
			{$T_MODULE_POLOS_FORM.telefone.html}
			{$T_MODULE_POLOS_FORM.telefone.error}
			
			<label>{$T_MODULE_POLOS_FORM.celular.label}</label> 
			{$T_MODULE_POLOS_FORM.celular.html}
			{$T_MODULE_POLOS_FORM.celular.error}
			
			<label>{$T_MODULE_POLOS_FORM.active.label}</label> 
			{$T_MODULE_POLOS_FORM.active.html}
			{$T_MODULE_POLOS_FORM.active.error}
			
			<label>{$T_MODULE_POLOS_FORM.observacoes.label}</label> 
			{$T_MODULE_POLOS_FORM.observacoes.html}
			{$T_MODULE_POLOS_FORM.observacoes.error}
			
		</div>
		<div class="grid_12">
			<label>{$T_MODULE_POLOS_FORM.cep.label}</label> 
			{$T_MODULE_POLOS_FORM.cep.html}
			{$T_MODULE_POLOS_FORM.cep.error}
			
			<label>{$T_MODULE_POLOS_FORM.endereco.label}</label> 
			{$T_MODULE_POLOS_FORM.endereco.html}
			{$T_MODULE_POLOS_FORM.endereco.error}

			<label>{$T_MODULE_POLOS_FORM.numero.label}</label> 
			{$T_MODULE_POLOS_FORM.numero.html}
			{$T_MODULE_POLOS_FORM.numero.error}
			
			<label>{$T_MODULE_POLOS_FORM.complemento.label}</label> 
			{$T_MODULE_POLOS_FORM.complemento.html}
			{$T_MODULE_POLOS_FORM.complemento.error}
			
			<label>{$T_MODULE_POLOS_FORM.bairro.label}</label> 
			{$T_MODULE_POLOS_FORM.bairro.html}
			{$T_MODULE_POLOS_FORM.bairro.error}
			
			<label>{$T_MODULE_POLOS_FORM.cidade.label}</label> 
			{$T_MODULE_POLOS_FORM.cidade.html}
			{$T_MODULE_POLOS_FORM.cidade.error}
			
			<label>{$T_MODULE_POLOS_FORM.uf.label}</label> 
			{$T_MODULE_POLOS_FORM.uf.html}
			{$T_MODULE_POLOS_FORM.uf.error}
			

		</div>
		<div class="grid_24">
			<button class="button_colour round_all">
				<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
				<span>{$T_MODULE_POLOS_FORM.submit_polo.value}</span>
			</button>
		</div>
	</form>
</div>