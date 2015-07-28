$SC.module("views.groups.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;
	mod.addInitializer(function() {
		// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
		var userGroupsCollectionClass = Backbone.Collection.extend({
			url : "/module/groups/item/users/" + entity_id
		});
		var userGroupsCollection = new userGroupsCollectionClass();
		userGroupsCollection.fetch();

		app.module("utils.datatables").on("datatable:item:draw", function(row, data) {
			var exists = userGroupsCollection.findWhere({user_login: data['login']});
			if (typeof exists != "undefined") {
				$(row).find(".datatable-option-check").removeClass("btn-danger").addClass("btn-success");
			} else {
				$(row).find(".datatable-option-check").removeClass("btn-success").addClass("btn-danger");
			}
		});


		app.module("utils.datatables").on("datatable:item:check", function(data) {

			var userGroupsSwitchModelClass = Backbone.Model.extend({
				urlRoot : "/module/groups/item/users/switch"
			});
			var userGroupsSwitchModel = new userGroupsSwitchModelClass();
			userGroupsSwitchModel.set("group_id", entity_id);
			userGroupsSwitchModel.set("user_login", data['login']);
			userGroupsSwitchModel.save();

			var exists = userGroupsCollection.findWhere({user_login: data['login']});
			if (typeof exists != "undefined") {
				// REMOVE FROM COLLECTION
				userGroupsCollection.remove(exists);
			} else {
				userGroupsCollection.add([{
					"group_id"		: entity_id,
					"user_login"	: data['login']

				}]);
			}
		});

	});
});
