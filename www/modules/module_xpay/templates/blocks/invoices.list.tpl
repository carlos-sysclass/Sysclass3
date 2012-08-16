{capture name="t_xpay_do_payment"}
	{*include file="`$T_XPAY_BASEDIR`templates/includes/user.course.options.tpl"*}
		
	{$T_XPAY_METHOD_FORM.javascript}
	<form {$T_XPAY_METHOD_FORM.attributes}>
		{$T_XPAY_METHOD_FORM.hidden}
		<table class="style1">
			<thead>
				<tr>
					<th style="text-align: center;">Selecionar</th>
					<th style="text-align: center;">Vencimento</th>
					<th style="text-align: center;">Valor</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th colspan="3">Curso: <strong>{$T_XPAY_STATEMENT.course}</strong></th>
				</tr>
					{foreach item="invoice" from=$T_XPAY_STATEMENT.invoices}
					{if ($invoice.valor+$invoice.total_reajuste) > $invoice.paid}
					<tr class="{if $invoice.locked}locked{/if}">
						<td align="center">
							{$T_XPAY_METHOD_FORM.invoice_indexes[$invoice.invoice_index].html}
						</td>
					 	<td align="center">
					 	
					 		{$invoice.valor+$invoice.total_reajuste}
					 		{$invoice.paid}
					 	
						 	{if $invoice.data_vencimento}
					 			#filter:date-{$invoice.data_vencimento}#
					 		{else}
					 			n/a
					 		{/if}
					 	</td>
					 	<td align="center">#filter:currency-{$invoice.valor+$invoice.total_reajuste}#</td>
					</tr>
					{/if}
					{/foreach}
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2">Total:</th>
						<th style="text-align: center;">#filter:currency-{$T_XPAY_STATEMENT_TOTALS.valor}#</th>
					</tr>
				</tfoot>
			</table>

			<div class="form-field clear">
				<label class="clear" for="textfield">{$smarty.const.__XPAY_PAYMENT_METHOD}<span class="required">*</span></label>
			</div>
			
			{foreach key="pay_module_key" item="pay_module" from=$T_XPAY_METHODS}
				<div class="form-field clear">
					{if $pay_module.title}
						<label class="clear" for="textfield">{$pay_module.title}</label>
					{/if}
					{foreach key="pay_index" item="pay_method" from=$pay_module.options}
						<!--  <input type="radio" value="" name="xpay_methods" class="xpay_methods"> -->
						{assign var = "input_name"  value = $pay_module_key:$pay_index }
						{$T_XPAY_METHOD_FORM.pay_methods[$input_name].html}
					{/foreach}
				</div>
			{/foreach}
		</form>
	{/capture}
{$smarty.capture.t_xpay_do_payment}