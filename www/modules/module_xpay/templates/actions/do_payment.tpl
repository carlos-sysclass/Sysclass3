{if $T_XPAY_STATEMENT}
	{capture name="t_xpay_do_payment"}
		{include file="`$T_XPAY_BASEDIR`templates/includes/user.course.options.tpl"}
		
		{$T_XPAY_METHOD_FORM.javascript}
		<form {$T_XPAY_METHOD_FORM.attributes}>
			{$T_XPAY_METHOD_FORM.hidden}
			<table class="style1">
				<thead>
					<tr>
						<th style="text-align: center;">Selecionar</th>
						<th style="text-align: center;">Identificação</th>
						<th style="text-align: center;">Vencimento</th>
						<th style="text-align: center;">Valor</th>
						<th style="text-align: center;">Acréscimos (+) / Descontos (-)</th>
						<th style="text-align: center;">Pago</th>
						<th style="text-align: center;">Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th colspan="6">Curso: <strong>{$T_XPAY_STATEMENT.course}</strong></th>
					</tr>
					{foreach item="invoice" from=$T_XPAY_STATEMENT.invoices}
					<tr class="{if ($invoice.valor+$invoice.total_reajuste) <= $invoice.paid}xpay-paid{/if}{if $invoice.locked}locked{/if}">
						<td align="center">
							{$T_XPAY_METHOD_FORM.invoice_indexes[$invoice.invoice_index].html}
						</td>
					 	<td align="center">{$invoice.invoice_id}</td>
					 	<td align="center">
						 	{if $invoice.data_vencimento}
					 			#filter:date-{$invoice.data_vencimento}#
					 		{else}
					 			n/a
					 		{/if}
					 	</td>
					 	<td align="center">#filter:currency-{$invoice.valor}#</td>
					 	<td align="center">#filter:currency:{$invoice.total_reajuste}#</td>
					 	<td align="center">#filter:currency:{$invoice.paid}#</td>
					 	<td align="center">#filter:currency-{$invoice.valor+$invoice.total_reajuste}#</td>
					</tr>
					{/foreach}
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2">Total:</th>
						<th>&nbsp;</th>
						<th style="text-align: center;">#filter:currency-{$T_XPAY_STATEMENT_TOTALS.valor}#</th>
						<th style="text-align: center;">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.total_reajuste}#</th>
						<th style="text-align: center;" class="xpay-paid">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.paid}#</th>
						<th style="text-align: center;">#filter:currency-{$T_XPAY_STATEMENT_TOTALS.valor+$T_XPAY_STATEMENT_TOTALS.total_reajuste}#</th>
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
	<!-- 
			<div class="form-field clear buttons">
				<button class="" type="submit" name="{$T_XPAY_METHOD_FORM.xpay_submit.name}" value="{$T_XPAY_METHOD_FORM.xpay_submit.value}">
					<img 
						src = "images/others/transparent.png"
						class="imgs_cont sprite16 sprite16-go_into"
						border = "0"
					/>							
					<span>{$T_XPAY_METHOD_FORM.xpay_submit.value}</span>
				</button>
			</div>
	-->
		</form>
	{/capture}
	
	{eF_template_printBlock
		title 			= $smarty.const.__XPAY_DO_PAYMENT
		data			= $smarty.capture.t_xpay_do_payment
	}
{/if}