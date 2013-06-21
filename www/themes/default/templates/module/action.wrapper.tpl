{include file="`$_T_MODULE_BASEDIR`/templates/actions/`$_T_MODULE_ACTION`.tpl"}

<!-- THIS CODE MUST BE PUT INSIDE PARENT MODULE CLASSES LOGIC, TO GENERATE AUTOMATIC ALL MODULE CONFIG -->
<script>
	{literal}
	if (typeof(window['_mod_data_']) == 'undefined') {
		window['_mod_data_'] = {};
	}
	
	{/literal}
	
	window._mod_data_._{$T_MODULE_NAME|@strtolower}_ = {Mag_Json_Encode data=$_T_MODULE_MOD_DATA};
</script>