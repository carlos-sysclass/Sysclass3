{capture name="t_xcontent_new_schedule_form"}
 	{$T_XCONTENT_NEW_SCHEDULE_FORM.javascript}
	<form {$T_XCONTENT_NEW_SCHEDULE_FORM.attributes}>
		{$T_XCONTENT_NEW_SCHEDULE_FORM.hidden|@implode}
		<div class="grid_24">
			<label>{$T_XCONTENT_NEW_SCHEDULE_FORM.scope_id.label}:</label>
			{$T_XCONTENT_NEW_SCHEDULE_FORM.scope_id.html}
		</div>
		<!-- 
		<div class="grid_24">
			<label>{$T_XCONTENT_NEW_SCHEDULE_FORM.lesson_id.label}:</label>
			{$T_XCONTENT_NEW_SCHEDULE_FORM.lesson_id.html}
		</div>
		-->
		<div class="grid_12">
			<label>{$T_XCONTENT_NEW_SCHEDULE_FORM.start_date.label}:</label>
			{$T_XCONTENT_NEW_SCHEDULE_FORM.start_date.html}
		</div>
		<div class="grid_12">
			<label>{$T_XCONTENT_NEW_SCHEDULE_FORM.end_date.label}:</label>
			{$T_XCONTENT_NEW_SCHEDULE_FORM.end_date.html}
		</div>
		{if $T_XCONTENT_SCOPE_FIELDS|@count > 0}
			{foreach item="field" from=$T_XCONTENT_SCOPE_FIELDS}
				<div class="grid_24">
					<label>{$T_XCONTENT_NEW_SCHEDULE_FORM.$field.label}:</label>
					{$T_XCONTENT_NEW_SCHEDULE_FORM.$field.html}
				</div>
			{/foreach}			
		{/if}
		<!-- 
		{if isset($T_XCONTENT_HTML)}
			<div class="grid_24">
				<label>{$smarty.const.__XCONTENT_CONTENT_NAME}:</label>
				<span id="xcontent_content_tree_text">{$smarty.const.__SELECT_ONE_OPTION}</label>
			</div>
			<div class="grid_24">
				{$T_XCONTENT_HTML}
			</div>
		{/if}
		 -->
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;" align="center">
			<button class="form-button" type="{$T_XCONTENT_NEW_SCHEDULE_FORM.submit_schedule.type}" name="{$T_XCONTENT_NEW_SCHEDULE_FORM.submit_schedule.name}" value="{$T_XCONTENT_NEW_SCHEDULE_FORM.submit_schedule.value}">
				<img width="29" height="29" src="images/transp.png">
				<span>{$T_XCONTENT_NEW_SCHEDULE_FORM.submit_schedule.label}</span>
			</button>
		</div>
	</form>
{/capture}

{eF_template_printBlock 
	title=$smarty.const.__XCONTENT_NEW_SCHEDULE
	data=$smarty.capture.t_xcontent_new_schedule_form
	contentclass="blockContents"
}
