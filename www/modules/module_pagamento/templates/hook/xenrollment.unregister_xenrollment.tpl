<!-- 
<div class="block flat_area box border" style="margin-top: 20px;">
	<h2>Se cancelado</h2>
	<div class="invoice-summary">
		<div class="grid_8">
			<label>Débito Total:</label>
			<span class="invoice-summary-item">#filter:currency-{$T_PAGAMENTO_INVOICE_SUMMARY.total_to_receive}#</span>
		</div>
		<div class="grid_8">
			<label>Se cancelado:</label>
			<span class="invoice-summary-item">#filter:currency-{$T_PAGAMENTO_INVOICE_CANCELFEE}#</span>
		</div>
	</div>
</div>
-->
<div class="grid_16">
	<div class="alert alert_orange">
		<img width="24" height="24" src="{$smarty.const.G_CURRENTTHEMEURL}/images/icons/small/white/alert_2.png">
		{$smarty.const.__PAGAMENTO_CANCELATION_INSTRUCTIONS}
	</div>	
</div>
<div class="clear"></div>
<div class="grid_16 box border">
	<div style="padding: 15px;">
		{include 
			file="$T_PAGAMENTO_BASEDIR/templates/includes/pagamento.invoices.summary.tpl"
			T_PAYMENT_INVOICES_SUMMARY=$T_PAGAMENTO_BEFORE_INVOICE_SUMMARY
		}
	</div>
</div>


<div class="clear"></div>

<div class="grid_8">
	<h3>{$smarty.const.__PAGAMENTO_BEFORE_CANCELATION}</h3>
{include 
	file="$T_PAGAMENTO_BASEDIR/templates/includes/pagamento.invoices.list.tpl"
	T_PAYMENT_INVOICES=$T_PAGAMENTO_BEFORE_INVOICES
	T_INVOICE_TITLE=""
}
</div>

<div class="grid_8">
	<h3>{$smarty.const.__PAGAMENTO_AFTER_CANCELATION}</h3>
{include 
	file="$T_PAGAMENTO_BASEDIR/templates/includes/pagamento.invoices.list.tpl"
	T_PAYMENT_INVOICES=$T_PAGAMENTO_AFTER_INVOICES
	T_INVOICE_TITLE=""
}
</div>