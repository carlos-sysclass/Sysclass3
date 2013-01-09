<script>
	var getCoursesDatatableID	= '{$smarty.const._XCOURSE_GETCOURSES_DATATABLE}';
//	var activeStates 		= ['{$smarty.const._ACTIVATE}', '{$smarty.const._DEACTIVATE}'];

	{if $smarty.get.xcourse_id}
		var editCourse_ID = new Number('{$smarty.get.xcourse_id}');
	{/if}
	$_xcourse_mod_data = {Mag_Json_Encode data=$T_XCOURSE_MOD_DATA};
</script>