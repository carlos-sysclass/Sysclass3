<ul class="xenrollment-course-select-list" style="list-style: none; width: 100%; float: left;">
	{foreach name="edit_payment_courses_iteration" key="course_pay" item="course" from=$T_COURSES_LIST}
		<li class="{if $course.user_in_course}user-in-this-course{/if} {if $course.show_catalog}in-catalog{else}out-catalog{/if}" {if $course.user_in_course}hint="{$smarty.const.__XENROLLMENT_USER_HAS_THIS_COURSE}"{/if}>
			<span class="course-name">
				{if !$course.user_in_course}
					<input type="radio" name="courses" value="{$course.id}" {if $T_XENROLLMENT_SELECTED_COURSE.id == $course.id}checked="checked"{/if}/>
				{/if}
				{$course.name}
			</span>
			<span class="course-price">#filter:currency-{$course.price}#</span>
		</li>
	{foreachelse}
		<li>{$smarty.const.__XUSER_NOCOURSESFOUND}</li>
	{/foreach}
</ul>