{capture name="t_quick_mails_list"}	
	<table class="style1 quickMailDataTable">
		<thead>
			<tr>
				<th>{$smarty.const.__QUICK_MAILS_FIELD_LOGIN}</th>
				<th>{$smarty.const.__QUICK_MAILS_FIELD_USER_TYPE}</th>
				<th>{$smarty.const.__QUICK_MAILS_FIELD_EMAIL}</th>
				<th>{$smarty.const.__QUICK_MAILS_FIELD_ACTION}</th>				
			</tr>
		</thead>
		<tbody>
		{foreach key = 'key' item = 'user' from = $T_QUICK_MAILS_USERS}
        	<tr id="row_{$user.login}">
				<td>
					<a href = "{$smarty.server.PHP_SELF}?ctg=users&edit_user={$user.login}" class = "editLink">
						<span id="column_{$user.login}" {if !$user.active}style="color:red;"{/if}>#filter:login-{$user.login}#</span>
					</a>
				</td>
                <td>{if $user.user_types_ID}{$T_ROLES[$user.user_types_ID]}{else}{$T_ROLES[$user.user_type]}{/if}</td>
                <td>{$user.email}</td>
                <td class = "centerAlign">
    				<input type = "checkbox" id = "{$lesson.id}" onclick = "_sysclass('load', 'quick_mails').toggleUserInRecipientList('{$smarty.get.item_id}', '{$user.id}', this);" {if $user.recipient_id}checked{/if}>{if $user.recipient_id}<span style = "display:none">checked</span>{/if} {*Span is for sorting here*}
     			</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
{/capture}

{sC_template_printBlock 
	title=$smarty.const.__QUICK_MAILS_RECIPIENT_LIST
	data=$smarty.capture.t_quick_mails_list
	contentclass="blockContents"
}