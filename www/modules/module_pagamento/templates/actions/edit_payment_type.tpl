{capture name="t_edit_payment_type_form"}
	{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.javascript}
	<form {$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.attributes}>
		{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.hidden}
		<div class="flat_area">
			<div class="grid_16">
				<label>{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.module_class_name.label}</label> 
				{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.module_class_name.html}
				{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.module_class_name.error}
			</div>
			<div class="grid_16">
				<label>{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.title.label}</label> 
				{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.title.html}
				{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.title.error}
			</div>
			<div class="grid_16">							
				<label>{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.comments.label}</label> 
				{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.comments.html}
				{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.comments.error}
			</div>
			<div class="grid_16">							
				<label>{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.active.label}</label> 
				{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.active.html}
				{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.active.error}
			</div>
			
			<div class="clear"></div>

			<div class="grid_16">
				<button class="button_colour round_all" type="submit" name="{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.submit_apply.name}" value="{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.submit_apply.value}">
					<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
					<span>{$T_MODULE_PAGAMENTO_CREATE_PAYMENT_FORM.submit_apply.value}</span>
				</button>
			</div>
		</div>
	</form>
{/capture}
{capture name="t_edit_payment_type_submodule_form"}
	{include file="$T_SUBMODULE_TEMPLATE"}
{/capture}

{capture name="t_edit_payment_tabbers"}
	{sC_template_printBlock 
		tabber = $T_EDIT_TABS[0].title
		title= $smarty.const._MODULE_PAGAMENTO_EDIT_PAYMENT_TYPE
		data=$smarty.capture.t_edit_payment_type_form
	}
	{sC_template_printBlock 
		tabber = $T_EDIT_TABS[1].title
		title = $T_SUBMODULE_TITLE
		data = $smarty.capture.t_edit_payment_type_submodule_form
	}
{/capture}


{sC_template_printBlock 
	title=$smarty.const._PAGAMENTO_CONFIG_TIPOS
	data=$smarty.capture.t_edit_payment_tabbers
	tabs = $T_EDIT_TABS
}