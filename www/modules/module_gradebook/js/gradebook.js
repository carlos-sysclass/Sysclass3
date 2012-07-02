jQuery(function($) {
	jQuery.Topic( "xcourse_course_lesson_change" ).subscribe( function(course_id, lesson_id) {
		var url = window.location.pathname + "?ctg=module&op=module_gradebook&lessons_ID=" + lesson_id;
		jQuery("#modules_gradebook_change_lesson_id").attr("href", url);
	});
});