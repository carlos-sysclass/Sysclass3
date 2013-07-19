{capture name="t_quick_mails_list"}	
	<a href="{$T_QUICK_MAILS_BASEURL}&action=add_list_item">{$smarty.const.__QUICK_MAILS_ADD_NEW_ITEM}</a>
	
	<table class="style1">
		<thead>
			<tr>
				<th>{$smarty.const.__QUICK_MAILS_FIELD_GROUP}</th>
				<th>{$smarty.const.__QUICK_MAILS_FIELD_TITLE}</th>
				<th>{$smarty.const.__QUICK_MAILS_FIELD_TYPE}</th>
				<th>{$smarty.const.__QUICK_MAILS_FIELD_ACTION}</th>				
			</tr>
		</thead>
		<tbody>
		{foreach item="item" from=$T_LIST}
			<tr>
				<td>
					{if $item.group}
						{$item.group}
					{else}
						{$smarty.const.__QUICK_MAILS_NOGROUP_SET}
					{/if}
				</td>
				<td>{$item.title}</td>
				<td>{$T_LIST_TYPES[$item.qm_type]}</td>
				<td>
					[<a href="{$T_QUICK_MAILS_BASEURL}&action=edit_list_item&item_id={$item.id}">{$smarty.const.__QUICK_MAILS_EDIT_ITEM}</a>]
					[<a href="{$T_QUICK_MAILS_BASEURL}&action=remove_list_item&item_id={$item.id}">{$smarty.const.__QUICK_MAILS_DELETE_ITEM}</a>]
				</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
{/capture}

{sC_template_printBlock 
	title=$smarty.const.__QUICK_MAILS_LIST
	data=$smarty.capture.t_quick_mails_list
	contentclass="blockContents"
}
