<table class="style1 invoice-summary">
	<thead>
		<tr>
			<th colspan="11">{$T_XPAY_STATEMENT.username} &raquo; 
				{$T_XPAY_STATEMENT.module_printname}
				<a class="base_price_details_link" href="javascript: void(0);">?</a>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Preço Base
				<a class="base_price_details_link" href="javascript: void(0);">?</a>
				<div class="hover_tooltip base_price_details"> 
				 	<ul>
					 	{foreach name="rule_it" item="detail" from=$T_XPAY_STATEMENT.modules}
					 		<li>
					 			<div class="rule_description">{$detail.module}</div>
					 			<div class="rule_value">#filter:currency:{$detail.base_price}#</div>
							</li>
					 	{/foreach}
					 	<li>
					 		<hr /> 
					 			<div class="rule_description">Total</div>
					 			<div class="rule_value">#filter:currency:{$T_XPAY_STATEMENT.base_price}#</div>
							</li>
				 	</ul>
				</div>
			</td>
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