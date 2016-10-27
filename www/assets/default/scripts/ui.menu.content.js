$SC.module("menu.content", function(mod, app, Backbone, Marionette, $, _) {


	var contentMenuViewClass = Backbone.View.extend({
	    events: {
	    	"click [data-entity-id]" : 'setCourse'
	    },
	    setCourse : function(e,a,b,c,d) {
	    	console.info('menu.users/contentMenuViewClass::moveProgram');
	    	//this.model.set("course_id", $(e.currentTarget).data("entity-id"));
			var program_id = parseInt($(e.currentTarget).data("entity-id"));
	    	this.collection.moveToProgram(program_id.toString());
	    }
  	});

	this.listenTo(app, "progress.started", function() {
		// LISTEN TO MODULE EVENTS TO UPDATE THE UI AS WELL
		this.contentMenuView = new contentMenuViewClass({
			el: '#users-topbar-menu',
			collection : app.module("portlet.content").programsCollection
		});
	});
});