{if $T_XPAY_STATEMENT}

	{capture name="t_xpay_do_payment"}
		{$T_XPAY_METHOD_FORM.javascript}
		<form {$T_XPAY_METHOD_FORM.attributes}>
			{$T_XPAY_METHOD_FORM.hidden}
			
			<div>
				{foreach key="pay_module_key" item="pay_module" from=$T_XPAY_METHODS}
					<div class="form-field clear" style="float: left; margin-top:3px;" >
						{if $pay_module.title}
							<label class="clear" for="textfield">{$pay_module.title}</label>
						{/if}
						{foreach key="pay_index" item="pay_method" from=$pay_module.options}
							{assign var = "input_name"  value = $pay_module_key:$pay_index }
							{$T_XPAY_METHOD_FORM.pay_methods[$input_name].html}
						{/foreach}
					</div>
				{/foreach}
				
				<div style="float: right;">
					<button class="form-button icon-save ui-state-disabled" id ="xpay-do-payment-button" type="submit" disabled="disabled">
						<img width="29" height="29" src="images/transp.png">
						{if $T_XPAY_INVOICE_IS_PAID}
							<span>{$smarty.const.__XPAY_VIEW_COPY}</span>
						{else}
							<span>{$smarty.const.__XPAY_DO_PAY}</span>
						{/if}
					</button>
				</div>					
			</div>
			<div class="clear"></div>
			
			<div id="xpay-submodule-options-container">
			</div>
			<div class="clear"></div>
			
					</form>
	{/capture}
	
	{sC_template_printBlock
		title 			= $smarty.const.__XPAY_DO_PAYMENT
		sub_title		= $smarty.const.__XPAY_DO_PAYMENT_INSTRUCTIONS
		data			= $smarty.capture.t_xpay_do_payment
	}
{/if}