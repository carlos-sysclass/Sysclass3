
<div>
	<form name="form_enrollment_search" method="post" id="form_enrollment_search">
		<h2>Busca</h2>
		<div id="report_enrollment_search">
			<div>
				<span>Nome:</span>
				<input name="name" type="text" />
			</div>
			<div>
				<span>Tipo:</span>
				<select name="userType">
					<option value="">selecione</option>
					{foreach name='enrollment_list' key='key' item='enroll' from=$T_XENROLLMENT_USERTYPES}
					<option value="{$enroll}">{$enroll}</option>
					{/foreach}
				</select>
			</div>
			<div>
				<span>Estado:</span>
				<select name="state">
					<option value="">selecione</option>
					{foreach name='enrollment_list' key='key' item='enroll' from=$T_XENROLLMENT_STATES}
					<option value="{$enroll}">{$enroll}</option>
					{/foreach}
				</select>
			</div>
			<div>
				<span>Cidade:</span>
				<select name="city">
					<option value="">selecione</option>
					{foreach name='enrollment_list' key='key' item='enroll' from=$T_XENROLLMENT_CITIES}
					<option value="{$enroll}">{$enroll}</option>
					{/foreach}
				</select>
			</div>
			<div>
				<input name="Buscar" value="Buscar" type="submit" id="report_enrollment_search_submit" />
			</div>
			<div>
				<a href="http://local.sysclass.com/administrator.php?ctg=module&op=module_xenrollment&action=report_enrollment_excel" id="report_enrollment_excel">
					<img alt="Formato Excel" title="Formato Excel" src="themes/default/images/file_types/xls.png" />Exportar para Excel
				</a>
			</div>
		</div>
	</form>
</div>

<div class="clear"></div>

<div id="list_report_enrollment">
	{include file="$T_XENROLLMENT_BASEDIR/templates/actions/xenrollment.get_report_enrollment.tpl"}
</div>
