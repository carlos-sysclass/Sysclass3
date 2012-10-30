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
		
		<table id="xpay-edit-negociation-table" class="style1">
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
				 				{if $invoice.sugested == 0}
				 					{if $invoice.full_price > $invoice.paid}
										<a 
											class="form-icon" 
											href="{$T_XPAY_BASEURL}&action=edit_invoice&negociation_id={$invoice.negociation_id}&invoice_index={$invoice.invoice_index}&popup=1"
											onclick = "eF_js_showDivPopup('{$smarty.const.__XPAY_EDIT_INVOICE}', 0)" 
											target = "POPUP_FRAME"
										><img src="images/others/transparent.gif" class="sprite16 sprite16-edit"></a>
										<a 
											class="form-icon" 
											href="{$T_XPAY_BASEURL}&action=create_payment&negociation_id={$invoice.negociation_id}&invoice_index={$invoice.invoice_index}&popup=1"
											onclick = "eF_js_showDivPopup('{$smarty.const.__XPAY_CREATE_PAYMENT}', 0)" 
											target = "POPUP_FRAME"
										><img src="images/others/transparent.gif" class="sprite16 sprite16-do_pay"></a>
									{/if}
									<a class="form-icon" href="{$T_XPAY_BASEURL}&action=do_payment&negociation_id={$invoice.negociation_id}&invoice_index={$invoice.invoice_index}">
										<img src="images/others/transparent.gif" class="sprite16 sprite16-arrow_right">
									</a>
									
								{/if}
							</div>
					 	</td>
					</tr>
				{foreachelse}
					<tr>
					 	<td colspan="7" class="datatable-not-found" align="center">{$smarty.const.__XPAY_NO_INVOICES_FOUND}</td>
					</tr>
				{/foreach}
			</tbody>
			<tfoot>
				<tr>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th style="text-align: center;">&nbsp;</th>
					<th style="text-align: center;">&nbsp;</th>
					<th style="text-align: center;">&nbsp;</th>
					<th style="text-align: center;">&nbsp;</th>
					<th>&nbsp;</th>
				</tr>
			</tfoot>
		</table>
	
		
		<div class="clear"></div>
		<div class="grid_24" style="margin: 20px 0;" align="right">
			<button class="form-button icon-add openInvoiceNegociationDialog" type="button">
				<img width="29" height="29" src="images/transp.png">
				<span>{$smarty.const.__XPAY_CREATE_NEW_NEGOCIATION}</span>
			</button>
			{if $T_XPAY_NEGOCIATION_IS_SUGESTED}
			<button class="form-button icon-save" type="button" onclick="_sysclass('load', 'xpay').saveInvoices();">
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