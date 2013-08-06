{capture name="t_xpay_cielo_last_transactions"}
<table id="xpay-cielo-repeatable-table" class="style1">
	<thead>
		<tr>
			<th style="text-align: center;">Último Pagamento</th>
			<th style="text-align: center;">Próximo</th>
			<th style="text-align: center;">Usuário</th>
			<th style="text-align: center;">Bandeira</th>
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;">Cartão</th>
			<th style="text-align: center;">Opções</th>
		</tr>

	</thead>
	<tbody>
		{foreach item="trans" from=$T_XPAY_CIELO_TRANSACTIONS}
			<tr {if $trans.overdue == 1}class="overdue"{/if}>
				<td align="center">#filter:date-{$trans.last_payment}#</td>
				<td align="center">
				{if !is_null($trans.next_payment)}
					#filter:date-{$trans.next_payment}#
				{/if}

				</td>
				<td>{$trans.login}</td>
				<td align="center"><img src="{$T_XPAY_CIELO_BASELINK}images/{$trans.bandeira}.png" /></td>
				<td align="center">#filter:currency:{$trans.valor}#</td>
				<td align="center">{$trans.cartao}</td>
				<td align="center">
					<a class="form-icon editLink" href="{$T_XPAY_BASEURL}&action=view_user_statement&amp;xuser_login={$trans.login}"><img border="0" alt="Extrato do Aluno" title="Extrato do Aluno" class="sprite16 sprite16-do_pay" src="images/others/transparent.gif" /></a>
					{if $trans.status_id == 0}
						{* EXCLUDE OPTION *}
					{/if}
					{if $trans.status_id == 4}
					<!--
						<a class="form-icon xpay-cielo-do-capture-link" onclick="_sysclass('load', 'xpay_cielo').doCaptureAction('{$trans.tid}');" href="javascript: void(0);">
							<img src="images/others/transparent.gif" class="sprite16 sprite16-arrow_right">
						</a>
					-->
					{/if}
					{if $trans.overdue == 1}
						<a class="form-icon xpay-cielo-do-withdraw-link" onclick="_sysclass('load', 'xpay_cielo').doWithdrawAction('{$trans.negocioation}', '{$trans.next_invoice}');" href="javascript: void(0);"><img src="images/others/transparent.gif" class="sprite16 sprite16-arrow_right" alt="Tentar realizar pagamento" /></a>
					{/if}
				</td>
			</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<th style="text-align: center;">Último Pagamento</th>
			<th style="text-align: center;">Próximo</th>
			<th style="text-align: center;">Usuário</th>
			<th style="text-align: center;">Bandeira</th>
			<th style="text-align: center;">Valor</th>
			<th style="text-align: center;">Cartão</th>
			<th style="text-align: center;">Opções</th>
		</tr>
	</tfoot>
</table>
<style>
{literal}
	.overdue td{
		color: #af0000;

	}
{/literal}
</style>
{/capture}
	
{sC_template_printBlock 
	title=$smarty.const.__XPAY_CIELO_LAST_TRANSACTIONS
	data=$smarty.capture.t_xpay_cielo_last_transactions
}
