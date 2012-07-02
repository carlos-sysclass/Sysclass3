<table class = "display" id="_XCOURSE_LESSONS_LIST">
	<thead>
		<tr>
			<th>{$smarty.const._NAME}</th>
			<th>{$smarty.const._DIRECTION}</th>
			<th>{$smarty.const._CREATED}</th>
			<th>{$smarty.const._SELECT}</th>
		</tr>
	</thead>
	<tbody>
		{foreach key = 'key' item = 'lesson' from = $T_XCOURSE_LESSONS_LIST}
			<tr metadata="{Mag_Json_Encode data = $lesson}">
				<td>
					<a href = "{$smarty.server.PHP_SELF}?ctg=lessons&edit_lesson={$lesson.id}" class = "editLink">{$lesson.name}</a>
				</td>
		     	<td>{$lesson.directionsPath}</td>
		     	<td>#filter:timestamp-{$lesson.created}#</td>
		     	<td class = "centerAlign">
			    	{*if $_change_*}
				      	<input type = "checkbox" id = "{$lesson.id}" onclick = "xcourse_lessonsAddRemove('{$T_XCOURSE_MOD_DATA.edited_course.id}', '{$lesson.id}');" {if $lesson.has_lesson}checked{/if}>{if $lesson.has_lesson}<span style = "display:none">checked</span>{/if} {*Span is for sorting here*}
					{*else*}
				     	{*if $lesson.has_lesson*}
				     	<!-- 
				     		<img src = "images/16x16/success.png" alt = "{$smarty.const._COURSELESSON}" title = "{$smarty.const._COURSELESSON}">
							<span style = "display:none">checked</span>
						 -->
			     		{*/if*}
			    	{*/if*}
		    	 </td>
<!-- 
				<td class = "center">
					<div class="button_display">
						<button class="skin_colour round_all editClassLink">
							<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/pencil.png">
							<span>Editar</span>
						</button>
						<button class="skin_colour round_all usersClassLink" title="{$smarty.const.__XCOURSE_USERINCLASS_HINT}">
							<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/users.png">
							<span>Usuário</span>
						</button>

						<button class="skin_colour round_all calendarClassLink">
							<img width="16" height="16" alt="Editar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/day_calendar.png">
							<span>Calendário</span>
						</button>
						{if $classe.count_users == 0}
						<button onclick="xcourse_deleteCourseClass(this, {$classe.courses_ID}, {$classe.id});" class="red skin_colour round_all">
							<img width="16" height="16" title="Deletar" alt="Deletar" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/delete.png">
							<span>Deletar</span>
						</button>
						{/if}
					</div>
				</td>
 -->
			</tr>
		{/foreach}
	</tbody>
</table>