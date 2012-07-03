{include file="$T_PAGAMENTO_BASEDIR/templates/includes/payment_options.tpl"}

{capture name="t_payments_table"}

<table id="_XPAYMENT_LAST_SENDED_LIST" class="display">
	<thead> 
		<tr>
			<th>{$smarty.const.__XPAYMENT_REGISTER_DATE}</th>
			<th>{$smarty.const._LOGIN}</th>
			<th>{$smarty.const.__XPAYMENT_TOTAL_ENVIADO}</th>
			<th>{$smarty.const.__XPAYMENT_TOTAL_ERRO}</th>
			<th>{$smarty.const._OPERATIONS}</th> 
		</tr> 
	</thead>
	<tbody> 
		{foreach key="index" item="item" from = $T_XPAYMENT_EMAIL_LIST}
		<tr>
			<td align="center">#filter:date-{$item.data_registro}#</td>
			<td><a href="administrator.php?ctg=module&op=module_xuser&action=edit_xuser&xuser_id={$item.user_id}">{$item.username}</a></td>
			<td align="center">{$item.total_sucesso}</td>
			<td align="center">{$item.total_erro}</td>
			<td align="center">
				<div class="button_display">
					<button onclick="window.location.href = 'administrator.php?ctg=module&op=module_xuser&action=edit_xuser&xuser_login={$payment.login}#Detalhes_financeiros';" class="skin_colour round_all"">
						<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png">
						<span>Editar</span>
					</button>	
				</div>
			</td>
		</tr> 
		{/foreach}
	</tbody> 
</table>
{/capture}

{eF_template_printBlock 
	title=$smarty.const._MODULE_PAGAMENTO_PAYMENTS
	data=$smarty.capture.t_payments_table
	contentclass=""
}