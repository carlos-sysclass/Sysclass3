{capture name="t_xpay_cielo_last_transactions"}
<table id="xpay-cielo-last-transactions-table" class="style1">
	<thead>
		<tr>
			<th style="text-align: center;">TID</th>
			<th style="text-align: center;">Data</th>
			<th style="text-align: center;">Usuário</th>
			<th style="text-align: center;">Bandeira</th>
			<th style="text-align: center;">Forma</th>
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;">Status</th>
			<th style="text-align: center;">Opções</th>
		</tr>
	</thead>
	<tbody>
		{foreach item="trans" from=$T_XPAY_CIELO_TRANSACTIONS}
			<tr>
				<td>{$trans.tid}</td>
				<td align="center">#filter:date-{$trans.data}#</td>
				<td>{$trans.login}</td>
				<td align="center">
					<img src="{$T_XPAY_CIELO_BASELINK}images/{$trans.bandeira}.png" />
					<span style="display:none;">{$trans.bandeira}</span>
				</td>
				<td align="center">{$trans.forma_pagamento}</td>
				<td align="center">#filter:currency:{$trans.valor}#</td>
				<td align="center">{$trans.status_id} - {$trans.status}</td>
				<td align="center">
					{if $trans.status_id == 0}
						{* EXCLUDE OPTION *}
					{/if}
					{if $trans.status_id == 4}
						<a class="form-icon xpay-cielo-do-capture-link" onclick="_sysclass('load', 'xpay_cielo').doCaptureAction('{$trans.tid}');" href="javascript: void(0);">
							<img src="images/others/transparent.gif" class="sprite16 sprite16-arrow_right">
						</a>
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<th style="text-align: center;">TID</th>
			<th style="text-align: center;">Data</th>
			<th style="text-align: center;">Usuário</th>
			<th style="text-align: center;">Bandeira</th>
			<th style="text-align: center;">Forma</th>
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;">Status</th>
			<th style="text-align: center;">Opções</th>
		</tr>
	</tfoot>
</table>
{/capture}
	
{sC_template_printBlock 
	title=$smarty.const.__XPAY_CIELO_LAST_TRANSACTIONS
	data=$smarty.capture.t_xpay_cielo_last_transactions
}
