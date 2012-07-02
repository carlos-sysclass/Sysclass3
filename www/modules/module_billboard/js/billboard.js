jQuery(function(jQuery) {	
	jQuery("#billboard-main-list > li").hide();
	jQuery("#billboard-main-list > li").first().show();
	
	jQuery.Topic( "xcourse_course_lesson_change" ).subscribe( function(course_id, lesson_id) {
		var class_name = ".course_" + course_id;
		
		jQuery("#billboard-main-list > li").hide();
		
		if (jQuery("#billboard-main-list > li" + class_name).size() > 0) {
			jQuery("#billboard-main-list > li" + class_name).show();
		} else {
			jQuery("#billboard-main-list > li").first().show();
		}
	});
});