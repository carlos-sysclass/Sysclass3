{if $T_XPAY_NEGOCIATIONS}
	{capture name="t_xpay_view_statement"}
		<table class="style1">
			<thead>
				<tr>
					<th style="text-align: left;">Curso</th>
					<th style="text-align: center;">Preço Base</th>
					<th style="text-align: center;">Pago</th>
					<th style="text-align: center;">Saldo</th>
					<th style="text-align: center;">{$smarty.const.__OPTIONS}</th>
				</tr>
			</thead>
			<tbody>
				{assign var="total_base_price" value="0"}
				{assign var="total_paid" value="0"}
	
				{foreach item="statement" from=$T_XPAY_NEGOCIATIONS}
					{math equation="total + current" total=$total_base_price current="`$statement.base_price`" assign="total_base_price"}
					{math equation="total + current" total=$total_paid current="`$statement.paid`" assign="total_paid"}
					<tr>
					 	<td>{$statement.module_printname}</td>
					 	<td align="center">#filter:currency:{$statement.base_price}#</td>
					 	<td align="center">#filter:currency:{$statement.paid}#</td>
					 	<td align="center">#filter:currency:{$statement.base_price-$statement.paid}#</td>
					 	<td align="center">
					 		<div>
						 		<a href="{$T_XPAY_BASEURL}&action=view_user_course_statement&negociation_id={$statement.id}" class="form-icon">
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
					<th>Total:</th>
					<th style="text-align: center;">#filter:currency:{$total_base_price}#</th>
					<th style="text-align: center;" class="xpay-paid">#filter:currency:{$total_paid}#</th>
					<th style="text-align: center;">#filter:currency:{$total_base_price-$total_paid}#</th>
					<th>&nbsp;</th>
				</tr>
			</tfoot>
		</table>
	{/capture}
	
	{if $T_XPAY_IS_ADMIN}
		{sC_template_printBlock
			title 			= $smarty.const.__XPAY_VIEW_USER_STATEMENT
			data			= $smarty.capture.t_xpay_view_statement
		}
	{else}
		{sC_template_printBlock
			title 			= $smarty.const.__XPAY_VIEW_MY_STATEMENT
			data			= $smarty.capture.t_xpay_view_statement
		}
	{/if}
{/if}
