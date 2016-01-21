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

		app.on("added.table", function(name, table) {
			if (name == "view-users") {
				mod.bindTableEvents(table);
			}
		}.bind(this));

		var userGroupsSwitchModelClass = Backbone.Model.extend({
			urlRoot : "/module/groups/item/users/switch"
		});
		/*
		app.module("utils.datatables").on("datatable:item:draw", function(row, data) {
			console.warn(row, data);
			var exists = userGroupsCollection.findWhere({user_id: data['id']});
			if (typeof exists != "undefined") {
				$(row).find(".datatable-option-check").removeClass("btn-danger").addClass("btn-success");
			} else {
				$(row).find(".datatable-option-check").removeClass("btn-success").addClass("btn-danger");
			}
		});

		app.module("utils.datatables").on("datatable:item:check", function(data) {



			var userGroupsSwitchModel = new userGroupsSwitchModelClass();
			userGroupsSwitchModel.set("group_id", entity_id);
			userGroupsSwitchModel.set("user_id", data['id']);
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
		*/

 		mod.bindTableEvents = function(table) {
 			/*
			this.listenTo(this.collection, "sync", function() {
				table.redraw();
			});
			*/
        	this.listenTo(table, "draw.datatables", function(row, data) {
				var exists = userGroupsCollection.findWhere({user_id: data['id']});

				//var exists = this.collection.findWhere({resource_id: data['id']});

				var innerInput = $(row).find(".datatable-option-switch");

				if (typeof exists != "undefined") {
					innerInput.bootstrapSwitch('state', true, true);
				} else {
					innerInput.bootstrapSwitch('state', false, true);
				}
			}.bind(this));

			this.listenTo(table, "switchItem.datatables", function(el, data, state) {
				var resourceSwitchModelClass = Backbone.Model.extend({
					urlRoot : "/module/roles/item/resources/toggle"
				});

				var exists = userGroupsCollection.findWhere({user_id: data['id']});

				if (typeof exists != "undefined") {
					userGroupsCollection.remove(exists);
				} else {
					userGroupsCollection.add([{
						"group_id"		: entity_id,
						"user_id"	: data['id']
					}]);
				}
				//console.warn('SWITCH', data, exists, this.collection.toJSON());

				var userGroupsSwitchModel = new userGroupsSwitchModelClass();
				userGroupsSwitchModel.set("group_id", entity_id);
				userGroupsSwitchModel.set("user_id", data['id']);
				userGroupsSwitchModel.save();

			}.bind(this));
        };
	});

});
