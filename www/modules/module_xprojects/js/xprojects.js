jQuery(function(jQuery) {
	if (typeof($_xproject_mod_data) != 'undefined') {
		var url = $_xproject_mod_data['xprojects.baseUrl'] + '&action=load_group_file_list';
		/*
		jQuery("#xproject-file-list").load(url);
		*/
		
		jQuery('#xproject-file-list').fileTree({ 
			root	: '/',
			script	: url
		}, function(file) {
	        // VIEW FILE
	    });
	}
	
});