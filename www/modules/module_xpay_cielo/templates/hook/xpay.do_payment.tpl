{if isset($T_XPAY_CIELO_URL_AUTENTICACAO)}
<!-- 	<a id="xpay-cielo-fancybox-link" href="{$T_XPAY_CIELO_URL_AUTENTICACAO}">dsadsa</a>  -->
	<!-- 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.0/themes/base/jquery-ui.css"/>
	 -->
	<div id="xpay-cielo-modal">
		<iframe 
			id="modalIFrame" 
			width="450" 
			height="650" 
			marginWidth="0" 
			marginHeight="0" 
			frameBorder="0" 
			scrolling="auto"
			title="Dialog Title"
			src="{$T_XPAY_CIELO_URL_AUTENTICACAO}"
		> </iframe>
	</div>
{/if}

{$T_MODULE_XPAY_CIELO_FORM.javascript}
<form {$T_MODULE_XPAY_CIELO_FORM.attributes}>
	{$T_MODULE_XPAY_CIELO_FORM.hidden}

	<div class="form-field clear">
	<label class="form-label clear" for="textfield">{$smarty.const.__XPAY_CIELO_PARCELAS}<span class="required">*</span></label>
		{foreach item="item" key="key" from=$T_MODULE_XPAY_CIELO_FORM.qtde_parcelas} 
			{$item.html}
		{/foreach}
	</div>
	
	<div class="form-field clear buttons">
		<button class="" type="submit" name="{$T_MODULE_XPAY_CIELO_FORM.xpay_cielo_submit.name}" value="{$T_MODULE_XPAY_CIELO_FORM.xpay_cielo_submit.value}">
			<!-- <img width="19" height="19" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">  -->
			<img 
				src = "images/others/transparent.png"
				class="imgs_cont sprite16 sprite16-go_into"
				border = "0"
			/>							
							
			<span>{$T_MODULE_XPAY_CIELO_FORM.xpay_cielo_submit.value}</span>
		</button>
	</div>
</form>