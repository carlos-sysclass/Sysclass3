<input type="hidden" name="xenrollment_course_id" value="{$T_XENROLLMENT_EDITED_COURSE.id}" />

{if $T_XDOCUMENTS_COURSE_OPTIONS}
<div class="grid_16 box border">
	<div class="headerTools">
		{foreach name="xenrollment_iteration" key="option_index" item="option" from=$T_XDOCUMENTS_COURSE_OPTIONS}
			<span>
				<a href="{$option.href}" {if $option.selected} class="selected" {/if} hint="{$option.hint}" class="{$option.class}">
					<img src="{$option.image}">
					{$option.text}
				</a>
			</span>
		{/foreach}
	</div>
</div>
<div class="clear"></div>
{/if}

<h3>{$smarty.const.__XENROLLMENT_COURSE_DOCUMENTS}</h3>
{include 
	file="$T_XENROLLMENT_BASEDIR/templates/includes/xdocuments.course.list.tpl"
	T_COURSE_DOCUMENT_LIST=$T_XDOCUMENTS_COURSES_LIST
	T_SELECTED_COURSE_DOCUMENT=$T_XENROLLMENT_EDITED_COURSE
}

{if $T_XDOCUMENTS_LIST|@count > 0}
<ul class="xdocuments_list xdocuments_list_summary">
	<li>
		<span class="xdocuments_name">
			Total de Documentos
		</span>
		<span class="xdocuments_required">&nbsp;</span>
		<span class="xdocuments_status">{$smarty.const.__XDOCUMENTS}: <strong>{$T_XDOCUMENTS_LIST|@count}</strong></span>
		<span class="xdocuments_operations">&nbsp;</span>
	</li>
</ul>
{/if}
{if $T_XDOCUMENTS_TO_APPEND_LIST|@count > 0}
<div id="_XDOCUMENTS_ADD_XDOCUMENT_TO_COURSE" title="{$smarty.const.__XDOCUMENTS_ADD_XDOCUMENT_TO_COURSE_DIALOG_TITLE}" class="blockContents" metadata="{Mag_Json_Encode data = $T_XCOURSE_EDITED_COURSE->course}">
	{include 
		file="$T_XENROLLMENT_BASEDIR/templates/includes/xdocuments.list.tpl"
		T_DOCUMENT_LIST=$T_XDOCUMENTS_TO_APPEND_LIST
	}
</div>
{/if}
