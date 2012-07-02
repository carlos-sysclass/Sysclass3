{$T_XENROLLMENT_HISTORY_FORM.javascript}
<form {$T_XENROLLMENT_HISTORY_FORM.attributes}>
	{$T_XENROLLMENT_HISTORY_FORM.hidden}
	
	{$T_XENROLLMENT_HISTORY_FORM.body.html}
	<div class="clear"></div>
	
	<div class="grid_16" style="margin-top: 20px; margin-bottom: 20px;">
		<button class="button_colour round_all" type="submit" name="{$T_XENROLLMENT_HISTORY_FORM.submit_xenrollment.name}" value="{$T_XENROLLMENT_HISTORY_FORM.submit_xenrollment.value}">
			<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
			<span>{$T_XENROLLMENT_HISTORY_FORM.submit_xenrollment.value}</span>
		</button>
	</div>		
</form>