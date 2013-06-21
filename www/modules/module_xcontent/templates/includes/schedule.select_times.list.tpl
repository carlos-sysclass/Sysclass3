
<ul class="default-list">
{foreach key = "schedule_date" item = "schedule_week" from=$T_XCONTENT_SCHEDULES}
	{foreach key = "week_day" item = "schedule_item" from=$schedule_week}
		{foreach name = "schedule_iterator" item = "schedule_item_hour" from=$schedule_item}
			{if $smarty.foreach.schedule_iterator.first}		
				<li>Dia #filter:date-{$schedule_item_hour.start}# ({$T_XCONTENT_WEEKNAMES[$week_day]})
					<ul style="list-style: none;">
			{/if}
			
					<li style="border-bottom: none;">
						<input name="xcontent_schedule_item" type="radio" value="{$schedule_item_hour.index}" {if $T_XCONTENT_SELECTED_OPTION == $schedule_item_hour.index}checked="checked"{/if} >
						<span style="vertical-align: middle">
						{$schedule_item.selected_option}
						#filter:time-{$schedule_item_hour.start}# às #filter:time-{$schedule_item_hour.end}#
						</span>
					</li>
			{if $smarty.foreach.schedule_iterator.last}
				</ul>
			</li>
			{/if}
		{/foreach}
	{/foreach}
{/foreach}
</ul>
