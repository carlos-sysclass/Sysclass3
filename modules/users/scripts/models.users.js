$SC.module("models.users", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		this.itemModelClass = Backbone.Model.extend({
			urlRoot : "/module/users/item/me"
		});
	});

});
