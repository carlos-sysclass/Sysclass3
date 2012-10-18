<table class="style1 ">
	<thead>
		<tr>
			<th>{$smarty.const.__XPAY_FILE_NAME}</th>
			<th style="text-align: center">{$smarty.const.__XPAY_BOLETO_FILE_TIME}</th>
			<th style="text-align: center">{$smarty.const.__XPAY_BOLETO_FILE_SIZE}</th>
			<th style="text-align: center">{$smarty.const.__OPTIONS}</th>
		</tr>
	</thead>
	<tbody>
		{foreach item="method" from=$T_XPAY_LAST_FILES}
			{if $method.files|@count > 0}
				<tr>
					<th colspan="2">{$smarty.const.__XPAY_PAYMENT_METHOD}: {$method.name}</th>
					<th style="text-align: center">{$method.size}</th>
				</tr>				
				{foreach item="file" from=$method.files}
					<tr>
						<td>{$file.name}</td>
						<td align="center">#filter:timestamp_time-{$file.timestamp}#</td>
						<td align="center">{$file.size}</td>
						<td align="center">
							<a href="javascript: _sysclass('load', 'xpay').viewFileDetails('{$file.method_index}', '{$file.name}');" title="Visualizar detalhes do arquivo"><img class="sprite16 sprite16-analysis" src="images/others/transparent.png" border="0"></a>
							<a href="javascript: _sysclass('load', 'xpay').importFileToSystem('{$file.method_index}', '{$file.name}');" title="Importar arquivo novamente"><img class="sprite16 sprite16-import" src="images/others/transparent.png" border="0"></a>
						</td>
						
						
					</tr>
				{/foreach}
			{/if}
		{/foreach}
	</tbody>
</table>