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
				{assign var="doc_checked_total" value="0"}
				{assign var="doc_total" value="0"}
				
				{foreach name="edit_payment_courses_iteration" key="course_pay" item="course" from=$T_XENROLLMENT_COURSES_LIST}
				
					{math equation="total + qtde" total=$doc_checked_total qtde="`$course.documents_summary.checked`" assign="doc_checked_total"}
					{math equation="total + qtde" total=$doc_total qtde="`$course.documents_summary.total`" assign="doc_total"}
					<li class="xenrollment_course_list_item">
						<span class="xdocuments_name">
							{$course.name}
							&nbsp;&raquo;&nbsp;
							{foreach name="edit_payment_courses_classes_iteration" key="classe_key" item="classe" from=$course.classes}
								<a href="javascript: void(0)">{$classe.name}</a>
								{if !$smarty.foreach.edit_payment_courses_classes_iteration.last}
								&nbsp;,&nbsp; 
								{/if}
							{/foreach}
							
							<br/>
							<span style="margin-left: 20px;">
								<a href="javascript: void(0);">{$course.course_type}</a>
							</span>
						</span>
						<span class="xdocuments_required">&nbsp;</span>
						
						<span class="xdocuments_status">{$smarty.const.__XDOCUMENTS}: <strong>{$course.documents_summary.checked} / {$course.documents_summary.total}</strong></span>
						<span class="xdocuments_operations">&nbsp;</span>
					</li>
					{if $course.documents_list|@count > 0}
					<li class="xdocuments_course_list_item">
						<h3 style="margin: 10px 0 15px;">{$smarty.const.__XENROLLMENT_USER_DOCUMENTS}</h3>
						{include 
							file="$T_XENROLLMENT_BASEDIR/templates/includes/xdocuments.user.list.tpl"
							T_USER_DOCUMENT_LIST=$course.documents_list
						}
					</li>
					{/if}
				{foreachelse}
					<li>{$smarty.const.__XUSER_NOCOURSESFOUND}</li>
				{/foreach}
				{if $T_XUSER_COURSES_LIST|@count > 0}
					<li>
						<span class="xdocuments_name">
							Total de Documentos
						</span>
						<span class="xdocuments_required">&nbsp;</span>
						<span class="xdocuments_status">{$smarty.const.__XDOCUMENTS}: <strong>{$doc_checked_total} / {$doc_total}</strong></span>
						<span class="xdocuments_operations">&nbsp;</span>
						
					</li>
				{/if}
			</ul>
			<br />