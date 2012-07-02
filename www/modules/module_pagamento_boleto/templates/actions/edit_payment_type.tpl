{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.javascript}
<form {$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.attributes}>
	{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.hidden}
	<div class="flat_area">
		<div class="grid_16 box border">
			<h2>{$smarty.const._MODULE_PAGAMENTO_BOLETO_ACCOUNT}</h2>
			<div class="flat_area">
				<div class="grid_5">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.agencia.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.agencia.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.agencia.error}
				</div>
				<div class="grid_6">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.conta_corrente.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.conta_corrente.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.conta_corrente.error}
				</div>
				<div class="grid_5">							
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.carteira.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.carteira.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.carteira.error}
				</div>
				<div class="grid_8">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.identificacao.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.identificacao.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.identificacao.error}
				</div>
				<div class="grid_8">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cpf_cnpj.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cpf_cnpj.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cpf_cnpj.error}
				</div>
				<div class="grid_16">							
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cedente.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cedente.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cedente.error}
				</div>

			</div>
		</div>
		
		<div class="grid_16 box border">
			<h2>{$smarty.const._MODULE_PAGAMENTO_BOLETO_ADDRESS}</h2>
			<div class="flat_area">
				<div class="grid_16">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cep.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cep.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cep.error}
				</div>
				<div class="grid_8">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.logradouro.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.logradouro.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.logradouro.error}
				</div>
				<div class="grid_3">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.numero.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.numero.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.numero.error}
				</div>
				<div class="grid_3">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.complemento.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.complemento.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.complemento.error}
				</div>
				<div class="grid_5">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.bairro.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.bairro.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.bairro.error}
				</div>
				<div class="grid_6">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cidade.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cidade.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.cidade.error}
				</div>
				<div class="grid_5">
					<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.uf.label}</label> 
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.uf.html}
					{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.uf.error}
				</div>
			</div>
		</div>

		<div class="grid_16 box border">
			<h2>{$smarty.const._MODULE_PAGAMENTO_BOLETO_DETAILS}</h2>
			<div class="flat_area">
				<div class="grid_4">
				<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.quantidade.label}</label> 
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.quantidade.html}
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.quantidade.error}
				</div>
				<div class="grid_4">
				<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.valor_unitario.label}</label> 
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.valor_unitario.html}
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.valor_unitario.error}
				</div>
				<div class="grid_4">
				<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.aceite.label}</label> 
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.aceite.html}
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.aceite.error}
				</div>
				<div class="grid_4">
				<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.especie.label}</label> 
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.especie.html}
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.especie.error}
				</div>
				<div class="grid_4">
				<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.especie_doc.label}</label> 
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.especie_doc.html}
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.especie_doc.error}
				</div>
				<div class="grid_4">
				<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.prazo_pagamento_matricula.label}</label> 
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.prazo_pagamento_matricula.html}
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.prazo_pagamento_matricula.error}
				</div>
				<div class="grid_16">							
				<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.demonstrativo.label}</label> 
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.demonstrativo.html}
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.demonstrativo.error}
				</div>
				
				<div class="grid_16">							
				<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.instrucoes_matricula.label}</label> 
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.instrucoes_matricula.html}
				</div>
				
				<div class="grid_16">							
				<label>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.instrucoes.label}</label> 
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.instrucoes.html}
				{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.instrucoes.error}
				</div>
			</div>
		</div>
		</div>

		<div class="clear"></div>

		<div class="grid_16">
			<button class="button_colour round_all" type="submit" name="{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.submit_preview.name}" value="{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.submit_preview.value}">
				<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
				<span>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.submit_preview.value}</span>
			</button>
			<button class="button_colour round_all" type="submit" name="{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.submit_apply.name}" value="{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.submit_apply.value}">
				<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
				<span>{$T_MODULE_PAGAMENTO_BOLETO_CONFIG_FORM.submit_apply.value}</span>
			</button>
		</div>
	</div>
</form>
