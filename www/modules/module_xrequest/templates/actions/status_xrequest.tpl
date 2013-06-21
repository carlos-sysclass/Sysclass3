{capture name="t_xrequest_body"}
	{include file="$T_XREQUEST_BASEDIR/templates/actions/xrequest_menu.tpl"}

<div class="clear"></div>


<div class="blockContents">
 	{$T_XREQUEST_STATUS_FORM.javascript}
	<form {$T_XREQUEST_STATUS_FORM.attributes}>
		{$T_XREQUEST_STATUS_FORM.hidden}
		<div class="grid_12">

			<label>{$T_XREQUEST_STATUS_FORM.name.label}:</label>
			{$T_XREQUEST_STATUS_FORM.name.html}
			<br />
			<label>{$T_XREQUEST_STATUS_FORM.status.label}:</label>
			{$T_XREQUEST_STATUS_FORM.status.html}
		</div>
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;">
			<input class="button_colour round_all" type="submit" name="{$T_XREQUEST_STATUS_FORM.submit_xrequest.name}" value="{$T_XREQUEST_STATUS_FORM.submit_xrequest.value}">
		</div>
		<div class="clear"></div>
	</form>
</div>

{/capture}

{eF_template_printBlock
	title 			= $smarty.const._XREQUEST_LIST_STATUS
	data			= $smarty.capture.t_xrequest_body
}