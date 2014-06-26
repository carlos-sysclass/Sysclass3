$SC.module("views.translate.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var TRANSLATE_ID = views_translate_edit.id;

		var translateModelClass = $SC.module("models.translate").translateModelClass;
		var translateModel = new translateModelClass();

		if (typeof views_translate_edit != 'undefined') {
			if (typeof views_translate_edit.id != 'undefined') {
				var TRANSLATE_ID = views_translate_edit.id;
				translateModel.set("id", TRANSLATE_ID);

				var baseFormClass = app.module("views").baseFormClass;
				var translateFormView = new baseFormClass({el : "#form-translate", model: translateModel});

				translateModel.fetch();

				// HANDLE PERMISSION VIEWS, TO INJECT TRANSLATE OBJECT
				app.module("dialog.permission").on("before:save", function(model) {
					// SET MODEL PROPERTIES 
					//console.log(model);
					model.set("entity", {
						'type' 		: 'translate',
						'entity_id'	: translateModel.get("id")
					});
					return true;
				});

				//app.module("dialog.permission").setCollectionParam({data : });
				app.module("dialog.permission").start({type : 'translate', 'entity_id' : TRANSLATE_ID});

				//$SC.module("dialog.permission").conditionCollection.fetch();
			}
		}

		// EXPORTS
		this.translateModel = translateModel;
	});
});