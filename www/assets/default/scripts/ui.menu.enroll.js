$SC.module("menu.enroll", function(mod, app, Backbone, Marionette, $, _) {

	mod.on("start", function() {
		var enrollMenuViewClass = Backbone.View.extend({
		    events: {
		    	"click a" : 'showDialog'
		    },
		    showDialog : function(e,a,b,c,d) {
		    	console.info('menu.enroll/enrollMenuViewClass::moveProgram');

		    	// OPEN DIALOG MODULE
		    	$SC.module("dialogs.enroll.avaliable").dialogView.open();
		    }
	  	});

		this.contentMenuView = new enrollMenuViewClass({
			el: '#enroll-topbar-menu'
		});

		mod.listenToOnce(app.userSettings, "sync", function(model, data, options) {
			alert(1);
			if (model.get("programs_count") == 0) {
				this.contentMenuView.showDialog();
			}
		}.bind(this));

	});
});