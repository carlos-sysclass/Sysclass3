(function($){
	
	
	// SEARCH WIDGET
	jQuery("#index_page :input[name='login']").val($languageJS['__USER_TEXT']);
	jQuery("#index_page :input[name='password']").hide().val($languageJS['__PASS_TEXT']);
	jQuery("#index_page :input[name='_password']").val($languageJS['__PASS_TEXT']).show();
	jQuery("#index_page :input[name='login_or_pwd']").val($languageJS['__RESET_TEXT']);
	
	jQuery("#index_page :input[name='login']").focus(function() {
		if (jQuery(this).val() == $languageJS['__USER_TEXT']) {
			jQuery(this).val("");
		}
	}).blur(function() {
		if (jQuery(this).val() == "") {
			jQuery(this).val($languageJS['__USER_TEXT']);
		}
	});
	jQuery("#index_page :input[name='password']").focus(function() {
		if (jQuery(this).val() == $languageJS['__PASS_TEXT']) {
			jQuery(this).val("");
		}
	}).blur(function() {
		if (jQuery(this).val() == "" || jQuery(this).val() == $languageJS['__PASS_TEXT']) {
			jQuery(this).hide();
			jQuery("#index_page :input[name='_password']").show().val(jQuery(this).val()).blur();
		}
		/*
		if (jQuery(this).val() == "") {
			jQuery(this).val($languageJS['__PASS_TEXT']);
		}
		*/
	});
	
	jQuery("#index_page :input[name='login_or_pwd']").focus(function() {
		if (jQuery(this).val() == $languageJS['__RESET_TEXT']) {
			jQuery(this).val("");
		}
	}).blur(function() {
		if (jQuery(this).val() == "") {
			jQuery(this).val($languageJS['__RESET_TEXT']);
		}
	});
	
		
	jQuery("#index_page :input[name='_password']").focus(function() {
		//if (jQuery(this).val() == $languageJS['__PASS_TEXT']) {
			jQuery(this).hide();
			jQuery("#index_page :input[name='password']").show().focus();
		//}
	}).blur(function() {
		if (jQuery(this).val() == "") {
			jQuery(this).val($languageJS['__PASS_TEXT']);
		}
	});
	
	// END SEARCH WIDGET
	
	
	jQuery("#avatar").click(function(){
		jQuery("#dialog-avatar").dialog({
			height: 250,
			width: 450,
			modal: true,
			resizable: false
		});
	});
	
	jQuery("#button-content").click(function(){
		jQuery("#list-content").toggle();	
	});
	jQuery("#button-lesson").click(function(){
		jQuery("#list-lesson").toggle();	
	});
	
	jQuery("#input-search-button").click(function(){
		jQuery("#input-search").toggle();	
	});

	jQuery('#xcms-module-ads-slideshow a:first').fadeIn(3000, function() {
		jQuery('#xcms-module-ads-slideshow').cycle();
	    
	});

	jQuery("#show_local_polos_maps" ).click(function() {
		jQuery("#local_polos_maps").dialog({
			height: 530,
			width: 670,
			resizable: false,
			modal: true
		});
	});

/*	
	jQuery("#menutop-over").hide();
	
	jQuery("#menutop-over-exibe").click(function(){
	//	jQuery("#menutop-over").show('t');
		$('#menutop-over').animate({
		    left: '+=60',
		    height: 'toggle'
		  }, 500, function() {
		    // Animation complete.
		  });
	});
	jQuery("#menutop-over").mouseout(function(){
		//jQuery("#menutop-over").hide('slow', 'toggle');
		$('#menutop-over').animate({
		    left: '-=60',
		    height: 'toggle'
		  }, 1000, function() {
		    // Animation complete.
		  });
	});

	jQuery(window).bind("scroll", function() { 
	    if (jQuery(window).scrollTop() > 0) {
	        jQuery(".footer").css("position", "relative");
	    } else {
	        jQuery(".footer").css("position", "absolute");
	    }
	});
*/

	jQuery('.text_expand').click(function() {
		var mostrar = jQuery(this).attr('tipo')=='expand'?'.text_complete':'.text_short';
		jQuery(this).parent().hide();
		jQuery(this).parent().parent().children(mostrar).show(3000);
		return false;
	});

})(jQuery); // plugin code ends