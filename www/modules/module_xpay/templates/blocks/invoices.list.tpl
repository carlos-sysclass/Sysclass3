<table class="style1">
	<thead>
		<tr>
			<th style="text-align: center;">Identificação</th>
			<th style="text-align: center;">Vencimento</th>
			<th style="text-align: center;">Valor</th>
		</tr>
	</thead>
	<tbody>
		{foreach item="invoice" from=$T_XPAY_STATEMENT.invoices}
			{if ($invoice.valor+$invoice.total_reajuste) > $invoice.paid}
				<tr class="{if $invoice.locked}locked{/if}{if $invoice.overdue}overdue{/if}">
					<td align="center">
						<a href="{$T_XPAY_BASEURL}&action=do_payment&negociation_id={$invoice.negociation_id}&invoice_index={$invoice.invoice_index}">
						{$invoice.invoice_id}
						</a>
					</td>
				 	<td align="center">
					 	{if $invoice.data_vencimento}
							<a href="{$T_XPAY_BASEURL}&action=do_payment&negociation_id={$invoice.negociation_id}&invoice_index={$invoice.invoice_index}">
				 				#filter:date-{$invoice.data_vencimento}#
							</a>
				 		{else}
				 			n/a
				 		{/if}
				 	</td>
				 	<td align="center">
						<a href="{$T_XPAY_BASEURL}&action=do_payment&negociation_id={$invoice.negociation_id}&invoice_index={$invoice.invoice_index}">
							#filter:currency-{$invoice.valor+$invoice.total_reajuste}#
						</a>
					</td>
				</tr>
			{/if}
		{/foreach}
	</tbody>
</table>
