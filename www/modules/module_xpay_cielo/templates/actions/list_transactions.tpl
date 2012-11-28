{capture name="t_xpay_cielo_last_transactions"}
<table id="xpay-cielo-last-transactions-table" class="style1">
	<thead>
		<tr>
			<th style="text-align: center;">TID</th>
			<th style="text-align: center;">Data</th>
			<th style="text-align: center;">Usuário</th>
			<th style="text-align: center;">Bandeira</th>
			<th style="text-align: center;">Forma</th>
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;">Status</th>
		</tr>
	</thead>
	<tbody>
		{foreach item="trans" from=$T_XPAY_CIELO_TRANSACTIONS}
			<tr>
				<td>{$trans.tid}</td>
				<td align="center">#filter:date-{$trans.data}#</td>
				<td>{$trans.login}</td>
				<td align="center"><img src="{$T_XPAY_CIELO_BASELINK}images/{$trans.bandeira}.png" /></td>
				<td align="center">{$trans.forma_pagamento}</td>
				<td align="center">#filter:currency:{$trans.valor}#</td>
				<td align="center">{$trans.status}</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<th style="text-align: center;">TID</th>
			<th style="text-align: center;">Data</th>
			<th style="text-align: center;">Usuário</th>
			<th style="text-align: center;">Bandeira</th>
			<th style="text-align: center;">Forma</th>
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;">
				<select name="filter_column_6" class="select_filter">
					<option value="">Status</option>
					{foreach item="status" from=$T_XPAY_CIELO_STATUSES}
						<option value="{$trans.nome}">{$status.nome}</option>
					{/foreach}
				</select>
			</th>
		</tr>
	</tfoot>
</table>
{/capture}
	
{eF_template_printBlock 
	title=$smarty.const.__XPAY_CIELO_LAST_TRANSACTIONS
	data=$smarty.capture.t_xpay_cielo_last_transactions
}
