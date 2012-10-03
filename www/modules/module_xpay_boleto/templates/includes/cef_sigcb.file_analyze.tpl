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
				<label><strong>N&ordm; Seq. do Arquivo:</strong></label>
				<span>{$t_status.nro_seq_arquivo_retorno.formatteddata}</span>
			</div>
	{/capture}
	
	{capture name="preview_registers"}
		{foreach item="t_batch" from=$T_PROCESS_FILE_STATUS.batch}
			{assign var=t_status value=$T_PROCESS_FILE_STATUS.batch[0].registros}
				<table class="style1" id="_PAGAMENTO_BOLETO_FILE_RETURN_LIST">
					<thead> 
						<tr>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_CARTEIRA_BOLETO}</th>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_NRO_DOCUMENTO}</th>
							<th>{$smarty.const.__PAGAMENTO_BOLETO_SACADO}</th>
							<!-- 
 							<th>{$smarty.const.__PAGAMENTO_BOLETO_OCORRENCIA}</th>
 							 --> 
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
							<td align="center">{$item.cod_carteira.formatteddata}</td>
							<td align="center">
								{$item.nosso_numero.formatteddata}
							</td>
							<td align="center">{$item.nome_sacado.formatteddata}</td>
							<!-- 
							<td align="center">{$item.motivo_ocorrencia.formatteddata} - {$T_BASE_OCORRENCIAS[$item.cod_ocorrencia.originaldata]}</td>
							 -->
							<td align="center">{$item.data_ocorrencia.formatteddata}</td>
							<td align="center">{$item.banco_receptor.formatteddata} - {$T_BASE_BANCOS[$item.banco_receptor.parseddata]}</td>
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
		{/foreach}
	{/capture}
	
	{capture name="preview_footer"}
		{assign var=t_status value=$T_PROCESS_FILE_STATUS.footer}
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