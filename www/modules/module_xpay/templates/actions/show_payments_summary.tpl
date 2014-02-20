{capture name="t_xpay_last_payments_widget"}

	{include file="$T_XPAY_BASEDIR/templates/includes/last_payments.list.tpl"
		T_XPAY_LAST_PAYMENTS=$T_XPAY_LAST_PAYMENTS
	}

	<div style="margin-top: 20px;" align="right">
		<button class="form-button icon-list" type="button" name="xpayViewAllPayments" onclick="window.location.href = '{$T_XPAY_BASEURL}&action=view_last_paid_invoices'">
			<img width="29" height="29" src="images/transp.png">
			<span>{$smarty.const.__XPAY_VIEW_ALL_LIST}</span>
		</button>		
	</div>
{/capture}

{capture name="t_xpay_last_files_widget"}
	{include file="$T_XPAY_BASEDIR/templates/includes/dialog.view_file_details.tpl"}

	{include file="$T_XPAY_BASEDIR/templates/includes/last_files.list.tpl"
		T_XPAY_LAST_FILES=$T_XPAY_LAST_FILES
	}

	<div style="margin-top: 20px;" align="right">
		<button class="form-button icon-list" type="button" name="xpayViewAllFiles" onclick="window.location.href = '{$T_XPAY_BASEURL}&action=view_last_sended_files'">
			<img width="29" height="29" src="images/transp.png">
			<span>{$smarty.const.__XPAY_VIEW_ALL_LIST}</span>
		</button>		
	</div>
{/capture}

{capture name="t_xpay_user_debts"}
	{include file="$T_XPAY_BASEDIR/templates/includes/debts.list.tpl"
		T_XPAY_DEBTS_LIST=$T_XPAY_DEBTS_LIST
	}

	<div style="margin-top: 20px;" align="right">
		<button class="form-button icon-list" type="button" name="xpayViewAllFiles" onclick="window.location.href = '{$T_XPAY_BASEURL}&action=view_users_in_debts'">
			<img width="29" height="29" src="images/transp.png">
			<span>{$smarty.const.__XPAY_VIEW_ALL_LIST}</span>
		</button>		
	</div>
{/capture}

{capture name="t_xpay_summary_list"}
	{include file="$T_XPAY_BASEDIR/templates/includes/options.links.tpl"}

	<div class="grid_12">
	{sC_template_printBlock 
		title=$smarty.const.__XPAY_LAST_PAYMENTS
		data=$smarty.capture.t_xpay_last_payments_widget
	}
	</div>

	<div class="grid_12">
	{sC_template_printBlock 
		title=$smarty.const.__XPAY_LAST_FILES
		data=$smarty.capture.t_xpay_last_files_widget
	}
	</div>
	<div class="clear"></div>
	<div class="grid_12">
	{sC_template_printBlock 
		title=$smarty.const.__XPAY_USER_DEBTS
		data=$smarty.capture.t_xpay_user_debts
	}
	</div>
{/capture}

{sC_template_printBlock 
	title=$smarty.const.__XPAY_SUMMARY_LIST
	data=$smarty.capture.t_xpay_summary_list
}
