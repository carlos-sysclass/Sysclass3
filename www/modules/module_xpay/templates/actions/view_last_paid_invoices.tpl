{capture name="t_xpay_last_payments_widget"}
	{include file="$T_XPAY_BASEDIR/templates/includes/options.links.tpl"}

<table id="xpay-last-paid-invoices-table" class="style1">
	<thead>
		<tr>
			<th style="text-align: center;">Polo</th>
			<th style="text-align: center;">Curso / Turma</th>
			<!-- <th style="text-align: center;">Nº Doc.</th>  -->
			<th style="text-align: center;">Usuário</th>
			<th style="text-align: center;">parcelas</th>
			<th style="text-align: center;">Vencimento</th>
			<th style="text-align: center;">Pagamento</th>
			<!-- <th style="text-align: center;">Método</th>  -->
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;">Desc./Acrés.</th>
			<th style="text-align: center;">Pago</th>
		</tr>
	</thead>
	<tbody>
		{foreach item="invoice" from=$T_XPAY_LAST_PAYMENTS}
			<tr class="{if $invoice.locked}locked{/if}">
				<td>{$invoice.polo}</td>
				<td>{$invoice.course_name}&nbsp;&raquo;&nbsp;{$invoice.class_name|eF_truncate:20}</td>
				<!-- <td>{$invoice.invoice_id}</td>  -->
				<td><a href="{$T_XPAY_BASEURL}&action=view_user_course_statement&xuser_id={$invoice.user_id}&xcourse_id={$invoice.course_id}">{$invoice.login}</a></td>
				<td align="center">
					{$invoice.invoice_index+1}/{$invoice.total_parcelas}
				</td>
				<td align="center">#filter:date-{$invoice.data_vencimento}#</td>
				<td align="center">#filter:date-{$invoice.data_pagamento}#</td>
				<!-- <td align="center">{$invoice.method_id}</td>  -->
				<td align="center">#filter:currency:{$invoice.valor}#</td>
				<td align="center">#filter:currency:{$invoice.desconto*-1}#</td>
				<td align="center">#filter:currency:{$invoice.paid}#</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<th style="text-align: center;">Polo</th>
			<th style="text-align: center;">Curso / Turma</th>
			<!-- <th style="text-align: center;">Nº Doc.</th> -->
			<th style="text-align: center;">Usuário</th>
			<th style="text-align: center;">
					<select name="filter_column_3" class="select_filter">
						<option value="">Parcelas</option>
						<option value="^(1)/">Somente Matrículas</option>
						<option value="([2-9]|1+[0-9]+)/">Somente Parcelas</option>
					</select>
				</th>
			<th style="text-align: center;">Vencimento</th>
			<th style="text-align: center;">Pagamento</th>
			<!-- <th style="text-align: center;">Método</th>  -->
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;">Desc./Acrés.</th>
			<th style="text-align: center;">Pago</th>
		</tr>
		<tr>
				<th colspan="6" style="text-align: right;">Total da Página</th>
				<th style="text-align: center;">Valor</th>
				<th style="text-align: center;">Pago</th>
				<th style="text-align: center;">Saldo Devedor</th>
			</tr>
			<tr>
				<th colspan="6" style="text-align: right;">Grande Total</th>
				<th style="text-align: center;">Valor</th>
				<th style="text-align: center;">Pago</th>
				<th style="text-align: center;">Saldo Devedor</th>
			</tr>
	</tfoot>
</table>
{/capture}
	
{eF_template_printBlock 
	title=$smarty.const.__XPAY_LAST_PAYMENTS
	data=$smarty.capture.t_xpay_last_payments_widget
}


