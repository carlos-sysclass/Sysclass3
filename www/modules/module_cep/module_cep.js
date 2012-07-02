jQuery(function($) {
	var cep_module_url = "http://" + window.location.hostname + window.location.pathname + "?ctg=module&op=module_cep&output=json";
	
	last_search = ""; 
	var checkCep = function() {
		var cep = jQuery(this).val();
		
		if ((cep.length === 5 || cep.length >= 8) && cep !== last_search) {
			cepObject = jQuery(this);
			jQuery.get(
				cep_module_url,
				{cep: jQuery(this).val(), prefix : jQuery(this).attr('name')},
				function(data, response) {
					for(key in data) {
						cepObject.parents("form").find(":input[name='" + key + "']").val(data[key]);
					}
					cepObject.parents("form").find(":input[name$='logradouro']").val(data['tipo_logradouro'] + ' ' + data['logradouro']);
					
					if (jQuery.uniform) {
						jQuery.uniform.update();	
					}
				},
				'json');
		}
	};
	jQuery(":input[alt='cep']").bind("keyup blur", checkCep);
	
	jQuery(":input[alt='cep']").each(checkCep);
});