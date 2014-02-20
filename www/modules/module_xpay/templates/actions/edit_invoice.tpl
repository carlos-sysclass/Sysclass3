{if $T_XPAY_IS_DONE}
var message = {$T_XPAY_MESSAGE};
	<script type="text/javascript">
		var message = {$T_XPAY_MESSAGE};
		{literal}
	    //popup_table = window.parent.jQuery('#popup_table').hide();
	    window.parent.sC_js_showDivPopup();
		window.parent.jQuery.messaging.show(message);

		//window.parent.jQuery.messaging.show(message);
		{/literal}
	</script>
	
{else}
	{capture name="t_xpay_edit_invoice"}
		<div class="form-container xpay-create-payment-form" title="{$smarty.const.__XPAY_CREATE_NEW_NEGOCIATION}">
			{$T_XPAY_EDIT_INVOICE_FORM.javascript}
			<form {$T_XPAY_EDIT_INVOICE_FORM.attributes}>
				{$T_XPAY_EDIT_INVOICE_FORM.hidden|@implode}
				
					<div>
						<label>{$T_XPAY_EDIT_INVOICE_FORM.valor.label}</label>
						{$T_XPAY_EDIT_INVOICE_FORM.valor.html}
					</div>
					<div>
						<label>{$T_XPAY_EDIT_INVOICE_FORM.data_vencimento.label}</label>
						{$T_XPAY_EDIT_INVOICE_FORM.data_vencimento.html}
					</div>
				
					<div class="clear"></div>
					<div align="center" style="margin-top: 20px;" class="buttons">
						<button value="{$T_XPAY_EDIT_INVOICE_FORM._edit_invoice.label}" name="{$T_XPAY_EDIT_INVOICE_FORM._edit_invoice.name}" type="submit" class="form-button">
							<img width="29" height="29" src="themes/sysclass3/images/transp.png">
							<span>{$T_XPAY_EDIT_INVOICE_FORM._edit_invoice.label}</span>
						</button>
					</div>
			</form>
		</div>
	{/capture}
	<div align="center">
	{sC_template_printBlock
		title 			= $smarty.const.__XPAY_EDIT_INVOICE
		data			= $smarty.capture.t_xpay_edit_invoice
		contentclass	= "blockContents "
	}
	</div>
{/if}