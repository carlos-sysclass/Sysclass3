{capture name="t_create_payment_type_form"}
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
		
{eF_template_printBlock 
	title=$smarty.const._MODULE_PAGAMENTO_ADD_PAYMENT_TYPE
	data=$smarty.capture.t_create_payment_type_form
}