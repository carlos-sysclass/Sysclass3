{capture name="t_view_scheduled"}
{if $T_EXTENDED_USERTYPE == "administrator" || $T_CURRENT_USER->moduleAccess.xcontent == 'change'}
	<div class="headerTools">
		<span>
			<img class="sprite16 sprite16-add" src="images/others/transparent.gif">
	    	<a href="{$T_XCONTENT_BASEURL}&action=new_schedule">Cadastrar novo agendamento</a>
		</span>
	</div>
{/if}
{foreach item="scope" from=$T_XCONTENT_SCOPES}
	{if $T_XCONTENT_SCHEDULES[$scope.id]|@count > 0}
		<div class="clear"></div>
		<h3>{$smarty.const.__XCONTENT_SCOPE}: {$scope.name}</h3>
	
		<table class="_XCONTENT_SCHEDULE_LIST static">
			<thead>
				<tr class="topTitle">
					<th style="text-align: center;">{$smarty.const.__XCONTENT_COURSE_OR_COURSES}</th>
					<th style="text-align: center;">{$smarty.const.__XCONTENT_CONTENT_OR_CONTENTS}</th>
					<th style="text-align: center;">Período</th>
					{foreach item="field" from=$scope.fields}
						<th style="text-align: center;">{$field.label}</th>
					{/foreach}
					<th style="text-align: center;">Opções</th>
				</tr>
			</thead>
			<tbody>
				{foreach item="schedule" from=$T_XCONTENT_SCHEDULES[$scope.id]}
				<tr>
					<td>{$schedule.total_courses} {$smarty.const.__XCONTENT_COURSE_OR_COURSES}</td>
					<td>{$schedule.total_contents} {$smarty.const.__XCONTENT_CONTENT_OR_CONTENTS}</td>
					
					<td align="center">
						{if $schedule.start}
							#filter:date-{$schedule.start}#
							{if $schedule.end}
								&raquo; #filter:date-{$schedule.end}#
							{else}
								&raquo; &#8734;
							{/if}
						{elseif $schedule.end}
							&#8734; &raquo; #filter:date-{$schedule.end}#
						{else}
							N/A
						{/if}
						
					</td>
					{foreach item="field" from=$scope.fields}
						{assign var="field_name" value="`$field.name`"}
						<td align="center">{$schedule.$field_name.value|eF_truncate:40}</td>
					{/foreach}			
					<td>
						<div>
							{if $T_EXTENDED_USERTYPE == "administrator" || $T_CURRENT_USER->moduleAccess.xcontent == 'change'}
							<button class="form-icon contentScheduleEdit" onclick="window.location.href = '{$T_XCONTENT_BASEURL}&action=edit_schedule_times&xschedule_id={$schedule.id}'; return false;">
								<img class="sprite16 sprite16-edit" src="images/others/transparent.gif" />
							</button>
							{/if}
							<button class="form-icon contentScheduleEdit" onclick="window.location.href = '{$T_XCONTENT_BASEURL}&action=view_scheduled_users&xschedule_id={$schedule.id}'; return false;">
								<img class="sprite16 sprite16-calendar" src="images/others/transparent.gif" />
							</button>
							<button class="form-icon contentScheduleDelete" onclick="deleteSchedule({$schedule.id}); return false;">
								<img class="sprite16 sprite16-close" src="images/others/transparent.gif" />
							</button>
						</div>
					
					</td>
				</tr>
				{/foreach}	
			</tbody>
		</table>
	{/if}
{/foreach}
{/capture}
{eF_template_printBlock
	title 			= $smarty.const.__XCONTENT_VIEW_SCHEDULED
	data			= $smarty.capture.t_view_scheduled
	contentclass	= "blockContents"
}