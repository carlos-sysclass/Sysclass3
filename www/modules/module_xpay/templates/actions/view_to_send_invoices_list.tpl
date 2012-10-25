{capture name="t_xpay_view_send_list"}
	{include file="$T_XPAY_BASEDIR/templates/includes/options.links.tpl"}
	
	<table id="xpay-view-to-send-invoices-list-table" class="style1">
		<thead>
			<tr>
				<th style="text-align: center;">Vencimento</th>
				<th style="text-align: center;">Parcela</th>
				<th style="text-align: center;">Usuário</th>
				<th style="text-align: center;">Curso</th>
				<th style="text-align: center;">Valor</th>
				<th style="text-align: center;">Acréscimos (+) / Descontos (-)</th>
				<th style="text-align: center;">Pago</th>
				<th style="text-align: center;">Saldo Devedor</th>
				<th style="text-align: center;">{$smarty.const.__OPTIONS}</th>
			</tr>
		</thead>
		<tbody>
			{foreach item="invoice" from=$T_XPAY_LIST}
				<tr class="{if $invoice.locked}locked{/if}">
					<td align="center">#filter:date-{$invoice.data_vencimento}#</td>
					<td align="center">{$invoice.invoice_index}/{$invoice.invoice_count}</td>
					<td align="center">
						<a href="{$T_XPAY_BASEURL}&action=view_user_course_statement&xuser_id={$invoice.user_id}&xcourse_id={$invoice.course_id}">
							{$invoice.username}
						</a>
					</td>
					<td align="center">{$invoice.course}</td>
				 	<td align="center">#filter:currency:{$invoice.valor}#</td>
				 	<td align="center">#filter:currency:{$invoice.total_reajuste}#</td>
				 	<td align="center">#filter:currency:{$invoice.paid}#</td>
				 	<td align="center">#filter:currency:{$invoice.valor+$invoice.total_reajuste-$invoice.paid}#</td>
				 	<td align="center">
				 		<div>
				 		{if $invoice.full_price > $invoice.paid}
				 			{if $invoice.sending}
					 			<input type="checkbox" name="invoices_to_send" value="{$invoice.negociation_id}:{$invoice.invoice_index}" onclick="xPayUpdateSentInvoiceStatus({$invoice.negociation_id}, {$invoice.invoice_index}, this);" checked="checked" />
					 		{elseif $invoice.sent_count > 0}
								<a class="form-icon" href="javascript: xPayMailInvoicesAdviseAction('{$invoice.negociation_id}', '{$invoice.invoice_index}');" title="Reenviar E-mail!"><img src="images/others/transparent.gif" class="sprite16 sprite16-mail" border="0"></a>
					 		{else}
					 			<input type="checkbox" name="invoices_to_send" value="{$invoice.negociation_id}:{$invoice.invoice_index}" onclick="xPayUpdateSentInvoiceStatus({$invoice.negociation_id}, {$invoice.invoice_index}, this);" />
					 		{/if}
						{/if}
						</div>
				 	</td>
				</tr>
			{/foreach}
		</tbody>
		<tfoot>
			<tr>
				<th style="text-align: center;">Vencimento</th>
				<th style="text-align: center;">
					<select name="filter_column_1" class="select_filter">
						<option value="">Parcelas</option>
						<option value="1+/">Somente Matrículas</option>
						<option value="([2-9]|1+[0-9]+)/">Somente Parcelas</option>
					</select>
				</th>
				<th style="text-align: center;">Usuário</th>
				<th style="text-align: center;">Curso</th>
				<th style="text-align: center;">Valor</th>
				<th style="text-align: center;">Acréscimos (+) / Descontos (-)</th>
				<th style="text-align: center;">Pago</th>
				<th style="text-align: center;">Saldo Devedor</th>
				<th style="text-align: center;">{$smarty.const.__OPTIONS}</th>
			</tr>
		</tfoot>
	</table>
{/capture}

{eF_template_printBlock
	title      = $smarty.const.__XPAY_VIEW_TO_SEND_INVOICES_LIST
	options    = $T_VIEW_TO_SEND_INVOICES_LIST_OPTIONS
	data       = $smarty.capture.t_xpay_view_send_list
}
