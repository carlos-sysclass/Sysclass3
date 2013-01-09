{include file="$T_MODULE_PAGAMENTO_BASEDIR/templates/includes/payment_options.tpl"}

{capture name="t_payment_types_table"}

<div class="grid_16 box border" style="margin-top: 15px;">
	<div class="headerTools">
		<span>
			<img src = "images/16x16/add.png" title = "{$smarty.const._MODULE_PAGAMENTO_ADD_PAYMENT_TYPE}" alt = "{$smarty.const._MODULE_PAGAMENTO_ADD_PAYMENT_TYPE}">
			<a href = "{$T_MODULE_PAGAMENTO_BASEURL}&action=create_payment_type"  title = "{$smarty.const._MODULE_PAGAMENTO_ADD_PAYMENT_TYPE}">{$smarty.const._MODULE_PAGAMENTO_ADD_PAYMENT_TYPE}</a>
		</span>
	</div>
</div>
<div class="clear"></div>

<table id="_XPAYMENT_TYPES_LIST" class="display"> 
	<thead> 
		<tr> 
			<th>{$smarty.const._MODULE_PAGAMENTO_PAYMENT_ID}</th> 
			<th>{$smarty.const._MODULE_PAGAMENTO_REGISTER_DATE}</th> 
			<th>{$smarty.const._MODULE_PAGAMENTO_TITLE}</th> 
			<th>{$smarty.const._MODULE_PAGAMENTO_MODULE_NAME}</th>
			<th>{$smarty.const._OPTIONS}</th>
		</tr> 
	</thead>
	<tbody> 
		{foreach name="payment_types_iterarion" key="index" item="payment_type" from = $T_MODULE_PAGAMENTO_PAYMENTS_TYPES}
		<tr>
			<td align="center">{$payment_type.payment_type_id}</td> 
			<td align="center">#filter:datetime-{$payment_type.data_registro}#</td> 
			<td>{$payment_type.title}</td> 
			<td align="center">{$payment_type.module_class_name}</td>
			<td align="center">
				<div class="button_display">
					<button onclick="window.location.href = '{$T_MODULE_PAGAMENTO_BASEURL}&action=edit_payment_type&payment_type_id={$payment_type.payment_type_id}';" class="skin_colour round_all">
						<img width="16" height="16" title="Editar" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png">
						<span>Editar</span>
					</button>
					<button onclick="deletePaymentType({$payment_type.payment_type_id});" class="skin_colour round_all">
						<img width="16" height="16" title="Deletar" alt="Deletar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/delete.png">
						<span>Deletar</span>
					</button>
				</div>
			</td>
		</tr> 
		{/foreach}
	</tbody> 
</table>
{/capture}


{eF_template_printBlock 
	title=$smarty.const._MODULE_PAGAMENTO_PAYMENT_TYPES
	data=$smarty.capture.t_payment_types_table
	contentclass=""
}