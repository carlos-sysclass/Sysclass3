{include file="$T_MODULE_PAGAMENTO_BASEDIR/templates/includes/payment_options.tpl"}

{capture name="t_payments_table"}
<div class="grid_16 box border" style="margin-top: 15px;">
	<div class="headerTools">
		<span>
			<a class="sendInvoiceByEmail" href="javascript: void(0);">
				<img alt="_MODULE_PAGAMENTO_SEND_EMAIL_BOLETO" src="/themes/sysclass/images/icons/small/grey/mail.png">
				(Re)Enviar boleto por e-mail
			</a>
		</span>
	</div>
</div>
<div class="clear"></div>

<table id="_XPAYMENT_LAST_RECEIVED_LIST" class="display">
	<thead> 
		<tr>
			<th>{$smarty.const._MODULE_PAGAMENTO_PAYMENT_ID}</th>
			<th>{$smarty.const._LOGIN}</th>
			<th>{$smarty.const._MODULE_PAGAMENTO_PARCELAS}</th>
			<th>{$smarty.const._MODULE_PAGAMENTO_PROXIMO_VENCIMENTO}</th>
			<th>{$smarty.const._MODULE_PAGAMENTO_VALOR}</th>
			<th>{$smarty.const._MODULE_PAGAMENTO_VALOR_PAGO}</th>
			<th>{$smarty.const._MODULE_PAGAMENTO_VALOR_SALDO}</th>
			<th>{$smarty.const._OPERATIONS}</th> 
		</tr> 
	</thead>
	<tbody> 
		{foreach name="payment_types_iterarion" key="index" item="payment" from = $T_MODULE_PAGAMENTO_PAYMENTS}
		<tr>
			<td align="center">
				<a href="{$T_MODULE_PAGAMENTO_BASEURL}&action=view_payment&payment_id={$payment.payment_id}" title="{$smarty.const._MODULE_PAGAMENTO_PAYMENT_DETAILS}">{$payment.payment_id}</a></td> 
			<td>{$payment.user_id} - {$payment.login}</td>
			<td align="center">{$payment.total_parcelas}</td>
			<td align="center">#filter:date-{$payment.proximo_vencimento}#</td>
			   
			<td align="center">#filter:currency-{$payment.total_valor}#</td>
			<td align="center">#filter:currency-{$payment.total_pago}#</td>
			<td align="center">#filter:currency-{$payment.total_saldo}#</td>
			
			<td align="center">
				<div class="button_display">
					
					<button onclick="window.location.href = 'administrator.php?ctg=module&op=module_xuser&action=edit_xuser&xuser_login={$payment.login}#Detalhes_financeiros';" class="skin_colour round_all"">
						<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png">
						<span>Editar</span>
					</button>	
					<button onclick="window.location.href = '{$T_MODULE_PAGAMENTO_BASEURL}&action=view_payment&payment_id={$payment.payment_id}';" class="skin_colour round_all">
						<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/documents.png">
						<span>Visualizar</span>
					</button>
					<button onclick="window.open('{$T_MODULE_PAGAMENTO_BASEURL}&action=get_invoice&payment_id={$payment.payment_id}');" class="skin_colour round_all">
						<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/printer.png">
						<span>Imprimir</span>
					</button>
				</div>
			</td>
		</tr> 
		{/foreach}
	</tbody> 
</table>
{/capture}

{sC_template_printBlock 
	title=$smarty.const._MODULE_PAGAMENTO_PAYMENTS
	data=$smarty.capture.t_payments_table
	contentclass=""
}