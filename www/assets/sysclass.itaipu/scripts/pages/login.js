$SC.module("ui.pages", function(mod, app, Backbone, Marionette, $, _){
	this.startWithParent = false;
	this.on("start", function() {
//		alert(Modernizr.video.ogg);
//		alert(Modernizr.video.webm);
//		alert(Modernizr.video.h264);
		var isMobile = app.module("ui").mobile;
		var sources = {};
		if (Modernizr.video.h264 || !isMobile) { // @todo Check for flash support
			sources['video/webm'] = '/assets/sysclass.itaipu/video/login-background.mp4';
		}

		if (Modernizr.video.webm) {
			sources['video/webm'] = '/assets/sysclass.itaipu/video/login-background.webm';
		}
		//if (Modernizr.video.ogg) {
		//	sources['video/ogg'] = '/files/login-background.ogv';
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
	});
});
