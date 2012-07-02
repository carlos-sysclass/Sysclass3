{capture name="t_xcontent_schedule_times"}
<form action="{$smarty.server.REQUEST_URI}" method="post">
	<input type="hidden" name="xschedule_id" value="{$T_XCONTENT_SCHEDULE_ID}" />
	<input type="hidden" name="xcontent_id" value="{$T_XCONTENT_CONTENT_ID}" />
	<div class="form-list-itens">
		<div class="grid_24">
			<label>{$smarty.const.__XCONTENT_COURSE}:</label>
			<span>{$T_XCONTENT_CONTENT.course}</span>
		</div>
		<div class="grid_24">
			<label>{$smarty.const.__XCONTENT_LESSON}:</label>
			<span>{$T_XCONTENT_CONTENT.lesson}</span>
		</div>
		<div class="grid_24">
			<label>{$smarty.const.__XCONTENT_CONTENT_NAME}:</label>
			<span>{$T_XCONTENT_CONTENT.content}</span>
		</div>
		<br />

		<div class="grid_24">
			{include
				file="$T_XCONTENT_BASEDIR/templates/includes/schedule.select_times.list.tpl"
				T_XCONTENT_SCHEDULES=$T_XCONTENT_SCHEDULE
				T_XCONTENT_WEEKNAMES=$T_XCONTENT_WEEKNAMES
				T_XCONTENT_SELECTED_OPTION=$T_XCONTENT_SELECTED_OPTION
			}
		</div>
		<div class="clear"></div>
		<div class="grid_24" style="margin-top: 20px;" align="center">
			<button class="form-button icon-add flatButton" type="submit" name="{$T_MODULE_XUSER_BASIC_FORM.submit_xuser.name}" value="{$T_MODULE_XUSER_BASIC_FORM.submit_xuser.value}">
				<img width="29" height="29" src="images/transp.png">
				<span>{$smarty.const._SAVE}</span>
			</button>
		</div>
	</div>
</form>
{/capture}

{eF_template_printBlock
	title 			= $smarty.const.__XCONTENT_SET_SCHEDULE_TIMES
	data			= $smarty.capture.t_xcontent_schedule_times
	contentclass	= ""
	class			= ""
	options			= ""
}
