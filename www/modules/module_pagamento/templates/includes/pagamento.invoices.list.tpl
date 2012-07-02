{if !$T_INVOICE_TITLE}
{elseif $T_INVOICE_TITLE} 
	<h3>{$T_INVOICE_TITLE}</h3>
{else}
	<h3>{$smarty.const._MODULE_PAGAMENTO_INVOICES}</h3>
{/if}

	<table class="static" id="__PAGAMENTO_INVOICES_LIST">
		<thead>
			<tr>
				
				<th>{$smarty.const._PAGAMENTO_NRO}</th>
				<th>{$smarty.const._PAGAMENTO_VALOR}</th>
				{if !$T_PAGAMENTO_INVOICE_DISABLE_FIELDS.VALOR_DESCONTO}
				<th>{$smarty.const._PAGAMENTO_VALOR_DESCONTO}</th>
				{/if}
				<th>{$smarty.const._PAGAMENTO_VENCIMENTO}</th>
				<th>{$smarty.const.__PAGAMENTO_STATUS}</th>
				{if !$T_PAGAMENTO_INVOICE_DISABLE_FIELDS.OPTIONS}
				<th>{$smarty.const._OPTIONS}</th>
				{/if}
			</tr>
		</thead>
		<tbody>
			{assign var="invoice_total" value="0"}
			{assign var="invoice_total_desconto" value="0"}
			{foreach name="edit_payment_invoices_iteration" key="payment_invoices_index" item="invoice" from=$T_PAYMENT_INVOICES}
			
				{math equation="total + preco" total=$invoice_total preco="`$invoice.valor`" assign="invoice_total"}
				{math equation="total + preco" total=$invoice_total_desconto preco="`$invoice.valor_desconto`" assign="invoice_total_desconto"}
				<tr class="{cycle values="odd,even"} {$invoice.status|lower} {if $invoice.bloqueio == 1}bloqueio{/if} {if $invoice.pago == 1 || $invoice.pago == 2}pago{/if}" metadata="{Mag_Json_Encode data=$invoice}">
					<td class="center">
						{if $invoice.parcela_index == $payment.next_invoice}
							<img width="16" height="16" alt="Próximo E-mail" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/mail.png">
						{/if}
						{if $invoice.parcela_index-1 == 0}
							{$smarty.const._PAGAMENTO_MATRICULA}
						{elseif $invoice.parcela_index == 99}
							{$smarty.const.__PAGAMENTO_CANCELAMENTO}
						{else}
							{$invoice.parcela_index-1}
						{/if}
						
					</td>
					<td class="center">#filter:currency-{$invoice.valor}#</td>
					{if !$T_PAGAMENTO_INVOICE_DISABLE_FIELDS.VALOR_DESCONTO}
					<td class="center">#filter:currency-{$invoice.valor_desconto}#</td>
					{/if}
					<td class="center">#filter:date-{$invoice.data_vencimento}#</td>
					<td class="center">
						{if $invoice.pago == 1 || $invoice.pago == 2}
							Pago
						{elseif $invoice.bloqueio == 1}
							Bloqueado
						{else}
							{$invoice.status}
						{/if}
					</td>
					{if !$T_PAGAMENTO_INVOICE_DISABLE_FIELDS.OPTIONS}
					<td class="center">
						<div class="button_display">
							{*if $invoice.data_vencimento|@strtotime < $smarty.now*}
								{if $invoice.pago == 0}
									<button class="red skin_colour round_all paidLink " title="{$smarty.const.__XUSER_PAID_INVOICE_HINT}">
										<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/facebook_like.png">
										<span>Registrar Pagamento</span>
									</button>
								{else}
									<button class="green skin_colour round_all paidLink " title="{$smarty.const.__XUSER_UNPAID_INVOICE_HINT}">
										<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/facebook_like.png">
										<span>Estornar Pagamento</span>
									</button>
								{/if}
							{*/if*}
							{if $invoice.bloqueio == 1}
								<button class="red skin_colour round_all lockLink " title="{$smarty.const.__XUSER_UNLOCK_INVOICE_HINT}">
									<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/unlocked.png">
									<span>Desbloquear Parcela</span>
								</button>
							{else}
								<button class="green skin_colour round_all lockLink" title="{$smarty.const.__XUSER_LOCK_INVOICE_HINT}">
									<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/unlocked.png">
									<span>Bloquear Parcela</span>
								</button>
							{/if}
							<button class="skin_colour round_all editLink">
								<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png">
								<span>Editar</span>
							</button>
							{if $invoice.bloqueio == 0 && $invoice.pago == 0}
								<button class="skin_colour round_all invoicePrintLink" style="{if $invoice.pago == 0}visibility: visible;{else}visibility: hidden;{/if}">
									<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/printer.png">
									<span>Imprimir</span>
								</button>
							{/if}
						</div>
					</td>
					{/if}
				</tr>
			{/foreach}
		</tbody>
		<tfoot>
			<tr>
				<th>
					{$smarty.const._PAGAMENTO_SUM}
				</th>
				<th>
					#filter:currency-{$invoice_total}#
				</th>
				{if !$T_PAGAMENTO_INVOICE_DISABLE_FIELDS.VALOR_DESCONTO}
				<th>
					#filter:currency-{$invoice_total_desconto}#
				</th>
				{/if}
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				{if !$T_PAGAMENTO_INVOICE_DISABLE_FIELDS.OPTIONS}
				<th>&nbsp;</th>
				{/if}
			</tr>
		</tfoot>
	</table>