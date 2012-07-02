{include file="$T_PAGAMENTO_BASEDIR/templates/includes/payment_options.tpl"}

{capture name="t_payments_table"}

{include file="$T_PAGAMENTO_BASEDIR/templates/includes/xpayment.invoices.filters.tpl"}

<table class="display" id="_PAGAMENTO_PAID_INVOICES_LIST">
	<thead> 
		<tr>
			<th>{$smarty.const.__IES_NAME}</th>
			<th>{$smarty.const.__PAGAMENTO_BOLETO_NRO_DOCUMENTO}</th>
			<th>{$smarty.const.__PAGAMENTO_BOLETO_USUARIO}</th>
			<th>{$smarty.const.__PAGAMENTO_INVOICE_PARCELAS}</th>
			<th>{$smarty.const.__PAGAMENTO_INVOICE_VENCIMENTO}</th>
			<th>{$smarty.const.__PAGAMENTO_INVOICE_PAGAMENTO}</th>
			<th>{$smarty.const.__PAGAMENTO_BOLETO_TOTAL_TITULO}</th>
			<th>{$smarty.const.__PAGAMENTO_BOLETO_TOTAL_DESCONTO}</th>
			<th>{$smarty.const.__PAGAMENTO_BOLETO_TOTAL}</th>
			<!-- 
			<th>{$smarty.const._OPERATIONS}</th>
			 -->
		</tr> 
	</thead>
	<tbody> 
		{assign var="soma_valor_titulo" value="0"}
		{assign var="soma_valor_desconto" value="0"}
		{assign var="soma_valor_total" value="0"}
		
		{assign var="last_group" value=""}
		{assign var="grupo_valor_titulo" value="0"}
		{assign var="grupo_valor_desconto" value="0"}
		{assign var="grupo_valor_total" value="0"}

		{foreach name="paid_list" key="index" item="invoice" from = $T_PAGAMENTO_PAID_INVOICES}
			{math equation="total + preco" total=$soma_valor_titulo preco="`$invoice.valor_titulo`" assign="soma_valor_titulo"}
			{math equation="total + preco" total=$soma_valor_desconto preco="`$invoice.valor_desconto`" assign="soma_valor_desconto"}
			{math equation="total + preco" total=$soma_valor_total preco="`$invoice.valor_total`" assign="soma_valor_total"}
			
			{if ($T_PAGAMENTO_LIST_IS_GROUPED && $invoice.data_pagamento != $last_group)}
				{if $smarty.foreach.paid_list.iteration > 1 }
					{* CLOSE GROUP *}
					<tr>
						<td align="center" class="group-close">{$smarty.const.__PAGAMENTO_INVOICE_TOTAL}</td>
						<td class="group-close">&nbsp;</td>
						<td class="group-close">&nbsp;</td>
						<td class="group-close">&nbsp;</td>
						<td class="group-close">&nbsp;</td>
						<td class="group-close">&nbsp;</td>
						<td align="center" class="group-close">#filter:currency-{$grupo_valor_titulo}#</td>
						<td align="center" class="group-close">#filter:currency-{$grupo_valor_desconto}#</td>
						<td align="center" class="group-close">#filter:currency-{$grupo_valor_total}#</td>
						<!-- 
						<td>&nbsp;</td>
						 -->
					</tr> 
				{/if}
				{* OPEN GROUP *}
				<tr>
					<td class="group-open">#filter:date-{$invoice.data_pagamento}#</td>
					<td class="group-open">&nbsp;</td>
					<td class="group-open">&nbsp;</td>
					<td class="group-open">&nbsp;</td>
					<td class="group-open">&nbsp;</td>
					<td class="group-open">&nbsp;</td>
					<td class="group-open">&nbsp;</td>
					<td class="group-open">&nbsp;</td>
					<td class="group-open">&nbsp;</td>
				</tr>					
				{assign var="last_group" value=$invoice.data_pagamento}
				{assign var="grupo_valor_titulo" value="0"}
				{assign var="grupo_valor_desconto" value="0"}
				{assign var="grupo_valor_total" value="0"}
			{/if}
			
			{math equation="total + preco" total=$grupo_valor_titulo preco="`$invoice.valor_total`" assign="grupo_valor_titulo"}
			{math equation="total + preco" total=$grupo_valor_desconto preco="`$invoice.valor_total`" assign="grupo_valor_desconto"}
			{math equation="total + preco" total=$grupo_valor_total preco="`$invoice.valor_total`" assign="grupo_valor_total"}

			<tr>
				
				<td align="center">{$invoice.ies}</td>
				
				<td align="center">{$invoice.nosso_numero}</td>
				<td>
					<a href="administrator.php?ctg=module&op=module_xuser&action=edit_xuser&xuser_id={$invoice.user_id}#Detalhes_financeiros"> 
						{$invoice.username}
					</a>
				</td>
				<td align="center">{$invoice.parcela_index}/{$invoice.total_parcelas}</td>
				<td align="center">#filter:date-{$invoice.data_vencimento}#</td>
				<td align="center">#filter:date-{$invoice.data_pagamento}#</td>
				<td align="center">#filter:currency-{$invoice.valor_titulo}#</td>
				<td align="center">#filter:currency-{$invoice.valor_desconto}#</td>
				<td align="center">#filter:currency-{$invoice.valor_total}#</td>
				<!--
				<td align="center">
					<div class="button_display">
				 
						<button onclick="window.location.href = 'administrator.php?ctg=module&op=module_xuser&action=edit_xuser&xuser_login={$payment.login}#Detalhes_financeiros';" class="skin_colour round_all"">
							<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png">
							<span>Editar</span>
						</button>	
						<button onclick="window.location.href = '{$T_MODULE_PAGAMENTO_BASEURL}&action=view_payment&payment_id={$payment.payment_id}';" class="skin_colour round_all">
							<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/documents.png">
							<span>Visualizar</span>
						</button>
						<button onclick="window.open('{$T_MODULE_PAGAMENTO_BASEURL}&action=get_invoice&payment_id={$payment.payment_id}');" class="skin_colour round_all">
							<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/printer.png">
							<span>Imprimir</span>
						</button>
					 
					</div>
				</td>
				-->
			</tr>
			
			{if $T_PAGAMENTO_LIST_IS_GROUPED && $smarty.foreach.paid_list.last}
				{* CLOSE GROUP *}
				<tr>
					<td align="center" class="group-close">{$smarty.const.__PAGAMENTO_INVOICE_TOTAL}</td>
					<td class="group-close">&nbsp;</td>
					<td class="group-close">&nbsp;</td>
					<td class="group-close">&nbsp;</td>
					<td class="group-close">&nbsp;</td>
					<td class="group-close">&nbsp;</td>
					<td align="center" class="group-close">#filter:currency-{$grupo_valor_titulo}#</td>
					<td align="center" class="group-close">#filter:currency-{$grupo_valor_desconto}#</td>
					<td align="center" class="group-close">#filter:currency-{$grupo_valor_total}#</td>
					<!-- 
					<td>&nbsp;</td>
					 -->
				</tr> 
			{/if}
		{/foreach}
	</tbody>
	<tfoot> 
		<tr>
			<td align="center" class="close-group">{$smarty.const.__PAGAMENTO_INVOICE_TOTAL}</td>
			<td class="close-group">&nbsp;</td>
			<td class="close-group">&nbsp;</td>
			<td class="close-group">&nbsp;</td>
			<td class="close-group">&nbsp;</td>
			<td class="close-group">&nbsp;</td>
			<td align="center" class="close-group">#filter:currency-{$soma_valor_titulo}#</td>
			<td align="center" class="close-group">#filter:currency-{$soma_valor_desconto}#</td>
			<td align="center" class="close-group">#filter:currency-{$soma_valor_total}#</td>
			<!-- 
			<td>&nbsp;</td>
			 -->
		</tr> 
	</tfoot>
</table>
{/capture}

{eF_template_printBlock 
	title=$smarty.const.__PAGAMENTO_PAID_INVOICES_LISTs
	data=$smarty.capture.t_payments_table
	contentclass="blockContents"
}