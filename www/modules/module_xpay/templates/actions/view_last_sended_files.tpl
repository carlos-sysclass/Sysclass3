{include file="$T_XPAY_BASEDIR/templates/includes/dialog.view_file_details.tpl"}

{capture name="t_xpay_last_payments_widget"}
	{include file="$T_XPAY_BASEDIR/templates/includes/options.links.tpl"}
	
	<table class="style1 xpayDataTable">
		<thead>
			<tr>
				<th>{$smarty.const.__XPAY_FILE_NAME}</th>
				<th>{$smarty.const.__XPAY_PAYMENT_METHOD}</th>
				<th style="text-align: center">{$smarty.const.__XPAY_BOLETO_FILE_TIME}</th>
				<th style="text-align: center">{$smarty.const.__XPAY_BOLETO_FILE_SIZE}</th>
				<th style="text-align: center">{$smarty.const.__OPTIONS}</th>
			</tr>
		</thead>
	<tbody>
		{foreach item="method" from=$T_XPAY_LAST_FILES}
			{if $method.files|@count > 0}
				{foreach item="file" from=$method.files}
					<tr>
						<td>{$file.name}</td>
						<td>{$method.name}</td>
						<td align="center">#filter:timestamp_time-{$file.timestamp}#</td>
						<td align="center">{$file.size}</td>
						<td align="center">
							<a href="javascript: _sysclass('load', 'xpay').viewFileDetails('{$file.method_index}', '{$file.name}');"><img class="sprite16 sprite16-analysis" src="images/others/transparent.png" border="0"></a>
						</td>
					</tr>
				{/foreach}
			{/if}
		{/foreach}
	</tbody>
</table>
{/capture}
	
{eF_template_printBlock 
	title=$smarty.const.__XPAY_LAST_PAYMENTS
	data=$smarty.capture.t_xpay_last_payments_widget
}


