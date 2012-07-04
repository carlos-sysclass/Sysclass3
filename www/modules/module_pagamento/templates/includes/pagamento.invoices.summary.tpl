<h3>{$smarty.const.__PAGAMENTO_INVOICES_SUMMARY}</h3>
<div class="invoice-summary">
	<div class="grid_2 prefix_1">
		<label>Valor Pago:</label>
		<span class="invoice-summary-item">#filter:currency-{$T_PAYMENT_INVOICES_SUMMARY.total_paid}#</span>
	</div>
	<div class="grid_1">
		<span class="invoice-summary-sign">+</span>
	</div>
	<div class="grid_2">
		<label>Valor Emitido:</label>
		<span class="invoice-summary-item">#filter:currency-{$T_PAYMENT_INVOICES_SUMMARY.total_sended}#</span>
	</div>
	<div class="grid_1">
		<span class="invoice-summary-sign">+</span>
	</div>

	<div class="grid_2">
		<label>Valor em Atraso:</label>
		<span class="invoice-summary-item">#filter:currency-{$T_PAYMENT_INVOICES_SUMMARY.total_delay}#</span>
	</div>
	<div class="grid_1">
		<span class="invoice-summary-sign">+</span>
	</div>

	<div class="grid_2">
		<label>Débito a Receber:</label>
		<span class="invoice-summary-item">#filter:currency-{$T_PAYMENT_INVOICES_SUMMARY.total_to_receive}#</span>
	</div>
	<div class="grid_1">
		<span class="invoice-summary-sign">=</span>
	</div>
	<div class="grid_2">
		<label>Valor Total:</label>
		<span class="invoice-summary-item">#filter:currency-{$T_PAYMENT_INVOICES_SUMMARY.total_debt}#</span>
	</div>
</div>