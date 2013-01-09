<script>
	// $_xpay_mod_data = {Mag_Json_Encode data=$T_XPAY_MOD_DATA};

	{literal}
	if (typeof(window['_mod_data_']) == 'undefined') {
		window['_mod_data_'] = {};
	}
	{/literal}
	window._mod_data_._xpay_ = {Mag_Json_Encode data=$T_XPAY_MOD_DATA};
</script>