{*if $T_CURRENT_USER->coreAccess.calendar != 'hidden' && $T_CONFIGURATION.disable_calendar != 1*}
	{capture name = "moduleCalendar"}
		{capture name='t_calendar_code'}
			{if $smarty.session.s_type == "administrator"}
				{assign var="calendar_ctg" value = "users&edit_user=`$smarty.get.edit_user`"}
			{else}
				{assign var="calendar_ctg" value = "personal"}
			{/if}
			{eF_template_printCalendar ctg=$calendar_ctg events=$T_CALENDAR_EVENTS timestamp=$T_VIEW_CALENDAR}
		
		{/capture}
		{*assign var="calendar_title" value = `$smarty.const._CALENDAR`&nbsp;(#filter:timestamp-`$T_VIEW_CALENDAR`#)*}
		{*eF_template_printBlock title=$calendar_title data=$smarty.capture.t_calendar_code image='32x32/calendar.png' options=$T_CALENDAR_OPTIONS link=$T_CALENDAR_LINK*}
	{/capture}
{*/if*}
{$smarty.capture.t_calendar_code}