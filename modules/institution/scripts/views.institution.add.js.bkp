$SC.module("views.institution.add", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var itemModelClass = $SC.module("models.institution").itemModelClass;
		var itemModel = new itemModelClass();

		var baseFormClass = app.module("views").baseFormClass;
		var newsFormView = new baseFormClass({el : "#form-institution", model: itemModel});

		// EXPORTS
		//this.itemModel = itemModel;
	});
});