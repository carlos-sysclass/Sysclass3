{capture name="t_xrequest_body"}
{include file="$T_XREQUEST_BASEDIR/templates/includes/xrequest_menu.tpl"}
<div class="clear"></div>

<div class="blockContents">
 	{$T_XREQUEST_NEW_FORM.javascript}
	<form {$T_XREQUEST_NEWREQUEST_FORM.attributes}>
		{$T_XREQUEST_NEWREQUEST_FORM.hidden}
		<div class="grid_12">
			{$T_XREQUEST_NEWREQUEST_FORM.user_ID.html}
			<label>{$T_XREQUEST_NEWREQUEST_FORM.type.label}:</label>
			{$T_XREQUEST_NEWREQUEST_FORM.type.html}
			<br />
			<label>{$T_XREQUEST_NEWREQUEST_FORM.desc.label}:</label>
			{$T_XREQUEST_NEWREQUEST_FORM.desc.html}
			
			
		</div>
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;">
			<input class="button_colour round_all" type="submit" name="{$T_XREQUEST_NEWREQUEST_FORM.submit_xrequest.name}" value="{$T_XREQUEST_NEWREQUEST_FORM.submit_xrequest.value}">
		</div>
		<div class="clear"></div>
	</form>
</div>
{/capture}

{sC_template_printBlock
	title 			= $smarty.const.__XREQUEST_NEW_REQUEST
	data			= $smarty.capture.t_xrequest_body
}