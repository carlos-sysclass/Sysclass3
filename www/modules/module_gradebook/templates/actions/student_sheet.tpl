{capture name = 't_gradebook_sheet_code'}

	<div class="course-lesson-autocomplete-container">
	    <label>Selecione uma disciplina:</label>
	    <input class="course-lesson-autocomplete" value="" />
	</div>
	<div id="gradebook-student-lesson-sheet"></div>

	{foreach item="lesson" from=$T_GRADEBOOK_LESSONS_SCORES}
		{if $lesson.columns|@count > 0}
			{capture name="t_gradebook_lesson"}
			<table class="style1">
				<thead>
					<tr>
						<th>{$smarty.const._GRADEBOOK_COLUMN_NAME}</th>
						<th style="text-align: center;">{$smarty.const.__GRADEBOOK_WEIGHT}</th>
						<th style="text-align: center;">{$smarty.const.__GRADEBOOK_FINAL_SCORE}</th>
					</tr>
				</thead>
				<tbody>
					{foreach item="group" from=$lesson.groups}
						{assign var="group_id" value=$group.id}
						
						{assign var="column_group_children" value=false}
						{foreach item="column" from=$lesson.columns}
							{assign var="column_id" value=$column.id}
							{if $group.id == $column.group_id}
								<tr>
									<td>{$column.name}</td>
									<td align="center">{$column.weight}</td>
									<td align="center">{$lesson.scores.columns[$column_id]}</td>
								</tr>
								{assign var="column_group_children" value=true}
							{/if}
						{/foreach}
						{if $column_group_children}
							<tr>
								<th colspan="2">{$group.name}</th>
								<th style="text-align: center;">{$lesson.scores.groups[$group_id]}</th>
							</tr>
						{/if}
					{/foreach}
					
				</tbody>
				<tfoot>
					<tr>
						<th>{$smarty.const.__GRADEBOOK_TOTAL}</th>
						<th style="text-align: center;">{$smarty.const.__GRADEBOOK_WEIGHT}</th>
						<th style="text-align: center;">{$lesson.scores.final_score}</th>
					</tr>
				</tfoot>
			</table>
			{/capture}
			{eF_template_printBlock title=$lesson.name data=$smarty.capture.t_gradebook_lesson image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}
		{else}
			
		{/if}		
		
	{/foreach}
{/capture}
{eF_template_printBlock title=$smarty.const._GRADEBOOK_NAME data=$smarty.capture.t_gradebook_sheet_code image=$T_GRADEBOOK_BASELINK|cat:'images/gradebook_logo.png' absoluteImagePath = 1}

	