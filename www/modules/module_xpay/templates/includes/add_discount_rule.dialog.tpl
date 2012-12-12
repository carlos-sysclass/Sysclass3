<div class="xpay-invoice-params-selection form-container" id="xpay-invoice-params-selection" title="{$smarty.const.__XPAY_CREATE_NEW_NEGOCIATION}">
	<form {$T_XPAY_ADD_RULE_FORM.attributes}>
		{$T_XPAY_ADD_RULE_FORM.hidden|@implode}
		<div align="left">
			<label>{$T_XPAY_ADD_RULE_FORM.description.label}:</label>
			<span>{$T_XPAY_ADD_RULE_FORM.description.html}</span>
		</div>
		<div align="left">
			<label>{$T_XPAY_ADD_RULE_FORM.type_id.label}:</label>
			<span>{$T_XPAY_ADD_RULE_FORM.type_id.html}</span>
		</div>
		<div>
			<label>{$T_XPAY_ADD_RULE_FORM.percentual.label}:</label>
			<span>{$T_XPAY_ADD_RULE_FORM.percentual.html}</span>
		</div>

		<div class="xpay-show-on-percentual" id="xpay-show-on-percentual-1"> 
			<label>{$T_XPAY_ADD_RULE_FORM.valor_percentual.label}:</label>
			<span>{$T_XPAY_ADD_RULE_FORM.valor_percentual.html} %</span>
		</div>
		<div class="xpay-show-on-percentual" id="xpay-show-on-percentual-0">
			<label>{$T_XPAY_ADD_RULE_FORM.valor_absoluto.label}:</label>
			<span>{$T_XPAY_ADD_RULE_FORM.valor_absoluto.html}</span>
		</div>
		<!--
		<div align="left">
			<label>{$T_XPAY_ADD_RULE_FORM.applied_on.label}:</label>
			<span>{$T_XPAY_ADD_RULE_FORM.applied_on.html}</span>
		</div>
		--->
	 </form>
</div>