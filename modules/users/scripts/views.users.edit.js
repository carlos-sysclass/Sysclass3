$SC.module("views.users.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {

		if (typeof views_users_edit != 'undefined') {
			if (typeof views_users_edit.id != 'undefined') {
				var ENTITY_ID = views_users_edit.id;
				var itemModelClass = $SC.module("models.users").userModelClass;

				var formEl = "#form-user";

			} else if (typeof views_users_edit.group_id != 'undefined') {
				var itemModelClass = $SC.module("models.users").userGroupModelClass;
				var ENTITY_ID = views_users_edit.group_id;

				var formEl = "#form-group-user";
			} else {
				return false;
			}

			var itemModel = new itemModelClass();

			itemModel.set("id", ENTITY_ID);

			var baseFormClass = app.module("views").baseFormClass;
			var formView = new baseFormClass({el : formEl, model: itemModel});

			itemModel.fetch();
		}

		// EXPORTS
		this.itemModel = itemModel;
	});
});
