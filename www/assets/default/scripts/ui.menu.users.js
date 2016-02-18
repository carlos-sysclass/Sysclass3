$SC.module("menu.users", function(mod, app, Backbone, Marionette, $, _) {


	alert('ddd');
	var usersMenuViewClass = Backbone.View.extend({
	    events: {
	    	"click [data-entity-id]" : 'setCourse'
	    },
	    setCourse : function(e,a,b,c,d) {
	    	console.info('menu.users/usersMenuViewClass::setCourse');
	    	alert($(e.currentTarget).data("entity-id"));
	    	this.model.set("course_id", $(e.currentTarget).data("entity-id"));

	    }
  	});

	mod.on("start", function() {
		this.listenToOnce(app.userSettings, "sync", function(model, data, options) {
			this.usersWidgetView = new usersMenuViewClass({
				el: '#users-topbar-menu',
				model : app.userSettings,
			});
		}.bind(this));
	});
});