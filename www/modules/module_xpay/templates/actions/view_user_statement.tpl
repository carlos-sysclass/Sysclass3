{capture name="t_xpay_view_statement"}
	<table class="style1">
		<thead>
			<tr>
				<th style="text-align: left;">Curso</th>
				<th style="text-align: center;">Modalidade</th>
				<th style="text-align: center;">Preço Base</th>
				<th style="text-align: center;">Pago</th>
				<th style="text-align: center;">Saldo</th>
				<th style="text-align: center;">{$smarty.const.__OPTIONS}</th>
			</tr>
		</thead>
		<tbody>
			{foreach item="statement" from=$T_XPAY_STATEMENT}
				<tr class="{if $invoice.full_price <= $invoice.paid}xpay-paid{/if}{if $invoice.locked}locked{/if}">
				 	<td>{$statement.module}</td>
				 	<td>{$statement.modality}</td>
				 	<td align="center">#filter:currency-{$statement.base_price}#</td>
				 	<td align="center">#filter:currency:{$statement.paid}#</td>
				 	<td align="center">#filter:currency-{$statement.balance}#</td>
				 	<td align="center">
				 		<div>
					 		<a href="{$T_XPAY_BASEURL}&action=view_user_course_statement&xuser_id={$statement.user_id}&x{$statement.type}_id={$statement.module_id}&negociation_index={$statement.negociation_index}" class="form-icon">
								<img src="images/others/transparent.gif" class="sprite16 sprite16-unit">
							</a>
							<!-- 
					 		<a href="{$T_XPAY_BASEURL}&action=edit_user_course_statement&xuser_id={$statement.user_id}&xcourse_id={$statement.course_id}&negociation_index={$statement.negociation_index}" class="form-icon">
								<img src="images/others/transparent.gif" class="sprite16 sprite16-edit">
							</a>
 							-->
						</div>
				 	</td>
				</tr>
			{/foreach}
		</tbody>
		<tfoot>
			<tr>
				<th colspan="2">Total:</th>
				<th style="text-align: center;">#filter:currency-{$T_XPAY_STATEMENT_TOTALS.base_price}#</th>
				<th style="text-align: center;" class="xpay-paid">#filter:currency:{$T_XPAY_STATEMENT_TOTALS.paid}#</th>
				<th style="text-align: center;">#filter:currency-{$T_XPAY_STATEMENT_TOTALS.balance}#</th>
				<th>&nbsp;</th>
			</tr>
		</tfoot>
	</table>
{/capture}

{if $T_XPAY_IS_ADMIN}
	{eF_template_printBlock
		title 			= $smarty.const.__XPAY_VIEW_USER_STATEMENT
		data			= $smarty.capture.t_xpay_view_statement
	}
{else}
	{eF_template_printBlock
		title 			= $smarty.const.__XPAY_VIEW_MY_STATEMENT
		data			= $smarty.capture.t_xpay_view_statement
	}
{/if}
