{if $T_XPAY_IS_DONE}
var message = {$T_XPAY_MESSAGE};
	<script type="text/javascript">
		var message = {$T_XPAY_MESSAGE};
		{literal}
	    //popup_table = window.parent.jQuery('#popup_table').hide();
	    window.parent.eF_js_showDivPopup();
		window.parent.jQuery.messaging.show(message);

		window.setparent.jQuery.messaging.show(message);
		
		{/literal}
	</script>
	
{else}
	{capture name="t_xpay_create_payment"}
		<div class="form-container xpay-create-payment-form" title="{$smarty.const.__XPAY_CREATE_NEW_NEGOCIATION}">
			{$T_XPAY_CREATE_PAYMENT_FORM.javascript}
			<form {$T_XPAY_CREATE_PAYMENT_FORM.attributes}>
				{$T_XPAY_CREATE_PAYMENT_FORM.hidden|@implode}
				
					<div>
						<label>{$T_XPAY_CREATE_PAYMENT_FORM.real_value.label}</label>
						{$T_XPAY_CREATE_PAYMENT_FORM.real_value.html}
					</div>
					<div>
						<label>{$T_XPAY_CREATE_PAYMENT_FORM.subtract_value.label}</label>
						{$T_XPAY_CREATE_PAYMENT_FORM.subtract_value.html}
					</div>
				
					<div>
						<label>{$T_XPAY_CREATE_PAYMENT_FORM.paid.label}</label>
						{$T_XPAY_CREATE_PAYMENT_FORM.paid.html}
					</div>
					<div>
						<label>{$T_XPAY_CREATE_PAYMENT_FORM.description.label}</label>
						{$T_XPAY_CREATE_PAYMENT_FORM.description.html}
					</div>
					<div class="clear"></div>
					<div align="center" style="margin-top: 20px;" class="buttons">
						<button value="{$T_XPAY_CREATE_PAYMENT_FORM._create_payment.label}" name="{$T_XPAY_CREATE_PAYMENT_FORM._create_payment.name}" type="submit" class="form-button">
							<img width="29" height="29" src="themes/sysclass3/images/transp.png">
							<span>{$T_XPAY_CREATE_PAYMENT_FORM._create_payment.label}</span>
						</button>
					</div>
			</form>
		</div>
	{/capture}
	<div align="center">
	{eF_template_printBlock
		title 			= $smarty.const.__XPAY_DO_PAYMENT
		data			= $smarty.capture.t_xpay_create_payment
		contentclass	= "blockContents "
	}
	</div>
{/if}