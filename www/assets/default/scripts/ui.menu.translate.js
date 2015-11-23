$SC.module("menu.translate", function(mod, app, Backbone, Marionette, $, _) {
	mod.on("start", function() {
		$("[data-callback='change-language']").click(function(e) {
			e.preventDefault();
			var translateLanguageModelClass = Backbone.Model.extend({
				urlRoot : "/module/translate/change/"
			});

			//var translateLanguageModelClass = translateLanguageModelClass;
			var language_id = $(this).data("language");
			var translateLanguageModel = new translateLanguageModelClass();
			translateLanguageModel.set("id", language_id);
			translateLanguageModel.save();
		});
	});
});