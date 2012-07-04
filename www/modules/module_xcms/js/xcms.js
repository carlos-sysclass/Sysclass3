(function(jQuery) {

	// Carrega comunicados
	jQuery.Topic('xcourse_course_lesson_change').subscribe(function(course_id, lesson_id) {
		jQuery('#comunicadosStudentContainer table tbody tr').hide();
		if ( jQuery('#comunicadosStudentContainer table tbody tr.lesson'+lesson_id).size() ==  0  ) {
			var url = window.location.protocol + "//" + window.location.hostname 
					+ window.location.pathname + "?ctg=module&op=module_xcms"
					+ "&action=load_news&output=json";
			jQuery.post(url, function(data, status) {
				jQuery('#comunicadosStudentContainer table tbody').append(jQuery(data).find('tr'));
				jQuery('#comunicadosStudentContainer table tbody tr.lesson0').show();
			});
		} else {
			jQuery('#comunicadosStudentContainer table tbody tr.lesson'+lesson_id).show();
			jQuery('#comunicadosStudentContainer table tbody tr.lesson0').show();
		}
	});

})(jQuery);