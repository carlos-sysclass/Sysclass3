{if $T_MODULE_PAGAMENTO_HOOK_PAYMENTS === FALSE} 
	FALSE
{else}
	<script type="text/javascript">
		var _edited_user_login = '{$T_XPAYMENT_EDITED_USER.login}';
	</script>
	<div id="_PAGAMENTO_NOTIMPLEMENTEDYET_DIALOG" class="blockContents" title="{$smarty.const._PAGAMENTO_NOTIMPLEMENTEDYET_DIALOG}">
		<p>Este recurso ainda não foi implantado. Pedimos desculpas pelo aborrecimento.</p>
	</div>
	
	<div id="_PAGAMENTO_INVOICES_DIALOG" class="blockContents" title="{$smarty.const._PAGAMENTO_EDITINVOICESDIALOG}">
		{$T_PAGAMENTO_INVOICE_UPDATE_FORM.javascript}
		<form {$T_PAGAMENTO_INVOICE_UPDATE_FORM.attributes}>
			{$T_PAGAMENTO_INVOICE_UPDATE_FORM.hidden}
			<div class="flat_area">
				<div class="grid_16">
					<!-- 
					<label>{$T_PAGAMENTO_INVOICE_UPDATE_FORM.status_id.label}</label> 
					{$T_PAGAMENTO_INVOICE_UPDATE_FORM.status_id.html}
					{$T_PAGAMENTO_INVOICE_UPDATE_FORM.status_id.error}
 					-->
					<label>{$T_PAGAMENTO_INVOICE_UPDATE_FORM.vencimento.label}</label> 
					{$T_PAGAMENTO_INVOICE_UPDATE_FORM.vencimento.html}
					{$T_PAGAMENTO_INVOICE_UPDATE_FORM.vencimento.error}
				</div>
			</div>
		</form>
	</div>
	
	<div class="grid_16 block">
		{$T_XPAYMENT_SENDER_UPDATE_FORM.javascript}
		<form {$T_XPAYMENT_SENDER_UPDATE_FORM.attributes}>
		{$T_XPAYMENT_SENDER_UPDATE_FORM.hidden}
				<h3>Selecione os responsáveis pelo pagamento e seus percentuais</h3>
				<div class="grid_16">
					<label class="inline">{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_sacado.label}</label>
					{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_sacado.html}
				</div>

				<div class="grid_5">
					{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_student.html}
					<label class="inline">{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_student.label}</label>
					
					<div class="ui-slider-indicator">0%</div>
					<div class="ui-slider" id="sender_student_ammount_slider"></div>
				</div>
				<div class="grid_6"> 
					{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_parent.html}
					<label class="inline">{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_parent.label}</label>
					
					<div class="ui-slider-indicator">0%</div>
					<div class="ui-slider" id="sender_parent_ammount_slider"></div>
				</div>
				<div class="grid_5">
					{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_financial.html}
					<label class="inline">{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_financial.label}</label>
					
					<div class="ui-slider-indicator">0%</div>
					<div class="ui-slider" id="sender_financial_ammount_slider"></div>
				</div>
				<div class="grid_16" style="margin-top: 20px;">
					<button class="button_colour round_all" type="submit" name="{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_submit.name}" value="{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_submit.value}">
						<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
						<span>{$T_XPAYMENT_SENDER_UPDATE_FORM.sender_submit.value}</span>
					</button>
				</div>
		</form>						
	</div>
	{foreach name="edit_payment_iteration" key="payment_index" item="payment" from=$T_MODULE_PAGAMENTO_HOOK_PAYMENTS}
	<!--
		<div class="grid_16 box border">
			<div class = "headerTools">
				<span>
					<a href = "javascript: void(0);" class="sendInvoiceByEmail" >
						<img src = "/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/mail.png" alt = "{$smarty.const._MODULE_PAGAMENTO_SEND_EMAIL_BOLETO}">
						(Re)Enviar boleto por e-mail
					</a>
				</span>
			</div>
		</div>
	-->

		<div class="grid_16 block">
			{*include 
				file="$T_MODULE_XUSER_BASEDIR/templates/includes/xuser.list.courses.tpl"
				T_XUSER_COURSES_LIST=$payment.courses
			*}
			
			{if $payment.courses|@count > 0}
			
			<div id="_PAGAMENTO_METHOD_SELECT" class="blockContents" title="{$smarty.const._PAGAMENTO_SELECTPAYMENTMETHODDIALOG}">
				{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.javascript}
				<form {$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.attributes}>
					{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.hidden}
					<div class="flat_area">
						<div class="grid_16">
							<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.payment_type_id.label}</label> 
							{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.payment_type_id.html}
							
							<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.data_matricula.label}</label> 
							{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.data_matricula.html}
							
							<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.data_inicio.label}</label> 
							{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.data_inicio.html}
							
							<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.parcelas.label}</label> 
							{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.parcelas.html}
							
							<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.vencimento.label}</label> 
							{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.vencimento.html}
							
							<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.desconto.label}</label> 
							{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.desconto.html}
						</div>
					</div>
				</form>
			</div>
			
			<h3>{$smarty.const._PAGAMENTO_SELECTED_METHOD}</h3>
			<div>
				{if $payment.payment_type}
					<span id="_PAGAMENTO_METHOD_SELECT_DESCRIPTION">
						<strong>{$payment.payment_type}</strong> - {$payment.payment_type_description}
					</span>
					<a href="javascript: changePaymentMethod('{$T_XPAYMENT_EDITED_USER.login}', {$payment.payment_id});">[ {$smarty.const._CHANGE} ] </a>
				{else}
					<span id="_PAGAMENTO_METHOD_SELECT_DESCRIPTION">
						{$smarty.const._PAGAMENTO_NOPAYMENTMETHODFOUND} -
					</span> 
					<a href="javascript: insertPaymentMethod('{$T_XPAYMENT_EDITED_USER.login}');">[ {$smarty.const._SELECT} ] </a>
				{/if}
			</div>
			{/if}
			<br />
			{if $payment.courses|@count > 0}
				{include 
					file="$T_MODULE_PAGAMENTO_BASEDIR/templates/includes/pagamento.invoices.summary.tpl"
					T_PAYMENT_INVOICES_SUMMARY=$payment.invoices_summary 
				}
				<br />
				{include 
					file="$T_MODULE_PAGAMENTO_BASEDIR/templates/includes/pagamento.invoices.list.tpl"
					T_PAYMENT_INVOICES=$payment.invoices
				}
			{/if}
		</div>
	{/foreach}
{/if}