{capture name = 't_gradebook_professor_code'}
<table class="sortedTable style1" style="width:100%">
	<thead>
		<tr>
			<th class="topTitle">{$smarty.const._GRADEBOOK_STUDENT_NAME}</th>
			<th class="topTitle">{$smarty.const._USERTYPE}</th>
			{foreach name = 'columns_loop' key = "id" item = "column" from = $T_GRADEBOOK_LESSON_COLUMNS}
			<th class="topTitle rightAlign">{$column.name} ({$smarty.const._GRADEBOOK_COLUMN_WEIGHT_DISPLAY}: {$column.weight})
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
			<th class="topTitle centerAlign">{$smarty.const._GRADEBOOK_SCORE}</th>
			<!-- <th class="topTitle centerAlign noSort">{$smarty.const._GRADEBOOK_GRADE}</th>  -->
			<th class="topTitle centerAlign noSort">{$smarty.const._GRADEBOOK_PUBLISH}</th>
		</tr>
	</thead>
{foreach name = 'users_loop' key = "id" item = "user" from = $T_GRADEBOOK_LESSON_USERS}
	<tr id="row_{$user.uid}" class="{cycle values = "oddRowColor, evenRowColor"} {if !$user.active}ui-state-disabled{/if}">
		<td>#filter:login-{$user.users_LOGIN}#</td>
		<td class="centerAlign">{$user.userrole}</td>
{foreach name = 'grades_loop' key = "id_" item = "grade" from = $user.grades}
		<td class="rightAlign">
			<input type="text" id="grade_{$grade.gid}" value="{$grade.grade}" size="5" maxlength="5" />
			<img class="ajaxHandle" src="{$T_GRADEBOOK_BASELINK|cat:'images/success.png'}" title="{$smarty.const._SAVE}" alt="{$smarty.const._SAVE}" onclick="changeGrade('{$grade.gid}', this)"/>
		</td>

{/foreach}
		<td class="centerAlign">{$user.score}</td>
<!-- 		<td class="centerAlign">{$user.grade}</td>  -->
		
		
		<td class="centerAlign">
			<input class="inputCheckbox" type="checkbox" name="checked_{$user.uid}" id="checked_{$user.uid}" onclick="publishGradebook('{$user.uid}', this);" {if ($user.publish == 1)} checked="checked"{/if} />
		</td>
	</tr>
{foreachelse}
	<tr class="defaultRowHeight oddRowColor">
		<td class="emptyCategory" colspan="100%">{$smarty.const._NODATAFOUND}</td>
	</tr>
{/foreach}
</table>
{/capture}

{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_professor_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}	