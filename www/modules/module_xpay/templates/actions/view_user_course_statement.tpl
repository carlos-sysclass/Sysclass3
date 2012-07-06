{if isset($T_XPAY_STATEMENT)}
	{capture name="t_xpay_view_statement"}
		{include file="`$T_XPAY_BASEDIR`templates/includes/user.course.options.tpl"}
		
		
		{foreach item="invoice" key="invoice_index" from=$T_XPAY_STATEMENT.invoices}

		{/foreach}
		
		
		{include
			file="`$T_XPAY_BASEDIR`templates/includes/print.negociation.summary.tpl"
			T_XPAY_STATEMENT=$T_XPAY_STATEMENT
		}
		<table class="style1">
			<thead>
				<tr>
					<th style="text-align: center;">ID</th>
					<th style="text-align: center;">Parcela</th>
					<th style="text-align: center;">Vencimento</th>
					<th style="text-align: center;">Valor</th>
					<th style="text-align: center;">Acréscimos (+) / Descontos (-)</th>
					<th style="text-align: center;">Pago</th>
					<th style="text-align: center;">Saldo Devedor</th>
					<th style="text-align: center;">{$smarty.const.__OPTIONS}</th>
				</tr>
			</thead>
			<tbody>
				{foreach item="invoice" key="invoice_index" from=$T_XPAY_STATEMENT.invoices}
					<tr class="{if ($invoice.valor+$invoice.total_reajuste) <= $invoice.paid}xpay-paid{/if}{if $invoice.locked}locked{/if}">
					 	<td align="center">{$invoice.invoice_id}</td>
					 	<td align="center">{$invoice.invoice_index}</td>
					 	<td align="center">
					 		{if $invoice.data_vencimento}
					 			#filter:date-{$invoice.data_vencimento}#
					 		{else}
					 			n/a
					 		{/if}
					 	</td>
					 	<td align="center">
					 	#filter:currency:{$invoice.valor}#</td>
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
					 	<td align="center">#filter:currency:{$invoice.valor+$invoice.total_reajuste-$invoice.paid}#</td>
					 	<td align="center">
					 		<div>
					 		{if $invoice.full_price > $invoice.paid}
					 		<!-- 
								<a class="form-icon" href="{$T_XPAY_BASEURL}&action=do_payment&negociation_id={$invoice.negociation_id}&invoice_index={$invoice.invoice_index}&popup=1" onclick = "eF_js_showDivPopup('{$smarty.const.__XPAY_PRINT_INVOICE}', 2);" target = "POPUP_FRAME">
									<img src="images/others/transparent.gif" class="sprite16 sprite16-arrow_right">
								</a>
							 -->
								<a class="form-icon" href="{$T_XPAY_BASEURL}&action=do_payment&negociation_id={$invoice.negociation_id}&invoice_index={$invoice.invoice_index}">
									<img src="images/others/transparent.gif" class="sprite16 sprite16-arrow_right">
								</a>
								
							{/if}
							</div>
					 	
					 	

					 	</td>
					 	
					</tr>
				{foreachelse}
					<tr>
					 	<td colspan="8" align="center">{$smarty.const.__XPAY_NO_INVOICES_FOUND}</td>
					 	
					</tr>
				{/foreach}
			</tbody>
			{if $T_XPAY_STATEMENT.invoices|@count > 0}
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th style="text-align: center;">{$T_XPAY_STATEMENT_TOTALS.invoices_count}</th>
						<th style="text-align: center;">&nbsp;</th>
						<th style="text-align: center;">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.valor}#</th>
						<th style="text-align: center;">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.total_reajuste}#</th>
						<th style="text-align: center;" class="xpay-paid">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.paid}#</th>
						<th style="text-align: center;">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.valor+$T_XPAY_STATEMENT_TOTALS.total_reajuste-$T_XPAY_STATEMENT_TOTALS.paid}#</th>
						<th style="text-align: center;">&nbsp;</th>
					</tr>
				</tfoot>
			{/if}
		</table>
	{/capture}
	{if $T_XPAY_IS_ADMIN}
		{eF_template_printBlock
			title 			= $smarty.const.__XPAY_VIEW_USER_COURSE_STATEMENT
			data			= $smarty.capture.t_xpay_view_statement
		}
	{else}
		{eF_template_printBlock
			title 			= $smarty.const.__XPAY_VIEW_MY_COURSE_STATEMENT
			data			= $smarty.capture.t_xpay_view_statement
		}
	{/if}
{/if}