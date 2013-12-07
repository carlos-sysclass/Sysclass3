$SC.module("panel.users", function(mod, MyApp, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
	  	// VIEWS
	  	var viewClass = Backbone.View.extend({
		    el: $('#users-panel'),
		    events: {
		    },
		    initialize: function() {

		    },
		    render: function(collection) {
		    }
	  	});

	  	var editAvatarViewClass = Backbone.View.extend({
	  		el: $('#users-edit-avatar-dialog'),
		    events: {
		    },
		    initialize: function() {
		    },
		    render: function(collection) {
		    }
	  	});
		this.view = new viewClass();
		this.editAvatarView = new editAvatarViewClass();
	});
});