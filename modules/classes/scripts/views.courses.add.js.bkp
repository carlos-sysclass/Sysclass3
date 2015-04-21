$SC.module("views.courses.add", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var itemModelClass = $SC.module("models.courses").itemModelClass;
		var itemModel = new itemModelClass();

		var baseFormClass = app.module("views").baseFormClass;
		var newsFormView = new baseFormClass({el : "#form-course", model: itemModel});

		// EXPORTS
		//this.itemModel = itemModel;
	});
});
