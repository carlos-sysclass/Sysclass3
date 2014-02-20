{capture name="t_quick_mails_edit_form"}
	{$T_QUICK_MAILS_EDIT_FORM.javascript}
	<form {$T_QUICK_MAILS_EDIT_FORM.attributes}>
		{$T_QUICK_MAILS_EDIT_FORM.hidden|@implode}
			<div class="grid_24">
				<label>{$T_QUICK_MAILS_EDIT_FORM.group_id.label}</label>
				{$T_QUICK_MAILS_EDIT_FORM.group_id.html}
			</div>
			<div class="grid_24">
				<label>{$T_QUICK_MAILS_EDIT_FORM.title.label}</label>
				{$T_QUICK_MAILS_EDIT_FORM.title.html}
			</div>
			<div class="grid_24">
				<label>{$T_QUICK_MAILS_EDIT_FORM.qm_type.label}</label>
				{$T_QUICK_MAILS_EDIT_FORM.qm_type.html}
			</div>
			
			<div class="clear"></div>
			<div align="center" style="margin-top: 20px;" class="buttons">
				<button value="{$T_QUICK_MAILS_EDIT_FORM._save.label}" name="{$T_QUICK_MAILS_EDIT_FORM._save.name}" type="submit" class="form-button">
					<img width="29" height="29" src="themes/sysclass3/images/transp.png">
					<span>{$T_QUICK_MAILS_EDIT_FORM._save.label}</span>
				</button>
			</div>
	</form>
{/capture}
{sC_template_printBlock
	title 			= $smarty.const.__QUICK_MAILS_ADD_ITEM
	data			= $smarty.capture.t_quick_mails_edit_form
	contentclass	= "blockContents "
}