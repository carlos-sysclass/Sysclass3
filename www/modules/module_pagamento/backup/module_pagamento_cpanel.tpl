{capture name = "t_ultimos_pagamentos_registrados_table"} 
	{*TABELA DO MODULO DE PAGAMENTOS *}
	<script>
	{literal}
	    var pagamentomodulebaseurl = '{$T_MODULE_PAGAMENTO_BASEURL}';
	    var pagamentomodulebaselink = '{$T_MODULE_PAGAMENTO_BASELINK}';
/*
	    jQuery(document).ready(function($){
	    	jQuery(".module_pagamento_boleto_print").colorbox({innerWidth: "800px", height:"90%", iframe:true});
	    });
*/
    {/literal}
	</script>
	<table style="width: 100%" class="sortedTable" size="{$T_TABLE_SIZE}" sortBy="3" id="languagesTable">
		<tr class="defaultRowHeight">
			<td class="topTitle">{$smarty.const._NUMERO_DOCUMENTO}</td>
			<td class="topTitle">{$smarty.const._LOGIN}</td>
			<td class="topTitle centerAlign">{$smarty.const._PAGAMENTO_VALOR}</td>
			<td class="topTitle centerAlign">{$smarty.const._PAGAMENTO_DATA_EMISSAO}</td>
			<td class="topTitle centerAlign noSort">{$smarty.const._OPERATIONS}</td>
		</tr>
		{foreach name = 'language_list' key = "name" item = "item" from = $T_PAGAMENTO_PENDENTES}
		<tr id="row_{$item.ID}" class="{cycle name = "languages" values="oddRowColor, evenRowColor"}">
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
			<td class="emptyCategory centerAlign" colspan="5">{$smarty.const._NODATAFOUND}</td>
		</tr>
		{/foreach}
	</table>
{/capture} 
{eF_template_printBlock 
	title=$smarty.const._ULTIMOS_PAGAMENTOS
	data=$smarty.capture.t_ultimos_pagamentos_registrados_table 
	image=$T_MODULE_PAGAMENTO_BASELINK|cat:'images/pagamento.png'
	absoluteImagePath = 1 link = $T_MODULE_PAGAMENTO_BASEURL
	options = $T_MODULE_PAGAMENTO_OPTIONS 
}
