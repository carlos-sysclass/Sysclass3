{capture name="t_xrequest_body"}
{include file="$T_XREQUEST_BASEDIR/templates/includes/xrequest_menu.tpl"}
<div class="clear"></div>


<div class="blockContents">
 	{$T_XREQUEST_BASIC_FORM.javascript}
	<form {$T_XREQUEST_BASIC_FORM.attributes}>
		{$T_XREQUEST_BASIC_FORM.hidden}
		
			<label>{$T_XREQUEST_BASIC_FORM.name.label}:</label>
			{$T_XREQUEST_BASIC_FORM.name.html}
		
			<br />
	
			<label>{$T_XREQUEST_BASIC_FORM.valor.label}:</label>
			{$T_XREQUEST_BASIC_FORM.valor.html}
			<br />
	
		
			<label>{$T_XREQUEST_BASIC_FORM.dias_prazo.label}:</label>
			{$T_XREQUEST_BASIC_FORM.dias_prazo.html}
			<br />
	
			<label>{$T_XREQUEST_BASIC_FORM.email.label}:</label>
			{$T_XREQUEST_BASIC_FORM.email.html}
			<br />
		
			<label>{$T_XREQUEST_BASIC_FORM.status.label}:</label>
			{$T_XREQUEST_BASIC_FORM.status.html}
		
		
		<div class="clear"></div>

		<div class="grid_16" style="margin-top: 20px;">
			<input class="button_colour round_all"  type="submit" name="{$T_XREQUEST_BASIC_FORM.submit_xrequest.name}" value="{$T_XREQUEST_BASIC_FORM.submit_xrequest.value}">
		</div>
		
		<div class="clear"></div>
	</form>
</div>
{/capture}

{sC_template_printBlock
	title 			= $smarty.const.__XREQUEST_TYPES
	data			= $smarty.capture.t_xrequest_body
}