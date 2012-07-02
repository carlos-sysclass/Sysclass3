jQuery(function($) {
	if (typeof(slider_value) != 'undefined') {
		jQuery("#max-users-slider").slider({
			range: "min",
			value: slider_value,
			min: -1,
			max: 150,
			change : function( event, ui ) {
				if (ui.value == -1) {
					html = "&#8734;";
				} else {
					html = ui.value; 
				}
				
				jQuery("#max-users-text").html( html );
				jQuery(":input[name='max_users']").val( ui.value );
				
				
				
			},
			slide : function( event, ui ) {
				if (ui.value == -1) {
					html = "&#8734;";
				} else {
					html = ui.value; 
				}

				jQuery("#max-users-text").html( html );
				jQuery(":input[name='max_users']").val( ui.value );
			}
		});
	}
});
