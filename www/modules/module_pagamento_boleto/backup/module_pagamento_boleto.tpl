{if $T_PAGAMENTO_PENDENTES}
	{capture name = "t_ultimos_pagamentos_registrados_table"} 
		{*TABELA DO MODULO DE PAGAMENTOS *}
	
		<table style="width: 100%" class="sortedTable" size="{$T_TABLE_SIZE}" sortBy="3" id="languagesTable">
			<tr class="defaultRowHeight">
				<td class="topTitle centerAlign">{$smarty.const._NUMERO_DOCUMENTO}</td>
				<td class="topTitle">{$smarty.const._LOGIN}</td>
				<td class="topTitle centerAlign">{$smarty.const._PAGAMENTO_VALOR}</td>
				<td class="topTitle centerAlign">{$smarty.const._PAGAMENTO_DATA_EMISSAO}</td>
				<td class="topTitle centerAlign noSort">{$smarty.const._OPERATIONS}</td>
			</tr>
			{foreach name = 'language_list' key = "name" item = "item" from = $T_PAGAMENTO_PENDENTES}
			<tr id="row_{$item.ID}" class="{cycle name = "pendentes" values="oddRowColor, evenRowColor"}">
				<td class="centerAlign">
					<a url="ask_information.php?module=module_pagamento&hash_id={$item.ID}" class="info" href="javascript:void(0)">
		           		{$item.ID}
		          	</a>
				</td>
				<td>{$item.user}</td>
				<td class="centerAlign">{$item.valor_string}</td>
				<td class="centerAlign">#filter:timestamp-{$item.data_emissao}#</td>
				{if $item.ID}
				<td class="centerAlign">
					<a 
						target="_blank"
						href="{$T_MODULE_PAGAMENTO_BASEURL}&subtype=boleto&action=get_fatura&id={$item.ID}&popup=1"
						class="module_pagamento_boleto_print"
						>
						<img
							src="images/16x16/printer.png"
							title="{$smarty.const._MOSTRAR_BOLETO}"
							alt="{$smarty.const._MOSTRAR_BOLETO}" />
					</a> 
				</td>
				{/if}
			</tr>
			{foreachelse}
			<tr class="oddRowColor defaultRowHeight">
				<td class="emptyCategory" colspan="3">{$smarty.const._NODATAFOUND}</td>
			</tr>
			{/foreach}
		</table>
	{/capture}
{/if}

{capture name="t_upload_return_file"}
	{$T_FILE_UPLOAD_FORM.javascript}
	<form {$T_FILE_UPLOAD_FORM.attributes}>
		{$T_FILE_UPLOAD_FORM.hidden}
		<table class = "formElements">
			<tr>
				<td class = "labelCell">{$T_FILE_UPLOAD_FORM.file_upload.label}:&nbsp;</td>
				<td class = "elementCell">{$T_FILE_UPLOAD_FORM.file_upload.html}</td>
			</tr>
			<tr>
				<td></td>
      			<td class = "elementCell">{$T_FILE_UPLOAD_FORM.submit_upload_file.html}</td>
      		</tr>
		</table>
	</form>
{/capture}
{if $T_SELECTED_OPTION == 'pending_payments'}

	{eF_template_printBlock 
		title=$smarty.const._PAGAMENTO_ENVIAR_ARQUIVO_RETORNO
		data=$smarty.capture.t_upload_return_file
	}
	{eF_template_printBlock 
		title=$smarty.const._ULTIMOS_BOLETOS_PENDENTES
		data=$smarty.capture.t_ultimos_pagamentos_registrados_table 
	}
{elseif $T_SELECTED_OPTION == 'pending_payments_preview'}
	{eF_template_printBlock 
		title=$smarty.const._PAGAMENTO_ENVIAR_ARQUIVO_RETORNO
		data=$smarty.capture.t_upload_return_file
	}
	
	{if $T_IMPORTED_FILE}
		{literal}
			<style>
				h2.itemTitle {
					display: block !important;
					border-bottom: 1px solid #CCCCCC;
				}
				
				h4.itemTitle, h5.itemTitle {
					display: block;
					margin: 10px 0 3px 0;
				}
				.clear {
					clear: both;
				}
				.itemLine {
					display: inline-block;
					padding: 3px 0;
					clear: both;
					width: 100%;
				}
				.itemLine label.labelCell {
					display: inline-block;
					width : 17%;
					padding: 2px 1%;
					font-weight: bold;
				}
				.itemLine span.elementCell {
					display: inline-block;
					width : 28%;
					padding: 2px 1%;
		
					border-bottom: 1px solid #000;
					border-left: 1px solid #000; 
		
				}
				.itemLine span.double {
					width : 78%;
				}
			</style>
		{/literal}
		
		
		
		{capture name="result_block"}
			{$smarty.capture.preview_header}
			{$smarty.capture.preview_registers}
			{$smarty.capture.preview_footer}
		{/capture}
		
		{eF_template_printBlock 
			title=$smarty.const._PAGAMENTO_RESULTADO_ENVIO_ARQUIVO_RETORNO
			data=$smarty.capture.result_block
		}
	{/if}
	
		
{/if}

 