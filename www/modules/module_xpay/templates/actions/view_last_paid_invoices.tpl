{capture name="t_xpay_last_payments_widget"}
	{include file="$T_XPAY_BASEDIR/templates/includes/options.links.tpl"}

	{include file="$T_XPAY_BASEDIR/templates/includes/last_payments.list.tpl"
		T_XPAY_LAST_PAYMENTS=$T_XPAY_LAST_PAYMENTS
		T_XPAY_TABLE_CLASS="xpayDataTable"
	}
{/capture}
	
{eF_template_printBlock 
	title=$smarty.const.__XPAY_LAST_PAYMENTS
	data=$smarty.capture.t_xpay_last_payments_widget
}