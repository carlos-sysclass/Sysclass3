{if $T_XPAY_STATEMENT}
	{capture name="t_xpay_do_payment"}
		{$T_XPAY_METHOD_FORM.javascript}
		<form {$T_XPAY_METHOD_FORM.attributes}>
			{$T_XPAY_METHOD_FORM.hidden}
			
			{include file="`$T_XPAY_BASEDIR`templates/includes/user.course.options.tpl"}
			
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
					<!--
					<tr>
						<th colspan="7">Curso: <strong>{$T_XPAY_STATEMENT.course}</strong></th>
					</tr>
					--->
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
					 	<td align="center">#filter:currency:{$invoice.total_reajuste}#
						 	{if $invoice.applied_rules|@count > 0}
						 		<a class="applied_rules_link" href="javascript: void(0);">?</a>
					 			<div class="applied_rules" id="applied_rule_{$invoice_index}"> 
								 	<ul>
									 	{foreach name="rule_it" item="applied_rule" from=$invoice.applied_rules}
									 		<li>
									 			<div class="rule_description">{$applied_rule.description}</div>
									 			<div class="rule_value">
									 			{if $applied_rule.count > 1}{$applied_rule.count}{$applied_rule.repeat_acronym} x {/if}
									 				#filter:currency:{$applied_rule.diff}#
									 			{if $applied_rule.count > 1} = #filter:currency:{$applied_rule.output-$applied_rule.input}#{/if}
									 			</div>
									 		</li>
									 	{/foreach}
								 	</ul>
							 	</div>
						 	{/if}
					 	</td>
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
			{capture name="t_xpay_methods"}
			<div>
				{foreach key="pay_module_key" item="pay_module" from=$T_XPAY_METHODS}
					<div class="form-field clear" style="float: left; margin-top:3px;" >
						{if $pay_module.title}
							<label class="clear" for="textfield">{$pay_module.title}</label>
						{/if}
						{foreach key="pay_index" item="pay_method" from=$pay_module.options}
							{assign var = "input_name"  value = $pay_module_key:$pay_index }
							{$T_XPAY_METHOD_FORM.pay_methods[$input_name].html}
						{/foreach}
					</div>
				{/foreach}
				
				<div style="float: left;">
					<button class="form-button icon-save" type="submit">
						<img width="29" height="29" src="images/transp.png">
						<span>{$smarty.const.__XPAY_SELECT}</span>
					</button>
				</div>					
			</div>
			{/capture}
						
			{eF_template_printBlock
				title 			= $smarty.const.__XPAY_PAYMENT_METHOD
				data			= $smarty.capture.t_xpay_methods
			}
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
		sub_title		= $smarty.const.__XPAY_DO_PAYMENT_INSTRUCTIONS
		data			= $smarty.capture.t_xpay_do_payment
	}
{/if}