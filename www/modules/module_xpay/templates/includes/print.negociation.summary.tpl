<table class="style1 invoice-summary">
	<thead>
		<tr>
			<th colspan="11">{$T_XPAY_STATEMENT.username} &raquo; {$T_XPAY_STATEMENT.module}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Preço Base</td>
			<td rowspan="2" class="invoice-summary-sign">+</td>
			<td>Acréscimos</td>
			<td rowspan="2" class="invoice-summary-sign">-</td>
			<td>Descontos</td>
			<td rowspan="2" class="invoice-summary-sign">=</td>
			<td>Valor Final</td>
			<td rowspan="2" class="invoice-summary-sign">-</td>
			<td>Valor Pago</td>
			<td rowspan="2" class="invoice-summary-sign">=</td>
			<td>Saldo</td>
		</tr>
		<tr>
			<td>#filter:currency:{$T_XPAY_STATEMENT.base_price}#</td>
			<td>#filter:currency:{$T_XPAY_STATEMENT.acrescimo}#</td>
			<td>#filter:currency:{$T_XPAY_STATEMENT.desconto}#</td>
			<td>#filter:currency:{$T_XPAY_STATEMENT.full_price}#</td>
			<td>#filter:currency:{$T_XPAY_STATEMENT.paid}#</td>
			<td>#filter:currency:{$T_XPAY_STATEMENT.full_price-$T_XPAY_STATEMENT.paid}#</td>
		</tr>
	</tbody>
</table>	