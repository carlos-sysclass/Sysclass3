	{capture name="preview_header"}
		{assign var=t_status value=$T_PROCESS_FILE_STATUS.header}
			<div class="grid_12">
				<label><strong>Retorno:</strong></label>
				<span>{$t_status.cod_retorno.formatteddata} - {$t_status.retorno.formatteddata}</span>
			</div>
			<div class="grid_12">
				<label><strong>Serviço:</strong></label>
				<span>{$t_status.cod_servico.formatteddata} - {$t_status.servico.formatteddata}</span>
			</div>
			<div class="grid_12">
				<label><strong>Nome do Banco:</strong></label>
				<span>{$t_status.cod_banco.formatteddata} - {$t_status.nome_banco.formatteddata}</span>
			</div>
			<div class="grid_12">
				<label><strong>Agência / Conta - DV:</strong></label>
				<span>{$t_status.agencia.formatteddata} / {$t_status.conta.formatteddata}-{$t_status.dac.formatteddata}</span>
			</div>
			<div class="grid_24">
				<label><strong>Empresa Cedente:</strong></label>
				<span class="elementCell double">{$t_status.nome_empresa.formatteddata}</span>
			</div>
			<div class="grid_12">
				<label><strong>Data de geração:</strong></label>
				<span>{$t_status.data_geracao.formatteddata}</span>
			</div>
			<div class="grid_12">
				<label><strong>Data de Crédito:</strong></label>
				<span>{$t_status.data_credito.formatteddata}</span>
			</div>
			<div class="grid_12">
				<label><strong>Densidade:</strong></label>
				<span>{$t_status.densidade.formatteddata} {$t_status.uni_densidade.formatteddata}</span>
			</div>
			<div class="grid_12">
				<label><strong>N&ordm; Seq. do Arquivo:</strong></label>
				<span>{$t_status.nro_seq_arquivo_retorno.formatteddata}</span>
			</div>

	{/capture}
	
	{capture name="preview_registers"}
		{assign var=t_status value=$T_PROCESS_FILE_STATUS.registros}
				<table class="style1" id="_PAGAMENTO_BOLETO_FILE_RETURN_LIST">
					<thead> 
						<tr>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_CARTEIRA_BOLETO}</th>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_NRO_DOCUMENTO}</th>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_SACADO}</th>
 							<th>{$smarty.const.__PAGAMENTO_BOLETO_OCORRENCIA}</th> 
							<th>{$smarty.const.__PAGAMENTO_BOLETO_DATA_PAGAMENTO}</th>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_FORMA_LIQUIDACAO}</th>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_TOTAL_TITULO}</th>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_TOTAL_DESCONTO}</th>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_TOTAL}</th>
							<!-- 
							<th>{$smarty.const._OPERATIONS}</th>
							 -->
						</tr>
					</thead>
					<tbody>
					{assign var="soma_valor_total" value="0"}
					{assign var="soma_qtde" value="0"}
					
					{foreach name = 'trans_list' key = "name" item = "item" from = $t_status}
						{if $item.valor_total.parseddata > 0}
							{math equation="total + preco" total=$soma_valor_total preco="`$item.valor_total.parseddata`" assign="soma_valor_total"}
							{math equation="qtde + 1" qtde=$soma_qtde assign="soma_qtde"}
							<tr id="row_{$trans_list}">
								<td align="center">{$item.carteira.formatteddata}</td>
								<td align="center">
									{if $item.tag.invoice_number_invalid}
										<a href="javascript: void(0);" style="color: red" title="{$smarty.const.__PAGAMENTO_BOLETO_INVALID_INVOICE_NUMBER_HINT}">
											{$item.nosso_numero2.formatteddata} - {$item.dac_nosso_numero2.formatteddata}
										</a>
									{else}
										{$item.nosso_numero2.formatteddata} - {$item.dac_nosso_numero2.formatteddata}
									{/if}
								</td>
								<td align="center">{$item.nome_sacado.formatteddata}</td>
								<td align="center">{$item.cod_ocorrencia.formatteddata} - {$T_BASE_OCORRENCIAS[$item.cod_ocorrencia.originaldata]}</td>
								<td align="center">{$item.data_ocorrencia.formatteddata}</td>
								<td align="center">{$item.cod_liquidacao.formatteddata} - {$T_BASE_LIQUIDACAO[$item.cod_liquidacao.originaldata]}</td>
								<td align="center">{$item.valor_titulo.formatteddata}</td>
								<td align="center">{$item.valor_desconto.formatteddata}</td>
								<td align="center">{$item.valor_total.formatteddata}</td>
								<!-- 
								<td align="center">
									<div class="button_display">
										
										{if $item.tag}
											{if !$item.tag.invoice_number_invalid}
											<button onclick="window.location.href = 'administrator.php?ctg=module&op=module_xuser&action=edit_xuser&xuser_login={$item.tag.cliente.login}#Detalhes_financeiros';" class="skin_colour round_all">
												<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png">
												<span>Editar</span>
											</button>
											{/if}
											{if !$item.tag.invoice_number_invalid}
											<button onclick="openInvoicePrintWindow({$item.tag.payment_id}, '{$item.nosso_numero2.parseddata}');" class="skin_colour round_all">
												<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/printer.png">
												<span>Imprimir</span>
											</button>
											{/if}
										{else}
											<button class="red skin_colour round_all deleteFileInvoiceReturn" title = "{$smarty.const._DELETE}" onclick = "javascript: void(0);">
												<img 
													class = "ajaxHandle" width="16" height="16" alt = "{$smarty.const._DELETE}" 
													src = "/{$smarty.const.G_CURRENTTHEMEURL}/images/icons/small/white/trashcan.png" />
											</button>
										{/if}
										 
									</div>
								</td>
								-->
							</tr>
						{/if}
					{/foreach}
					</tbody> 
				</table>
	{/capture}
	
	{capture name="preview_footer"}
		{assign var=t_status value=$T_PROCESS_FILE_STATUS.footer}
			<!-- 
			<h4>Cobrança Simples</h4>
			<div class="grid_5">
				<label class="labelCell">Qtde:</label>
				<span class="elementCell">{$t_status.qtde_cobr_simples.formatteddata}</span>
			</div>
			<div class="grid_6">
				<label class="labelCell">Valor Total:</label>
				<span class="elementCell">{$t_status.valor_total_simples.formatteddata}</span>
			</div>
			<div class="grid_5">
				<label class="labelCell">Aviso Bancário:</label>
				<span class="elementCell">{$t_status.aviso_bancario_simples.formatteddata}</span>
			</div>
			<div class="clear"></div>
			<br />
			
			<h4 class="itemTitle">Cobrança Vinculada</h4>
			<div class="grid_5">
				<label class="labelCell">Qtde:</label>
				<span class="elementCell">{$t_status.qtde_cobr_simples.formatteddata}</span>
			</div>
			<div class="grid_6">
				<label class="labelCell">Valor Total:</label>
				<span class="elementCell">{$t_status.valor_total_simples.formatteddata}</span>
			</div>
			<div class="grid_5">
				<label class="labelCell">Aviso Bancário:</label>
				<span class="elementCell">{$t_status.aviso_bancario_simples.formatteddata}</span>
			</div>
			<div class="clear"></div>
			<br />
			
			<h4>Cobrança Direta / Escritural</h4>
			<div class="grid_5">
				<label class="labelCell">Qtde:</label>
				<span class="elementCell">{$t_status.qtde_cobr_simples.formatteddata}</span>
			</div>
			<div class="grid_6">
				<label class="labelCell">Valor Total:</label>
				<span class="elementCell">{$t_status.valor_total_simples.formatteddata}</span>
			</div>
			<div class="grid_5">
				<label class="labelCell">Aviso Bancário:</label>
				<span class="elementCell">{$t_status.aviso_bancario_simples.formatteddata}</span>
			</div>
			<div class="clear"></div>
			<br />
			 -->
			<div class="grid_12">
				<label><strong>Qtde:</strong></label>
				<span>{$soma_qtde}</span>
			</div>
			<div class="grid_12">
				<label><strong>Valor Total:</strong></label>
				<span>#filter:currency-{$soma_valor_total}#</span>
			</div>
	{/capture}
	
	<div class="container_24 itau-analyze-html-container">
		<div id="_PAGAMENTO_BOLETO_FILE_HEADER">
			{$smarty.capture.preview_header}
		</div>
		<div class="clear"></div>
		<hr />
		
		<div id="_PAGAMENTO_BOLETO_FILE_REGISTERS">
			{$smarty.capture.preview_registers}
		</div>
		<div class="clear"></div>
		<hr />
			
		<div id="_PAGAMENTO_BOLETO_FILE_FOOTER">
			{$smarty.capture.preview_footer}
		</div>
	</div>