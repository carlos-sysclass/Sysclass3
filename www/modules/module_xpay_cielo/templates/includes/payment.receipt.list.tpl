{foreach item="transaction" from=T_XPAY_CIELO_TRANSACTIONS}
	{include file="$T_XPAY_CIELO_BASEDIR/templates/includes/payment.receipt.tpl"
		T_XPAY_CIELO_TRANS=$T_XPAY_CIELO_TRANS
		T_XPAY_CIELO_RETURN_LINK=$T_XPAY_CIELO_RETURN_LINK
	}
{/foreach}
