$SC.module("views.courses.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {

		var itemModelClass = $SC.module("models.courses").itemModelClass;
		var itemModel = new itemModelClass();

		if (typeof views_courses_edit != 'undefined') {
			if (typeof views_courses_edit.id != 'undefined') {
				var COURSES_ID = views_courses_edit.id;
				itemModel.set("id", COURSES_ID);

				var baseFormClass = app.module("views").baseFormClass;
				var formView = new baseFormClass({el : "#form-course", model: itemModel});

				itemModel.fetch();

				// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
				app.module("dialog.permission").on("before:save", function(model) {
					// SET MODEL PROPERTIES
					//console.log(model);
					model.set("entity", {
						'type' 		: 'courses',
						'entity_id'	: itemModel.get("id")
					});
					return true;
				});

				//app.module("dialog.permission").setCollectionParam({data : });
				app.module("dialog.permission").start({type : 'courses', 'entity_id' : COURSES_ID});

				//$SC.module("dialog.permission").conditionCollection.fetch();
			}
		}

		// EXPORTS
		this.itemModel = itemModel;
	});
});
