$SC.module("menu.translate", function(mod, app, Backbone, Marionette, $, _) {
	mod.addInitializer(function() {

		$("[data-callback='change-language']").click(function() {
			var translateLanguageModelClass = $SC.module("models.translate").translateLanguageModelClass;
			var language_id = $(this).data("language");
			var translateLanguageModel = new translateLanguageModelClass();
			translateLanguageModel.set("id", language_id);
			translateLanguageModel.save();
		});
	});
});