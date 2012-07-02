<div class="blockContents" metadata="{Mag_Json_Encode data=$course}">
	<h4>{$smarty.const.__XENROLLMENT_REGISTER_CHECKLIST}</h4>

	
	<ul class="xenrollment-register-checklist" style="list-style: none; width: 100%; float: left;">
		<li>
			<img width="24" height="24" alt="Check" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/users.png">
			<span {if $T_XENROLLMENT_SELECTED_USER}class="check-item-ok"{else}class="check-item-pendente"{/if}>
				{$smarty.const.__XENROLLMENT_SELECT_OR_REGISTER_STUDENT}
			</span>
			<span class="xenrollment-register-checklist-status">
				{if $T_XENROLLMENT_SELECTED_USER}
					<a href="{$T_XENROLLMENT_SELECTED_USER.edit_link}">
						#filter:login-{$T_XENROLLMENT_SELECTED_USER.login}#
					</a>
					<br />
					<a href="{$T_XENROLLMENT_SELECT_USER_URL}" class="no-color-link">
						{$smarty.const.__XUSER_SELECTANOTHERUSER}
					</a>
				{else}
					{$smarty.const.__XENROLLMENT_STUDENT_NOT_SELECTED_YET}
					<br />
					<a href="{$T_XENROLLMENT_ADD_USER_URL}" class="no-color-link">
						{$smarty.const.__XUSER_ADDUSER}
					</a>
					&nbsp;|&nbsp;
					<a href="{$T_XENROLLMENT_SELECT_USER_URL}" class="no-color-link">
						{$smarty.const.__XUSER_SELECTUSER}
					</a>
				{/if}
			</span>
		</li>
		<li>
			<img width="24" height="24" alt="Check" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/book_large.png">
			<span {if $T_XENROLLMENT_SELECTED_USER && $T_XENROLLMENT_SELECTED_COURSE}class="check-item-ok"{else}class="check-item-pendente"{/if}>
				{$smarty.const.__XENROLLMENT_SELECT_OR_REGISTER_COURSE}
			</span>
			{if $T_XENROLLMENT_SELECTED_USER}
			<span class="xenrollment-register-checklist-status">
				{if $T_XENROLLMENT_SELECTED_COURSE}
					<a href="javascript: void(0);" class="_XENROLLMENT_COURSESELECT_LINK">
						{$T_XENROLLMENT_SELECTED_COURSE.name}
					</a>
					<br />
					<a href="{$T_XENROLLMENT_SELECT_COURSE_URL}" class="_XENROLLMENT_COURSESELECT_LINK no-color-link">
						{$smarty.const.__XCOURSE_SELECTANOTHERCOURSE}
					</a>
				{else}
					{$smarty.const.__XENROLLMENT_COURSE_NOT_SELECTED_YET}
					<br />
					<a href="{$T_XENROLLMENT_ADD_COURSE_URL}" class="no-color-link">
						{$smarty.const.__XCOURSE_ADDCOURSE}
					</a>
					&nbsp;|&nbsp;
					<a href="javascript: void(0);" class="_XENROLLMENT_COURSESELECT_LINK no-color-link">
						{$smarty.const.__XCOURSE_SELECTCOURSE}
					</a>
				{/if}
			</span>
			{/if}
		</li>
		<li>
			<img width="24" height="24" alt="Check" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/day_calendar.png">
			<span {if $T_XENROLLMENT_SELECTED_USER && $T_XENROLLMENT_SELECTED_COURSE && $T_XENROLLMENT_SELECTED_CLASSE}class="check-item-ok"{else}class="check-item-pendente"{/if}>
				{$smarty.const.__XENROLLMENT_SELECT_OR_REGISTER_CLASSE}
			</span>
			{if $T_XENROLLMENT_SELECTED_COURSE}
			<span class="xenrollment-register-checklist-status">
				{if $T_XENROLLMENT_SELECTED_CLASSE}
					<a href="javascript: void(0);" class="_XENROLLMENT_CLASSESELECT_LINK">
						{$T_XENROLLMENT_SELECTED_MODALITY.course_type} &raquo; {$T_XENROLLMENT_SELECTED_CLASSE.name}  
					</a>
					<br />
					<a href="{$T_XENROLLMENT_SELECT_CLASSE_URL}" class="_XENROLLMENT_CLASSESELECT_LINK no-color-link">
						{$smarty.const.__XCOURSE_SELECTANOTHERCLASSE}
					</a>
				{else}
					{$smarty.const.__XENROLLMENT_CLASSE_NOT_SELECTED_YET}
					<br />
					<a href="{$T_XENROLLMENT_ADD_CLASSE_URL}" class="no-color-link">
						{$smarty.const.__XCOURSE_ADDCLASSE}
					</a>
					&nbsp;|&nbsp;
					<a href="javascript: void(0);" class="_XENROLLMENT_CLASSESELECT_LINK no-color-link">
						{$smarty.const.__XCOURSE_SELECTCLASSE}
					</a>
				{/if}
			</span>
			{/if}
		</li>
		<li>
			<img width="24" height="24" alt="Check" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/money.png">
			<span {if $T_XENROLLMENT_SELECTED_USER && $T_XENROLLMENT_SELECTED_COURSE && $T_XENROLLMENT_SELECTED_PAYMENT}class="check-item-ok"{else}class="check-item-pendente"{/if}>
				{$smarty.const.__XENROLLMENT_REGISTER_PAYMENT_DETAILS}
			</span>
			{if $T_XENROLLMENT_SELECTED_COURSE}
			<span class="xenrollment-register-checklist-status">
				{if $T_XENROLLMENT_SELECTED_PAYMENT}
					<a href="javascript: void(0);" class="_XENROLLMENT_PAYMENTSELECT_LINK">
						{$T_XENROLLMENT_SELECTED_PAYMENT.payment_type}
					</a>
					<a href="{$T_XENROLLMENT_SELECTED_USER.edit_link}#Detalhes_financeiros" class=" no-color-link">
						[+ Detalhes]
					</a>

					<br />
					<span class="annotation">
					{$T_XENROLLMENT_SELECTED_PAYMENT.payment_type_description}
					</span>
					<br />
				{else}
					
					
					<a href="javascript: void(0);" class="_XENROLLMENT_PAYMENTSELECT_LINK no-color-link">
						{$smarty.const.__XPAYMENT_ADDPAYMENT}
					</a>
					<br />
					<span class="annotation">
					{$smarty.const.__XENROLLMENT_PAYMENT_NOT_SELECTED_YET}
					</span>
					<!-- 
					&nbsp;|&nbsp;
					<a href="javascript: void(0);" class="_XENROLLMENT_PAYMENTSELECT_LINK no-color-link">
						{$smarty.const.__XCOURSE_SELECTCLASSE}
					</a>
					 -->
				{/if}
			</span>
			{/if}
			 
		</li>
		<li>
			<img width="24" height="24" alt="Check" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/grey/books.png">
			<span {if $T_XENROLLMENT_SELECTED_USER && $T_XENROLLMENT_SELECTED_COURSE && $T_XENROLLMENT_SELECTED_COURSE.documents_summary.checked == $T_XENROLLMENT_SELECTED_COURSE.documents_summary.total}class="check-item-ok"{else}class="check-item-pendente"{/if}>
				{$smarty.const.__XENROLLMENT_CHECK_DOCUMENT_LIST}
			</span>
			{if $T_XENROLLMENT_SELECTED_COURSE.documents_summary}

			<span class="xenrollment-register-checklist-status">
				{if $T_XENROLLMENT_SELECTED_COURSE.documents_summary}
					<a href="javascript: void(0);" class="_XENROLLMENT_DOCUMENTSSELECT_LINK">
						{$smarty.const.__XDOCUMENTS}: <strong>{$T_XENROLLMENT_SELECTED_COURSE.documents_summary.checked} / {$T_XENROLLMENT_SELECTED_COURSE.documents_summary.total}</strong>
					</a>
					<br />
					<a href="javascript: void(0);" class="_XENROLLMENT_DOCUMENTSSELECT_LINK no-color-link">
						{$smarty.const.__XDOCUMENTS_EDIT_DOCLIST}
					</a>
				{else}
				{/if}
			</span>
			{/if}
			 
		</li>
	</ul>

	{if $T_XENROLLMENT_ACTION == 'register_xenrollment' || $T_XENROLLMENT_SELECTED_ENROLLMENT.status_id == 1}
	<div class="grid_16" style="margin-top: 20px;">
		<button class="button_colour round_all xenrollment_save_new_enrollment" type="button" name="__ENROLLMENT_SAVE" value="{$smarty.const.__XENROLLMENT_SAVE}">
			<img width="24" height="24" src="/themes/{$smarty.const.G_CURRENTTHEME}/images/icons/small/white/bended_arrow_right.png">
				<span>{$smarty.const.__XENROLLMENT_REGISTER}</span>
		</button>
	</div>
	{/if}
</div>

<div id="_XENROLLMENT_COURSESELECT_DIALOG" title="{$smarty.const.__XENROLLMENT_COURSESELECT_DIALOG_TITLE}" class="_XENROLLMENT_COURSESELECT_DIALOG block">
	<h4>{$smarty.const.__XENROLLMENT_PLEASE_SELECT_A_COURSE}</h4>
	{include 
		file="$T_XENROLLMENT_BASEDIR/templates/includes/xenrollment.courses.selectlist.tpl"
		T_COURSES_LIST=$T_XENROLLMENT_DISPONIBLE_COURSES_LIST
		T_CURRENT_COURSE=$T_XENROLLMENT_SELECTED_COURSE
	}
</div>

<div id="_XENROLLMENT_CLASSESELECT_DIALOG" title="{$smarty.const.__XENROLLMENT_CLASSESELECT_DIALOG_TITLE}" class="_XENROLLMENT_CLASSESELECT_DIALOG block">
		<ul style="list-style: none; width: 100%; float: left;">
			<li>
				<span class="classe-modality">
					<label>{$smarty.const.__XENROLLMENT_PLEASE_SELECT_A_COURSE_MODALITY}</label>
					{if $T_XENROLLMENT_SELECTED_COURSE.enable_presencial == 1}
						<input 
							type="radio" 
							name="course_type[{$T_XENROLLMENT_SELECTED_COURSE.id}]" 
							value="Presencial" 
							{if $T_XENROLLMENT_SELECTED_MODALITY.course_type == 'Presencial'}checked="checked"{/if}>Presencial
					{/if}
					{if $T_XENROLLMENT_SELECTED_COURSE.enable_web == 1}
						<input 
							type="radio" 
							name="course_type[{$T_XENROLLMENT_SELECTED_COURSE.id}]" 
							value="Via Web" 
							{if $T_XENROLLMENT_SELECTED_MODALITY.course_type == 'Via Web'}checked="checked"{/if}>Via Web
					{/if}
				</span>
			</li>
		</ul>
		<label>{$smarty.const.__XENROLLMENT_PLEASE_SELECT_A_CLASS}</label>
		<ul class="xenrollment-classe-select-list" style="list-style: none; width: 100%; float: left;">
		{foreach name="edit_payment_courses_iteration" key="course_pay" item="classe" from=$T_XENROLLMENT_DISPONIBLE_CLASSES_LIST}
			<li>
				<span class="classe-name">
					<input type="radio" name="classes" value="{$classe.id}" {if $T_XENROLLMENT_SELECTED_CLASSE.id == $classe.id}checked="checked"{/if}/>
					{$classe.name}
				</span>
			</li>
		{foreachelse}
			<li>{$smarty.const.__XUSER_NOCLASSESFOUND}</li>
		{/foreach}
	</ul>
</div>


<div id="_XENROLLMENT_PAYMENTSELECT_DIALOG" class="blockContents" title="{$smarty.const._PAGAMENTO_SELECTPAYMENTMETHODDIALOG}">
	{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.javascript}
	<form {$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.attributes}>
		{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.hidden}
		<div class="flat_area">
			<div class="grid_16">
				<label>{$T_XPAGAMENTO_METHOD_SELECT_FORM.payment_type_id.label}</label> 
				{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.payment_type_id.html}
				
				<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.data_matricula.label}</label> 
				{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.data_matricula.html}
							
				<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.data_inicio.label}</label> 
				{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.data_inicio.html}
				
				<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.parcelas.label}</label> 
				{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.parcelas.html}
						
				<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.vencimento.label}</label> 
				{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.vencimento.html}
							
				<label>{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.desconto.label}</label> 
				{$T_MODULE_PAGAMENTO_METHOD_SELECT_FORM.desconto.html}
			</div>
		</div>
	</form>
</div>


<div id="_XENROLLMENT_DOCUMENTSSELECT_DIALOG" title="{$smarty.const.__XENROLLMENT_DOCUMENTSSELECT_DIALOG_TITLE}" class="_XENROLLMENT_DOCUMENTSSELECT_DIALOG block">
	<h4>{$smarty.const.__XENROLLMENT_CHECK_DOCUMENT_LIST}</h4>
	{include 
		file="$T_XENROLLMENT_BASEDIR/templates/includes/xdocuments.user.list.tpl"
		T_USER_DOCUMENT_LIST=$T_XENROLLMENT_SELECTED_COURSE.documents_list
	}
</div>

