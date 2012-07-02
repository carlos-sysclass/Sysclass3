{$T_XCONTENT_SELECT_FORM.javascript}
<form {$T_XCONTENT_SELECT_FORM.attributes}>
	{$T_XCONTENT_SELECT_FORM.hidden|@implode}
	<div class="blockContents" style="width: 100%;">
		<div>
			<label>{$T_XCONTENT_SELECT_FORM.lesson_id.label}:</label>
			{$T_XCONTENT_SELECT_FORM.lesson_id.html}
		</div>
		<div>
			<label>{$smarty.const.__XCONTENT_CONTENT_NAME}:</label>
		</div>
		<div style="display: block" id="xcontent_content_tree_container">
		</div>
		<div>
			<label>{$T_XCONTENT_SELECT_FORM.required.label}:</label>
			{$T_XCONTENT_SELECT_FORM.required.html}
		</div>
		<div class="clear"></div>
		<div style="margin-top: 20px;" align="center">
			<button class="form-button" type="{$T_XCONTENT_SELECT_FORM.submit_schedule.type}" name="{$T_XCONTENT_SELECT_FORM.submit_schedule.name}" value="{$T_XCONTENT_SELECT_FORM.submit_schedule.value}">
				<img width="29" height="29" src="images/transp.png">
				<span>{$T_XCONTENT_SELECT_FORM.submit_schedule.label}</span>
			</button>
		</div>
	</div>
</form>