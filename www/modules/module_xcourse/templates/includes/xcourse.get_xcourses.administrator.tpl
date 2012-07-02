{capture name="t_xcourses_table"}
	<div class="clear"></div>
	<div class="grid_16 box border" style="margin-top: 15px;">
		<div class="headerTools">
			<!-- 
			<span>
				<a class="sendInvoiceByEmail" href="javascript: void(0);">
					<img alt="_MODULE_PAGAMENTO_SEND_EMAIL_BOLETO" src="/themes/sysclass/images/icons/small/grey/mail.png">
					(Re)Enviar boleto por e-mail
				</a>
			</span>
			 -->
			{if $T_MODULE_XCOURSE_CANCHANGE}
            <span>
                    <a href = "administrator.php?ctg=module&op=module_xcourse&action=add_xcourse">
                    <img src = "/themes/sysclass/images/icons/small/grey/user.png" title = "{$smarty.const._NEWCOURSE}" alt = "{$smarty.const._NEWCOURSE}">
                    {$smarty.const._NEWCOURSE}
                    </a>
                </span>
                
            <span>
                    <a href = "javascript: void(0); ">
                    <img src = "/themes/sysclass/images/icons/small/grey/user_comment.png" title = "{$smarty.const._IMPORTCOURSE}" alt = "{$smarty.const._IMPORTCOURSE}">
                    {$smarty.const._IMPORTCOURSE}
                    </a>
                </span>
                {/if}
		</div>
	</div>
	<div class="clear"></div>
	
	<table class="display" id="_XCOURSE_GETCOURSES_DATATABLE">
		<thead>
			<tr>
				<th>{$smarty.const._NAME}</th>
				<th>{$smarty.const._PARTICIPATION}</th>
				<!-- <th>{$smarty.const._LESSONS}</th>  -->
				<th>{$smarty.const._PRICE_PRESENCIAL}</th>
				<th>{$smarty.const._PRICE_WEB}</th>
				<th>{$smarty.const._STARTDATE}</th>
				<th>{$smarty.const._ENDDATE}</th>
				<th>{$smarty.const._CREATED}</th>
				<th>{$smarty.const._OPERATIONS}</th>
				
			</tr>
		</thead>
		<tbody>
			{foreach name = 'xcourses_list' key = 'key' item = 'course' from = $T_XCOURSE_DATASOURCE}
			<tr>
				<td>
					<a href="{$T_XCOURSE_BASEURL}&action=edit_xcourse&xcourse_id={$course.id}" . $entifyRow[ 'login' ]" class = "editLink" {if $course.active != 1}style="color:red;"{/if}>
						{$course.name}
					</a>
				</td>
				<td class="center">{$course.num_students}</td>
				<!-- <td class="center">{$course.num_lessons}</td>  -->
				<td class="center">#filter:currency-{$course.price_presencial}#</td>
				<td class="center">#filter:currency-{$course.price_web}#</td>
				<td class="center">#filter:timestamp-{$course.start_date}#</td>
				<td class="center">#filter:timestamp-{$course.end_date}#</td>
				<td class="center">#filter:timestamp-{$course.created}#</td>
				<td class = "center">
					{if $course.active == 1}
						<button class="green skin_colour round_all activateCourseLink" title="{$smarty.const._DEACTIVATE}" {if $T_MODULE_XCOURSE_CANCHANGE}onclick = "xcourse_activateCourse(this, '{$course.id}')"{/if}>
							<img class = "ajaxHandle" width="16" height="16" alt = "{$smarty.const._DEACTIVATE}" 
							src = "/{$smarty.const.G_CURRENTTHEMEURL}/images/icons/small/white/alert_2.png" />
						</button>    
					{else}
						<button class="red skin_colour round_all activateCourseLink" title = "{$smarty.const._ACTIVATE}" {if $T_MODULE_XCOURSE_CANCHANGE}onclick = "xcourse_activateCourse(this, '{$course.id}')"{/if}>
							<img 
							class = "ajaxHandle" width="16" height="16" alt = "{$smarty.const._ACTIVATE}" 
							src = "/{$smarty.const.G_CURRENTTHEMEURL}/images/icons/small/white/alert_2.png" />
						</button>    
					{/if}
					<button 
						class="skin_colour round_all" 
						title = "{$smarty.const.__XCOURSE_EDIT_CALENDAR}" 
						onclick = "window.location.href = '{$T_XCOURSE_BASEURL}&action=edit_xcourse_calendar&xcourse_id={$course.id}'">
						<img 
							class = "ajaxHandle" width="16" height="16" alt = "{$smarty.const._STATISTICS}" 
							src = "/{$smarty.const.G_CURRENTTHEMEURL}/images/icons/small/white/day_calendar.png" />
					</button>
					{*if !isset($T_DATASOURCE_OPERATIONS) || in_array('progress', $T_DATASOURCE_OPERATIONS)*}
						{*if !$course.has_instances || $T_SORTED_TABLE == 'instancesTable'*}
							<!--     <a href = "{$smarty.server.PHP_SELF}?ctg=statistics&option=user&sel_user={$smarty.get.sel_user}&specific_course_info=1&course={$course.id}&popup=1" target = "POPUP_FRAME" onclick = "eF_js_showDivPopup('{$smarty.const._DETAILS}', 2)"><img class = "handle" src = "images/16x16/information.png" title = "{$smarty.const._DETAILS}" alt = "{$smarty.const._DETAILS}" /></a>&nbsp;  -->
						{*/if*}
					{*/if*}
 
					{*if !isset($T_DATASOURCE_OPERATIONS) || in_array('statistics', $T_DATASOURCE_OPERATIONS)*}
						{if !isset($T_CURRENT_USER->coreAccess.statistics) || $T_CURRENT_USER->coreAccess.statistics != 'hidden'}
							<button 
								class="skin_colour round_all statisticsCourseLink" 
								title = "{$smarty.const._STATISTICS}" 
								onclick = "window.location.href = '{$smarty.server.PHP_SELF}?ctg=statistics&option=course&sel_course={$course.id}'">
								<img 
									class = "ajaxHandle" width="16" height="16" alt = "{$smarty.const._STATISTICS}" 
									src = "/{$smarty.const.G_CURRENTTHEMEURL}/images/icons/small/white/chart_8.png" />
							</button>
						{/if}
					{*/if*}
 
					{*if !isset($T_DATASOURCE_OPERATIONS) || in_array('settings', $T_DATASOURCE_OPERATIONS)*}
						<button 
							class="skin_colour round_all statisticsCourseLink" 
							title = "{$smarty.const._COURSEINFORMATION}" 
							onclick = "window.location.href = '{$smarty.server.PHP_SELF}?ctg=courses&course={$course.id}&op=course_info'">
							<img 
								class = "ajaxHandle" width="16" height="16" alt = "{$smarty.const._COURSEINFORMATION}" 
								src = "/{$smarty.const.G_CURRENTTHEMEURL}/images/icons/small/white/cog_2.png" />
						</button>	
					{*/if*}
 
					{*if !isset($T_DATASOURCE_OPERATIONS) || in_array('propagate', $T_DATASOURCE_OPERATIONS)*}
					<!--     <img class = "ajaxHandle" src = "images/16x16/arrow_right.png" title = "{$smarty.const._PROPAGATECOURSE}" alt = "{$smarty.const._PROPAGATECOURSE}" onclick = "propagateCourse(this, '{$course.id}')"/>  -->
					{*/if*}
 
					{*if !isset($T_DATASOURCE_OPERATIONS) || in_array('delete', $T_DATASOURCE_OPERATIONS)*}
						{if $T_MODULE_XCOURSE_CANCHANGE}
							<button class="skin_colour round_all activateCourseLink" title = "{$smarty.const._DELETE}" onclick = "xcourse_deleteCourse(this, '{$course.id}');">
								<img 
									class = "ajaxHandle" width="16" height="16" alt = "{$smarty.const._DELETE}" 
									src = "/{$smarty.const.G_CURRENTTHEMEURL}/images/icons/small/white/trashcan.png" />
							</button>
						{/if}
					{*/if*}
				</td>					
			</tr>
			{/foreach}
		</tbody>
	</table>
{/capture}

{eF_template_printBlock 
	title=$smarty.const.__XCOURSES_MANAGEMENT
	data=$smarty.capture.t_xcourses_table
	contentclass=""
}