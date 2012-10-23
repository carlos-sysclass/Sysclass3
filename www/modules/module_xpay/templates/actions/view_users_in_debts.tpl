{capture name="t_xpay_view_users_in_debt"}
	{include file="$T_XPAY_BASEDIR/templates/includes/options.links.tpl"}
	
	<table id="xpay-view_users-in-debts-table" class="style1">
		<thead>
			<tr>
				<th style="text-align: center;">Débito desde</th>
				<th style="text-align: center;">Usuário</th>
				<th style="text-align: center;">Curso</th>
				<th style="text-align: center;">Valor</th>
				<th style="text-align: center;">Pago</th>
				<th style="text-align: center;">Saldo Devedor</th>
			</tr>
		</thead>
		<tbody>
			{foreach item="debt" from=$T_XPAY_LIST}
				<tr class="{if $invoice.locked}locked{/if}">
					<td align="center">#filter:date-{$debt.data_debito_inicial}#</td>
					<td>
						<a href="{$T_XPAY_BASEURL}&action=view_user_course_statement&xuser_id={$debt.user_id}&xcourse_id={$debt.course_id}">
							{$debt.username}
						</a>
					</td>
					<td>{$debt.course}</td>
					
				 	<!-- <td align="center">{$invoice.invoice_id}</td>  -->
				 	<td align="center">#filter:currency:{$debt.valor_total}#</td>
				 	<td align="center">#filter:currency:{$debt.considered_paid}#</td>
				 	<td align="center">#filter:currency:{$debt.total_debito}#</td>
				</tr>
			{/foreach}
		</tbody>
		<tfoot>
			<tr>
				<th style="text-align: center;">Débito desde</th>
				<th style="text-align: center;">Usuário</th>
				<th style="text-align: center;">Curso</th>
				<th style="text-align: center;">Valor</th>
				<th style="text-align: center;">Pago</th>
				<th style="text-align: center;">Saldo Devedor</th>
			</tr>
		</tfoot>
	</table>
{/capture}

{eF_template_printBlock
	title      = $smarty.const.__XPAY_VIEW_USERS_IN_DEBTS
	data       = $smarty.capture.t_xpay_view_users_in_debt
}
