{include file="$T_PAGAMENTO_BASEDIR/templates/includes/payment_options.tpl"}



{capture name="t_payments_table"}

{include file="$T_PAGAMENTO_BASEDIR/templates/includes/xpayment.invoices.filters.tpl"}
<div class="blockContents">
	<div class="grid_16" style="margin-bottom: 20px">
		<a href="{$T_PAGAMENTO_BASEURL}&action=send_invoices">
			<span>Enviar Boletos agora</span>
		</a>
	</div>
	<div class="clear"></div>
	<h3>Lista de Envio Atual</h3>
	<!--
	<div class="grid_16">
		<button value="Aplicar" name="submit_apply" type="submit" class="button_colour round_all">
			<img width="24" height="24" src="/themes/sysclass/images/icons/small/white/bended_arrow_right.png">
			<span>>Enviar Boletos agora</span>
		</button>
	</div>
	-->
	<ul style="list-style: none; width: 100%; float: left;" id="xpayment_email_send_list" class="xenrollment-register-checklist">
		{foreach key="index" item="item" from = $T_XPAYMENT_TO_SEND_LIST}
			<li> 
				<a onclick="xPaymentAPI.removeFromSendListAction({$item.payment_id}, {$item.parcela_index}); jQuery(this).parents('li').remove();" href="javascript: void(0);">
						<img width="24" height="24" src="/themes/sysclass/images/icons/small/grey/delete.png" alt="Check">
				</a>
				<span class="check-item-ok">
					{$item.name} {$item.surname} - 
					Parcela {$item.parcela_index}/{$item.parcela_total} -
					Venc.: #filter:date-{$item.data_vencimento}# -
					Valor: #filter:currency-{$item.valor}# 
				</span> 
			</li>
		{/foreach}
	</ul>
</div>
<div class="clear"></div>
<table id="_XPAYMENT_TO_SEND_LIST" class="display">
	<thead> 
		<tr>
			<th>{$smarty.const.__XPAYMENT_EXPIRATION_DATE}</th>
			<th>{$smarty.const.__XPAYMENT_PARCELA_INDEX}</th>
			<th>{$smarty.const.__USER}</th>
			<th>{$smarty.const.__COURSE}</th>
			<th>{$smarty.const.__XPAYMENT_STATUS}</th>
			<th>{$smarty.const.__XPAYMENT_VALOR}</th>
			<th>{$smarty.const._OPERATIONS}</th> 
		</tr> 
	</thead>
	<tbody> 
		{foreach key="index" item="item" from = $T_XPAYMENT_LIST}
		<tr metadata="{Mag_Json_Encode data = $item}">
			<td align="center">#filter:date-{$item.data_vencimento}#</td>
			<td align="center">{$item.parcela_index}/{$item.parcela_total} </td>
			<td><a href="administrator.php?ctg=module&op=module_xuser&action=edit_xuser&xuser_id={$item.user_id}">{$item.username}</a></td>
			<td><a href="administrator.php?ctg=module&op=module_xcourse&action=edit_xcourse&xcourse_id={$item.course_id}">{$item.curso}</a></td>
			<td align="center">{$item.status}</td>
			<td align="center">#filter:currency-{$item.valor}#</td>
			<td align="center">
				<div class="button_display">
					<button onclick="" class="appendToEmailList skin_colour round_all"">
						<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
						<span>Adicionar a lista</span>
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
	contentclass="blockContents"
}