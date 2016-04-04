$SC.module("ui.pages", function(mod, app, Backbone, Marionette, $, _){
	this.startWithParent = false;

	this.on("start", function() {
		var isMobile = app.module("ui").mobile;
		var sources = {};
		//if (Modernizr.video.webm) {
		//	sources['video/webm'] = '/login-background.webm';
		//}
		//if (Modernizr.video.ogg) {
		//	sources['video/ogg'] = '/files/login-background.ogv';
		//}
		//if (Modernizr.video.h264 || !isMobile) { // @todo Check for flash support
		    sources['video/mp4'] = '/assets/sysclass.itaipu/video/login-background.mp4';
			sources['video/webm'] = '/assets/sysclass.itaipu/video/login-background.webm';
		//}
		//sources['video/flv'] = '/login-background.flv';

		if (!_.isEmpty(sources)) {
			var BV = new $.BigVideo({
				useFlashForFirefox:false,
				sources : sources
			});
			BV.init();
		    BV.show(null, {ambient: true});
		} else {
			$("body.login").addClass("backstrech-me");
			app.module("ui").handleBackstrech(document);
		}

		var handleSignup = function() {
			$('.signup-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            /*
	            rules: {
	                name: {
	                    required: true
	                },
	                surname: {
	                    required: true
	                },
	                email: {
	                    required: false,
	                    email : true

	                },
	                language_id
	            },
	            messages: {
	                username: {
	                    required: "Username is required."
	                },
	                password: {
	                    required: "Password is required."
	                }
	            },
	            */

	            invalidHandler: function (event, validator) { //display error alert on form submit
	                $('.alert-danger', $('.signup-form')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },
	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },
	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },
	            submitHandler: function (form) {
	                form.submit();
	            }
	        });

	        $('.signup-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.signup-form').validate().form()) {
	                    $('.signup-form').submit();
	                }
	                return false;
	            }
	        });
		};

		handleSignup();

	});
});
