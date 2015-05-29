$SC.module("views.institution.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var NEWS_ID = views_institution_edit.id;

		var itemModelClass = $SC.module("models.institution").itemModelClass;
		var itemModel = new itemModelClass();

		if (typeof views_institution_edit != 'undefined') {
			if (typeof views_institution_edit.id != 'undefined') {
				var NEWS_ID = views_institution_edit.id;
				itemModel.set("id", NEWS_ID);

				var baseFormClass = app.module("views").baseFormClass;
				var newsFormView = new baseFormClass({el : "#form-institution", model: itemModel});

				itemModel.fetch();

				// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
				app.module("dialog.permission").on("before:save", function(model) {
					// SET MODEL PROPERTIES 
					//console.log(model);
					model.set("entity", {
						'type' 		: 'institution',
						'entity_id'	: itemModel.get("id")
					});
					return true;
				});

				app.module("dialog.permission").start({type : 'institution', 'entity_id' : NEWS_ID});
			}
		}

		// EXPORTS
		//this.itemModel = itemModel;
	});
});