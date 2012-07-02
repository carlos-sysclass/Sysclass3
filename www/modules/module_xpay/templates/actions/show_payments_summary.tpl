{capture name="t_xpay_last_payments_widget"}
	{include file="$T_XPAY_BASEDIR/templates/includes/last_payments.list.tpl"
		T_XPAY_LAST_PAYMENTS=$T_XPAY_LAST_PAYMENTS
	}

	<div style="margin-top: 20px;" align="right">
		<button class="form-button icon-list" type="button" name="xpayViewAllPayments" onclick="window.location.href = '{$T_XPAY_BASEURL}&action=view_last_paid_invoices'">
			<img width="29" height="29" src="images/transp.png">
			<span>{$smarty.const.__XPAY_ALL_LAST_PAYMENTS}</span>
		</button>		
	</div>
{/capture}

{capture name="t_xpay_summary_list"}
	{include file="$T_XPAY_BASEDIR/templates/includes/options.links.tpl"}


	<div class="grid_12">
	{eF_template_printBlock 
		title=$smarty.const.__XPAY_LAST_PAYMENTS
		data=$smarty.capture.t_xpay_last_payments_widget
	}
	</div>
{/capture}

{eF_template_printBlock 
	title=$smarty.const.__XPAY_SUMMARY_LIST
	data=$smarty.capture.t_xpay_summary_list
}
