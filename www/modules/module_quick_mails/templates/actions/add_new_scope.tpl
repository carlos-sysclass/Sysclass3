{capture name="T_QUICK_MAIL_NEW_SCOPE_FORM"}
 	{$T_QUICK_MAIL_NEW_SCOPE_FORM.javascript}
	<form {$T_QUICK_MAIL_NEW_SCOPE_FORM.attributes}>
		{$T_QUICK_MAIL_NEW_SCOPE_FORM.hidden|@implode}
		<div class="grid_24">
			<label>{$T_QUICK_MAIL_NEW_SCOPE_FORM.scope_id.label}:</label>
			{$T_QUICK_MAIL_NEW_SCOPE_FORM.scope_id.html}
		</div>
		<!-- 
		<div class="grid_24">
			<label>{$T_QUICK_MAIL_NEW_SCOPE_FORM.lesson_id.label}:</label>
			{$T_QUICK_MAIL_NEW_SCOPE_FORM.lesson_id.html}
		</div>
		-->
		{if $T_QUICK_MAIL_SCOPE_FIELDS|@count > 0}
			{foreach item="field" from=$T_QUICK_MAIL_SCOPE_FIELDS}
				<div class="grid_24">
					<label>{$T_QUICK_MAIL_NEW_SCOPE_FORM.$field.label}:</label>
					{$T_QUICK_MAIL_NEW_SCOPE_FORM.$field.html}
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
			<button class="form-button" type="{$T_QUICK_MAIL_NEW_SCOPE_FORM.submit_scope.type}" name="{$T_QUICK_MAIL_NEW_SCOPE_FORM.submit_scope.name}" value="{$T_QUICK_MAIL_NEW_SCOPE_FORM.submit_scope.value}">
				<img width="29" height="29" src="images/transp.png">
				<span>{$T_QUICK_MAIL_NEW_SCOPE_FORM.submit_scope.label}</span>
			</button>
		</div>
	</form>
{/capture}

{sC_template_printBlock 
	title=$smarty.const.__QUICK_MAILS_NEW_SCOPE
	data=$smarty.capture.T_QUICK_MAIL_NEW_SCOPE_FORM
	contentclass="blockContents"
}
