$SC.module("views.groups.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;

	mod.addInitializer(function() {
		// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
		var userCollectionClass = Backbone.Collection.extend({
			url : "/module/courses/items/users/default/" + JSON.stringify({course_id : entity_id})
		});
		var userCollection = new userCollectionClass();
		userCollection.fetch();

		app.module("utils.datatables").on("datatable:item:draw", function(row, data) {
			var exists = userCollection.findWhere({user_id: data['id']});
			if (typeof exists != "undefined") {
				$(row).find(".datatable-option-check").removeClass("btn-danger").addClass("btn-success");
			} else {
				$(row).find(".datatable-option-check").removeClass("btn-success").addClass("btn-danger");
			}
		});

		app.module("utils.datatables").on("datatable:item:check", function(data) {

			var userSwitchModelClass = Backbone.Model.extend({
				urlRoot : "/module/courses/item/users/switch"
			});
			var userSwitchModel = new userSwitchModelClass();
			userSwitchModel.set("course_id", entity_id);
			userSwitchModel.set("user_id", data['id']);
			userSwitchModel.save();

			var exists = userCollection.findWhere({user_id: data['id']});
			if (typeof exists != "undefined") {
				// REMOVE FROM COLLECTION
				userCollection.remove(exists);
			} else {
				userCollection.add(userSwitchModel);
			}
		});
	});
});
