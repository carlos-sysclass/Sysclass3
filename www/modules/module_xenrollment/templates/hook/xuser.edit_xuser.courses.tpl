{if $T_XENROLLMENT_COURSES_LIST_OPTIONS}
<div class="grid_16 box border">
	<div class="headerTools">
		{foreach name="xenrollment_iteration" key="option_index" item="option" from=$T_XENROLLMENT_COURSES_LIST_OPTIONS}
			<span>
				<a href="{$option.href}" {if $option.selected} class="selected" {/if} title="{$option.hint}">
					<img src="{$option.image}">
					{$option.text}
				</a>
			</span>
		{/foreach}
	</div>
</div>
<div class="clear"></div>
{/if}


{foreach name="edit_payment_courses_iteration" key="course_pay" item="course" from=$T_XENROLLMENT_COURSES_LIST}
{* FOREACH COURSE, APPEND userTypeChange DIALOG *}
<div id="_XUSER_COURSETYPE_DIALOG-{$course.id}" class="_XUSER_COURSETYPE_DIALOG block" title="{$smarty.const.__XUSER_EDITCOURSETYPE}" metadata="{Mag_Json_Encode data=$course}">
	<label class="courseName">{$course.name}</label>
	<input type="radio" name="dialog_course_type[{$course.id}]" value="Presencial" {if $course.course_type == 'Presencial'}checked="checked"{/if}>Presencial
	<input type="radio" name="dialog_course_type[{$course.id}]" value="Via Web" {if $course.course_type == 'Via Web'}checked="checked"{/if}>Via Web
</div>
{/foreach}

<h3>{$smarty.const.__XUSER_SELECTED_COURSES}</h3>
<ul style="list-style: none;">
	{assign var="course_total" value="0"}
	{foreach name="edit_payment_courses_iteration" key="course_pay" item="course" from=$T_XENROLLMENT_COURSES_LIST}

		{math equation="total + preco" total=$course_total preco="`$course.price`" assign="course_total"}

		<li>
			<span style="width: 70%; display: inline-block;">
				{$course.name}
				&nbsp;&raquo;&nbsp;
				{*foreach name="edit_payment_courses_classes_iteration" key="classe_key" item="classe" from=$course.classe*}
				{assign var="classe" value=$course.classe}
				
					<a 
						id="__XUSER_COURSECLASS_CHANGELINK-{$course.id}-{$classe.id}" 
						href="javascript: changeUserCourseClass('{$T_XENROLLMENT_EDITED_USER.login}', {$course.id}, {$classe.id});">{$classe.name}
					</a>
					{if !$smarty.foreach.edit_payment_courses_classes_iteration.last}
					&nbsp;,&nbsp; 
					{/if}
				{*/foreach*}
				
				<br/>
				<span style="margin-left: 20px;">
					<a id="__XUSER_COURSETYPE_CHANGELINK-{$course.id}" href="javascript: changeUserCourseType('{$T_XENROLLMENT_EDITED_USER.login}', {$course.id});">{$course.course_type}</a>
					&nbsp;&raquo;&nbsp;
					
				</span>
			</span>
			<span style="width: 25%; float: right;"><strong>#filter:currency-{$course.price}#</strong></span>
		</li>
	{foreachelse}
		<li>{$smarty.const.__XUSER_NOCOURSESFOUND}</li>
	{/foreach}
	{if $T_XENROLLMENT_COURSES_LIST|@count > 0}
		<li>
			<span style="width: 70%; display: inline-block;">
				Total de Cursos
			</span>
			<span style="width: 25%; float: right;"><strong>#filter:currency-{$course_total}#</strong></span>
			
		</li>
	{/if}
</ul>