{capture name="t_edit_scheduled_users"}
<div class="blockContents form-list-itens">
	<!-- 
	<div class="grid_12">
		<label>{$smarty.const.__XCONTENT_START_DATE}:</label>
		<span>dasdas</span>
	</div>
	<div class="grid_12">
		<label>{$smarty.const.__XCONTENT_END_DATE}:</label>
		<span>dasdas</span>
	</div>
	 -->
	<div class="grid_24">
		<label>{$smarty.const.__XCONTENT_SCOPE}:</label>
		<span>{$T_XCONTENT_SCHEDULE.scope}</span>
	</div>
	
	{if $T_XCONTENT_SCOPE_FIELDS|@count > 0}
		{foreach item="field" from=$T_XCONTENT_SCOPE_FIELDS}
			<div class="grid_24">
				<label>{$T_XCONTENT_SCHEDULE.$field.label}:</label>
				<span>{$T_XCONTENT_SCHEDULE.$field.value}</span>
			</div>
		{/foreach}			
	{/if}
	<div class="grid_24">
		<label>{$msarty.const.__XCONTENT_CONTENT}</label>
	</div>
	
	{capture name="t_table_header"}
	<thead>
		<tr class="topTitle">
			<th width="15%" align="center">{$smarty.const.__XCONTENT_DATETIME}</th>
			<th align="center">{$smarty.const.__XCONTENT_USER}</th>
			<th align="center">{$smarty.const.__XCONTENT_COURSE}</th>
			<th align="center">{$smarty.const.__XCONTENT_LESSON}</th>
			<th align="center">{$smarty.const.__XCONTENT_CONTENT_NAME}</th>
			<th width="10%" align="center">{$smarty.const.__XCONTENT_USER_SCHEDULE_LIBERATION}</th>
		</tr>
	</thead>
	{/capture}
	
	{foreach name="schedule_it" key="datetime" item="content_users" from=$T_XCONTENT_SCHEDULE_TIME_USERS}
		{assign var="t_table_body" value=t_table_body_`$smarty.foreach.schedule_it.iteration`}
		{capture name=$t_table_body}
			{foreach item="scheduled_user" from=$content_users}
				<tr>
					<td align="center">#filter:datetime-{$datetime}#</td>
					<td>{$scheduled_user.name} {$scheduled_user.surname}</td>
					<td>{$scheduled_user.course|sC_truncate:40}</td>
					<td>{$scheduled_user.lesson|sC_truncate:40}</td>
					<td>{$scheduled_user.content|sC_truncate:40}</td>
					<td align="center">
						<input 
							type="checkbox" 
							name="xcontent_user_liberation" 
							value="0" 
							{if $scheduled_user.liberation == 1}checked="checked"{/if}
							onclick="doAjaxContentScheduledLiberation({$scheduled_user.schedule_id}, {$scheduled_user.user_id}, {$scheduled_user.content_id}, this);"	
						/>
						
					</td>
				</tr>
			{/foreach}
		{/capture}
	{/foreach}
	
	
	<h4>{$smarty.const.__XCONTENT_USER_WITH_SCHEDULED_REGISTERED}</h4>
	
	<table class="_XCONTENT_SCHEDULED_USERS_LIST static">
	{$smarty.capture.t_table_header}
	{section name="t_body_data" loop=$smarty.foreach.schedule_it.iteration }
		 {assign var="t_table_body" value=t_table_body_`$smarty.section.t_body_data.iteration`}
		 {$smarty.capture.$t_table_body}
	{/section}
	</table>
	
	
	
	{foreach name="schedule_it" key="datetime" item="content_users" from=$T_XCONTENT_NOSCHEDULE_TIME_USERS}
			<h4>{$smarty.const.__XCONTENT_USER_WITH_NO_SCHEDULED_REGISTERED}</h4>
			<table class="_XCONTENT_SCHEDULED_USERS_LIST static">
				{$smarty.capture.t_table_header}
				<tbody>
				
					{foreach item="scheduled_user" from=$content_users}
					<tr>
						<td align="center">n/a</td>
						<td>{$scheduled_user.name} {$scheduled_user.surname}</td>
						<td>{$scheduled_user.course|sC_truncate:60}</td>
						<td>{$scheduled_user.lesson|sC_truncate:60}</td>
						<td>{$scheduled_user.content|sC_truncate:60}</td>
						<td><input type="checkbox" name="xcontent_user_liberation" value="0" /></td>
					</tr>
					{/foreach}
				</tbody>
			</table>
	{/foreach}
</div>
{/capture}
{sC_template_printBlock
	title 			= $smarty.const.__XCONTENT_VIEW_SCHEDULED_USERS
	data			= $smarty.capture.t_edit_scheduled_users
	contentclass	= "blockContents"
}