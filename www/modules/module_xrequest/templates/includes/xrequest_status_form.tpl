{capture name="t_xrequest_body"}
{include file="$T_MODULE_XREQUEST_BASEDIR/templates/includes/xrequest_menu.tpl"}

<div class="clear"></div>


<div class="blockContents">
 	{$T_XREQUEST_BASIC_FORM.javascript}
	<form {$T_XREQUEST_BASIC_FORM.attributes}>
		{$T_XREQUEST_BASIC_FORM.hidden}
		<div class="grid_12">

			<label>{$T_XREQUEST_BASIC_FORM.name.label}:</label>
			{$T_XREQUEST_BASIC_FORM.name.html}
			<br />
			<label>{$T_XREQUEST_BASIC_FORM.status.label}:</label>
			{$T_XREQUEST_BASIC_FORM.status.html}
		</div>
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;">
			<button class="button_colour round_all" type="submit" name="{$T_MODULE_XREQUEST_BASIC_FORM.submit_XREQUEST.name}" value="{$T_MODULE_XREQUEST_BASIC_FORM.submit_XREQUEST.value}">
				<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
				<span>{$T_XREQUEST_BASIC_FORM.submit_XREQUEST.value}</span>
			</button>
		</div>
		<div class="clear"></div>
	</form>
</div>
{/capture}

{eF_template_printBlock
	title 			= $smarty.const._XREQUEST_NEW_STATUS
	data			= $smarty.capture.t_xrequest_body
}