{include file="$T_MODULE_PAGAMENTO_BASEDIR/templates/includes/payment_options.tpl"}

{capture name="t_update_invoices_tabbers"}
	{foreach name="update_invoices_iteration" key="index" item="module" from = $T_MODULE_PAGAMENTO_INVOICE_SUBMODULES}
	
		{include file=$module.template assign="templateData"}
		
		{eF_template_printBlock 
			tabber = $module.title
			title = $module.title
			data = $templateData
		}
	{foreachelse}
		{$smarty.const._MODULE_PAGAMENTO_SORRY_NO_SUBMOULES_FOUND}
	{/foreach}
{/capture}

{eF_template_printBlock 
	title =	$smarty.const._MODULE_PAGAMENTO_INVOICES_STATUS
	data  =	$smarty.capture.t_update_invoices_tabbers
	tabs  =	$T_MODULE_PAGAMENTO_INVOICE_SUBMODULES
}