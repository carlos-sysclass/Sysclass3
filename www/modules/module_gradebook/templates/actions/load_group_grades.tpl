{capture name = 't_gradebook_professor_code'}
<table class="style1">
	<thead>
		<tr>
			<th>{$smarty.const._GRADEBOOK_STUDENT_NAME}</th>
			{foreach name = 'columns_loop' key = "id" item = "column" from = $T_GRADEBOOK_LESSON_COLUMNS}
			<th>{$column.name} ({$smarty.const._GRADEBOOK_COLUMN_WEIGHT_DISPLAY}: {$column.weight})
				{if $column.refers_to_type != 'real_world'}
				<!-- <a href="{$T_GRADEBOOK_BASEURL}&import_grades={$column.id}" onclick="return confirm('{$smarty.const._IRREVERSIBLEACTIONAREYOUSURE}')"><img src="{$T_GRADEBOOK_BASELINK}images/import.png" alt="{$smarty.const._GRADEBOOK_IMPORT_GRADES}" title="{$smarty.const._GRADEBOOK_IMPORT_GRADES}" border="0"></a>  -->
				<a href="javascript: _sysclass('load', 'gradebook').importStudentsGrades({$T_GRADEBOOK_GROUP_ID}, {$column.id})" onclick="return confirm('{$smarty.const._IRREVERSIBLEACTIONAREYOUSURE}')"><img src="{$T_GRADEBOOK_BASELINK}images/import.png" alt="{$smarty.const._GRADEBOOK_IMPORT_GRADES}" title="{$smarty.const._GRADEBOOK_IMPORT_GRADES}" border="0"></a>
				{/if}
				<!-- 
				<td class="topTitle leftAlign noSort" style="width:16px;">
					<a href="{$T_GRADEBOOK_BASEURL}&delete_column={$column.id}" onclick="return confirm('{$smarty.const._IRREVERSIBLEACTIONAREYOUSURE}')"><img src="{$T_GRADEBOOK_BASELINK}images/delete.png" alt="{$smarty.const._GRADEBOOK_DELETE_COLUMN}" title="{$smarty.const._GRADEBOOK_DELETE_COLUMN}" border="0"></a>
				</td>
				 -->
			</th>
			{/foreach}
			<th>{$smarty.const.__GRADEBOOK_GROUP_SCORE}</th>
			<th>{$smarty.const.__GRADEBOOK_FINAL_SCORE}</th>
			<!-- <th class="topTitle centerAlign noSort">{$smarty.const._GRADEBOOK_GRADE}</th>  -->
			<th>{$smarty.const._GRADEBOOK_PUBLISH}</th>
		</tr>
	</thead>
	<tbody>
		{foreach name = 'users_loop' key = "id" item = "user" from = $T_GRADEBOOK_LESSON_USERS}
			{assign var="login" value=$user.users_LOGIN}
			<tr id="row_{$user.uid}" class="{cycle values = "oddRowColor, evenRowColor"} {if !$user.active}ui-state-disabled{/if}">
				<td>#filter:login-{$user.users_LOGIN}#</td>
				{foreach name = 'columns_loop' key = "id" item = "column" from = $T_GRADEBOOK_LESSON_COLUMNS}
					{assign var="oid" value=$column.id}
					<td align="center">
						<input type="text" value="{$user.grades[$oid].grade}" size="5" maxlength="5" class="gradebook-grade-input" 
							data-oid="{$column.id}"
							data-login="{$user.users_LOGIN}"
						 />
						<img 
							src="{$T_GRADEBOOK_BASELINK|cat:'images/progress1.gif'}" 
							title="{$smarty.const._SAVE}" 
							alt="{$smarty.const._SAVE}"
							style="visibility: hidden;" />
					</td>
				{/foreach}
		
				<td align="center"><span id="gradebook-group-score-{$T_GRADEBOOK_GROUP_ID}-{$oid}-#filter:sanitizeDOMString-{$user.users_LOGIN}#">
					{if $T_GRADEBOOK_SCORES[$login].groups[$T_GRADEBOOK_GROUP_ID] == -1}
						N/A
					{else}
						{$T_GRADEBOOK_SCORES[$login].groups[$T_GRADEBOOK_GROUP_ID]}
					{/if}
					
				</span></td>
				<td align="center"><span id="gradebook-final-score-{$oid}-#filter:sanitizeDOMString-{$user.users_LOGIN}#">
					{$T_GRADEBOOK_SCORES[$login].final_score} - {$T_GRADEBOOK_SCORES[$login].final_status}
				</span></td>
		<!-- 		<td class="centerAlign">{$user.grade}</td>  -->
				<td align="center">
					<input class="inputCheckbox" type="checkbox" name="checked_{$user.uid}" id="checked_{$user.uid}" onclick="publishGradebook('{$user.uid}', this);" {if ($user.publish == 1)} checked="checked"{/if} />
					<a href = "{$T_GRADEBOOK_BASEURL}&action=student_sheet&xuser_login={$login}">
						<img border = "0" src = "images/16x16/certificate.png" title = "{$smarty.const.__XPAY_VIEW_USER_STATEMENT}" alt = "{$smarty.const.__XPAY_VIEW_USER_STATEMENT}" />
					</a>&nbsp;<br />
					
					
				</td>
			</tr>
		{foreachelse}
		
			<tr class="defaultRowHeight oddRowColor">
				<td class="emptyCategory" colspan="100%">{$smarty.const._NODATAFOUND}</td>
			</tr>
		{/foreach}
	</tbody>
</table>
{/capture}

{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_professor_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}	