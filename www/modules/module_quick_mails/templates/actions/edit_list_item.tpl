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
				<strong>{$T_QUICK_MAILS_EDIT_FORM.qm_type.html}</strong>
				{if $T_ITEM_TYPE != 'link'}
					[<a href="{$T_QUICK_MAILS_BASEURL}&action=edit_item_destination&item_id={$smarty.get.item_id}">{$smarty.const.__QUICK_MAILS_EDIT_ITEM_DESTINATION}</a>]
				{/if}
			</div>
			{if $T_ITEM_TYPE == 'link'}
				<div class="grid_24">
					<label>{$T_QUICK_MAILS_EDIT_FORM.link.label}</label>
					{$T_QUICK_MAILS_EDIT_FORM.link.html}
				</div>
			{/if}
			<div style="margin-bottom: 10px;"></div>
			<h4>{$smarty.const.__XENTIFY_ITEM_VISIBILITY}
				[<a href="{$T_QUICK_MAILS_BASEURL}&action=add_new_scope&item_id={$smarty.get.item_id}">{$smarty.const.__QUICK_MAILS_ADD_NEW_SCOPE}</a>]
			</h4>
			<ul class="default-list" style="margin-top: 10px;">
				{foreach item="item" from=$T_ITEM_SCOPES}
				<li class="event-conf list-item">
					<span style="margin-left: 10px;">{$item.description}</span>
					<div class="list-item-image">
						<a href="{$T_QUICK_MAILS_BASEURL}&action=delete_scope&item_id={$smarty.get.item_id}&scope_id={$item.codigo}">
							<img src="images/others/transparent.png" class="imgs_cont sprite16 sprite16-close" title="{$item.description}" alt="{$item.description}"border="0" />
						</a>
					</div>
				</li>
				{/foreach}
			</ul>
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
	title 			= $smarty.const.__QUICK_MAILS_EDIT_ITEM
	data			= $smarty.capture.t_quick_mails_edit_form
	contentclass	= "blockContents "
}