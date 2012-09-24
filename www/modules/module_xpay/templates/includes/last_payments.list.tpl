<table id="xpay-view-to-send-invoices-list-table" class="style1 {$T_XPAY_TABLE_CLASS}">
	<thead>
		<tr>
			<th style="text-align: center;">Aluno</th>
			<th style="text-align: center;">Data</th>
			<th style="text-align: center;">Parcela</th>
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;">{$smarty.const.__OPTIONS}</th>
		</tr>
	</thead>
	<tbody>
		{foreach item="invoice" from=$T_XPAY_LAST_PAYMENTS}
			<tr class="{if $invoice.locked}locked{/if}">
				<td><a href="{$T_XPAY_BASEURL}&action=view_user_course_statement&xuser_id={$invoice.user_id}&xcourse_id={$invoice.course_id}">{$invoice.name} {$invoice.surname}</a></td>
				<td align="center">#filter:date-{$invoice.data_pagamento}#</td>
				<td align="center">
					{if $invoice.invoice_index == 0}
						Matrícula
					{else}
						{$invoice.invoice_index}/{$invoice.total_parcelas}
					{/if}
				</td>
				<td align="center">#filter:currency:{$invoice.valor}#</td>
			 	<td align="center">
			 		<div>
					</div>
			 	</td>
			</tr>
		{/foreach}
	</tbody>
</table>