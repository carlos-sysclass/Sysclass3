{if $T_PAYMENT.invoices|@count > 0}
	{*include file="$T_MODULE_PAGAMENTO_BASEDIR/templates/includes/javascript.tpl"*}
	
	<style>
	{literal}
	.dataTables_scrollFoot {
		margin-bottom: 20px;		
	}
	{/literal}
	</style>
	{capture name = "t_payments_list_code"}
	
		<table id="_PAGAMENTO_DASHBOARD_INVOICES">
			<thead>
				<tr>
					<th>{$smarty.const._PAGAMENTO_NRO}</th>
					<th>{$smarty.const._PAGAMENTO_VALOR}</th>
					<th>{$smarty.const._PAGAMENTO_VENCIMENTO}</th>
					<th>{$smarty.const._OPTIONS}</th>
				</tr>
			</thead>
			<tbody>
				{assign var="invoice_total" value="0"}
				{assign var="invoice_total_desconto" value="0"}
				{foreach name="edit_payment_invoices_iteration" key="payment_invoices_index" item="invoice" from=$T_PAYMENT.invoices}
				
					{if $invoice.bloqueio == 0 && $invoice.pago == 0}
					
						{math equation="total + preco" total=$invoice_total preco="`$invoice.valor`" assign="invoice_total"}
						{math equation="total + preco" total=$invoice_total_desconto preco="`$invoice.valor_desconto`" assign="invoice_total_desconto"}
						<tr class="{cycle values="odd,even"} {$invoice.status|lower} {if $invoice.bloqueio == 1}bloqueio{/if} {if $invoice.pago == 1}pago{/if}" metadata="{Mag_Json_Encode data=$invoice}">
							<td align="center">
								{if $invoice.parcela_index == $T_PAYMENT.next_invoice}
									<img width="16" height="16" alt="Próximo E-mail" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/mail.png">
								{/if}
								{if $invoice.parcela_index-1 == 0}{$smarty.const._PAGAMENTO_MATRICULA}{else}{$invoice.parcela_index-1}{/if}
							</td>
							<td align="center">#filter:currency-{$invoice.valor}#</td>
							<td align="center">#filter:date-{$invoice.data_vencimento}#</td>
							<td align="center">
								<div class="button_display">
									<button class="skin_colour round_all invoicePrintLink" style="{if $invoice.pago == 0}visibility: visible;{else}visibility: hidden;{/if}">
										<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/printer.png">
										<span>Impirmir</span>
									</button>
								</div>
							</td>
						</tr>
					{/if}
				{foreachelse}
					<tr>
						<td colspan="10">{$smarty.const._PAGAMENTO_NOINVOICESFOUND}</td>
					</tr>
				{/foreach}
			</tbody>
			{if $T_PAYMENT.invoices}
			<tfoot>
				<tr>
					<th>{$smarty.const._PAGAMENTO_SUM}</th>
					<th>#filter:currency-{$invoice_total}#</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
				</tr>
			</tfoot>
			{/if}
		</table>    
    {/capture}

    {sC_template_printBlock 
    	title=$smarty.const.__PAGAMENTO_DASHBOARD_LIST 
    	data=$smarty.capture.t_payments_list_code 
    	contentclass = "no_padding"
    }
{/if}