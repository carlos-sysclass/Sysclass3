{if $T_XPAY_STATEMENT}
	
	{capture name="t_xpay_simulate_statement"}
		{include file="`$T_XPAY_BASEDIR`templates/includes/user.course.options.tpl"}
		
		{include
			file="`$T_XPAY_BASEDIR`templates/includes/print.negociation.summary.tpl"
			T_XPAY_STATEMENT=$T_XPAY_STATEMENT
		}
	
		
		{* CREATE TABLE WITH EDITABLE INVOICES *}
		
		{$T_XPAY_INVOICE_PARAMS_FORM.javascript}
		<div class="xpay-invoice-params-selection form-container" id="xpay-invoice-params-selection" title="{$smarty.const.__XPAY_CREATE_NEW_NEGOCIATION}">
			<form {$T_XPAY_INVOICE_PARAMS_FORM.attributes}>
				{$T_XPAY_INVOICE_PARAMS_FORM.hidden|@implode}
				<div align="left">
					<label>{$T_XPAY_INVOICE_PARAMS_FORM.saldo_total.label}:</label>
					<span>{$T_XPAY_INVOICE_PARAMS_FORM.saldo_total.html}</span>
				</div>
				<div align="left">
					<label>{$T_XPAY_INVOICE_PARAMS_FORM.taxa_matricula.label}:</label>
					<span>{$T_XPAY_INVOICE_PARAMS_FORM.taxa_matricula.html}</span>
				</div>
				<div>
					<label>{$T_XPAY_INVOICE_PARAMS_FORM.vencimento_1_parcela.label}:</label>
					<span>{$T_XPAY_INVOICE_PARAMS_FORM.vencimento_1_parcela.html}</span>
				</div>
	
				<div>
					<label>{$T_XPAY_INVOICE_PARAMS_FORM.dia_vencimento.label}:</label>
					<span>{$T_XPAY_INVOICE_PARAMS_FORM.dia_vencimento.html}</span>
				</div>
				<div align="left">
					<label>{$T_XPAY_INVOICE_PARAMS_FORM.total_parcelas.label}:</label>
					<span>{$T_XPAY_INVOICE_PARAMS_FORM.total_parcelas.html}</span>
				</div>
			 </form>
		</div>
		
		<table class="style1">
			<thead>
				<tr>
					<th style="text-align: center;">Descrição</th>
	<!-- 				<th style="text-align: center;">Parcela</th>  -->
					<th style="text-align: center;">Vencimento</th>
					<th style="text-align: center;">Valor</th>
					<th style="text-align: center;">Acréscimos (+) / Descontos (-)</th>
		 			<th style="text-align: center;">Pago</th>	
					<th style="text-align: center;">Total</th>
					<th style="text-align: center;">{$smarty.const.__OPTIONS}</th>
				</tr>
			</thead>
			<tbody>
				{foreach item="invoice" from=$T_XPAY_STATEMENT.invoices}
					<tr class="{if ($invoice.valor+$invoice.total_reajuste) <= $invoice.paid}xpay-paid{/if} {if $invoice.locked}locked{/if}">
						<td>{$invoice.description}</td>
					 	<td align="center">#filter:date-{$invoice.data_vencimento}#</td>
					 	<td align="center">#filter:currency:{$invoice.valor}#</td>
					 	<td align="center">#filter:currency:{$invoice.total_reajuste}#
					 		{if $invoice.applied_rules|@count > 0}
						 		<a class="applied_rules_link" href="javascript: void(0);">?</a>
								<div class="hover_tooltip applied_rules" id="applied_rule_{$invoice_index}"> 
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
				 				{if $invoice.locked == 0 && ($invoice.valor+$invoice.total_reajuste) > $invoice.paid}
				 				<!-- 
							 		<a href="{$T_XPAY_BASEURL}&action=edit_user_course_statement&xuser_id={$statement.user_id}&xcourse_id={$statement.course_id}&negociation_index={$statement.negociation_index}" class="form-icon">
										<img src="images/others/transparent.gif" class="sprite16 sprite16-edit">
									</a>
								 -->
								{/if}
							</div>
					 	</td>
					</tr>
				{foreachelse}
					<tr>
					 	<td colspan="7" align="center">{$smarty.const.__XPAY_NO_INVOICES_FOUND}</td>
					</tr>
				{/foreach}
			</tbody>
			
			{if $T_XPAY_STATEMENT.sugested_invoices|@count > 0}
				<tfoot>
					<tr>
						<th>&nbsp;</th>
						<th style="text-align: center;">&nbsp;</th>
						<th style="text-align: center;">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.valor}#</th>
						<th style="text-align: center;">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.total_reajuste}#</th>
						<th style="text-align: center;">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.paid}#</th>
						<th style="text-align: center;">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.balance}#</th>
						<th style="text-align: center;">&nbsp;</th>
					</tr>
				</tfoot>
			{/if}
		</table>
	
		
		<div class="clear"></div>
		<div class="grid_24" style="margin: 20px 0;" align="right">
			<button class="form-button icon-add openInvoiceNegociationDialog" type="button">
				<img width="29" height="29" src="images/transp.png">
				<span>{$smarty.const.__XPAY_CREATE_NEW_NEGOCIATION}</span>
			</button>
			{if $T_XPAY_NEGOCIATION_IS_SUGESTED}
			<button class="form-button icon-save saveNegociation" type="button">
				<img width="29" height="29" src="images/transp.png">
				<span>{$smarty.const.__XPAY_SAVE_NEGOCIATION}</span>
			</button>
			{/if}
		</div>	
		
	{/capture}
	{eF_template_printBlock
		title 			= $smarty.const.__XPAY_SIMULATE_NEGOCIATION
		data			= $smarty.capture.t_xpay_simulate_statement
	}
{/if}