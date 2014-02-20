<table class="style1">
	<thead>
		<tr>
			<th style="text-align: center;">Débito desde</th>
			<th style="text-align: center;">Usuário</th>
			<th style="text-align: center;">Saldo Devedor</th>
		</tr>
	</thead>
	<tbody>
		{foreach item="debt" from=$T_XPAY_DEBTS_LIST}
			<tr class="{if $invoice.locked}locked{/if}">
				<td align="center">#filter:date-{$debt.data_debito_inicial}#</td>
				<td>
					<a href="{$T_XPAY_BASEURL}&action=view_user_course_statement&negociation_id={$debt.negociation_id}">
						{$debt.username}
					</a>
				</td>
			 	<td align="center">#filter:currency:{$debt.total_debito}#</td>
			</tr>
		{/foreach}
	</tbody>
</table>
	