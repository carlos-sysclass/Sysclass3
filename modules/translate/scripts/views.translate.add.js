$SC.module("views.translate.add", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var translateModelClass = $SC.module("models.translate").translateModelClass;
		var translateModel = new translateModelClass();

		var baseFormClass = app.module("views").baseFormClass;
		var translateFormView = new baseFormClass({el : "#form-translate", model: translateModel});

		// EXPORTS
		this.translateModel = translateModel;
	});
});