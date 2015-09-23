$SC.module("views.roles.set_resources", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;

	mod.addInitializer(function() {
		// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
		var rolesResourcesCollectionClass = Backbone.Collection.extend({
			url : "/module/roles/items/resources/default/" + JSON.stringify({role_id : entity_id})
		});
		var rolesResourcesCollection = new rolesResourcesCollectionClass();
		rolesResourcesCollection.fetch();

		app.module("utils.datatables").on("datatable:item:draw", function(row, data) {
			var exists = rolesResourcesCollection.findWhere({resource_id: data['id']});
			if (typeof exists != "undefined") {
				$(row).find(".datatable-option-check").removeClass("btn-danger").addClass("btn-success");
			} else {
				$(row).find(".datatable-option-check").removeClass("btn-success").addClass("btn-danger");
			}
		});

		app.module("utils.datatables").on("datatable:item:check", function(data,a,b,c) {
			console.warn(data,a,b,c);
			
			var resourceSwitchModelClass = Backbone.Model.extend({
				urlRoot : "/module/roles/item/resources/toggle"
			});
			var resourceSwitchModel = new resourceSwitchModelClass();
			resourceSwitchModel.set("role_id", entity_id);
			resourceSwitchModel.set("resource_id", data['id']);
			resourceSwitchModel.save();

			var exists = rolesResourcesCollection.findWhere({resource_id: data['id']});
			if (typeof exists != "undefined") {
				// REMOVE FROM COLLECTION
				rolesResourcesCollection.remove(exists);
			} else {
				rolesResourcesCollection.add(resourceSwitchModel);
			}
		});
	});
});
