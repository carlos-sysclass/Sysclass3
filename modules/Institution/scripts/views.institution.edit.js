$SC.module("views.institution.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;

	this.startWithParent = false;

	mod.on("start", function(opt) {

		var socialInfoViewClass = Backbone.View.extend({
			events : {
				"click .social-addanother" : "addNewSocialInfo"
			},
			socialInfoDialog : app.module("dialogs.institution.social"),
			initialize : function() {
				if (!this.socialInfoDialog.started) {
					this.socialInfoDialog.start();
				}
				this.socialInfoDialog.setModel(
					opt.module.getModel()
				);



			},
			addNewSocialInfo : function() {
				this.socialInfoDialog.open();
			}
		});

		var socialInfoView = new socialInfoViewClass({
			el: "#additional-address"
		});





		/*
		var bindTableEvents = function(table) {
			this.listenTo(table, "draw.datatables", function(row, data) {
	    		//console.warn('DRAW', row, data);
				var exists = userCollection.findWhere({user_id: data['id']});

				var innerInput = $(row).find(".datatable-option-switch");

				if (typeof exists != "undefined") {
					innerInput.bootstrapSwitch('state', true, true);
				} else {
					innerInput.bootstrapSwitch('state', false, true);
				}
			}.bind(this));


			this.listenTo(table, "switchItem.datatables", function(el, data, state) {
				var userSwitchModelClass = Backbone.Model.extend({
					urlRoot : "/module/courses/item/users/toggle"

				});

				var userSwitchModel = new userSwitchModelClass();
				userSwitchModel.set("course_id", entity_id);
				userSwitchModel.set("user_id", data['id']);
				userSwitchModel.save();

				var exists = userCollection.findWhere({user_id: data['id']});

				if (typeof exists != "undefined") {
					userCollection.remove(exists);
				} else {
					userCollection.add(userSwitchModel);
				}
				//console.warn('SWITCH', data, exists, this.collection.toJSON());
			}.bind(this));
		}.bind(this);

		console.warn(app.getTable('view-users'));


		// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
		var userCollectionClass = Backbone.Collection.extend({
			url : "/module/courses/items/users/default/" + JSON.stringify({course_id : entity_id})
		});
		var userCollection = new userCollectionClass();
		userCollection.fetch();
		*/
		/*
		app.module("utils.datatables").on("datatable:item:draw", function(row, data) {
			var exists = userCollection.findWhere({user_id: data['id']});
			if (typeof exists != "undefined") {
				$(row).find(".datatable-option-check").removeClass("btn-danger").addClass("btn-success");
			} else {
				$(row).find(".datatable-option-check").removeClass("btn-success").addClass("btn-danger");
			}
		});
		*/
		/*
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
		*/
	});

    $SC.module("crud.views.edit").on("start", function() {
        mod.start({
            module: this
        });
    });

	var bindTableEvents = function(table) {
		this.listenTo(table, "draw.datatables", function(row, data) {
    		//console.warn('DRAW', row, data);
			var exists = userCollection.findWhere({user_id: data['id']});

			var innerInput = $(row).find(".datatable-option-switch");

			if (typeof exists != "undefined") {
				innerInput.bootstrapSwitch('state', true, true);
			} else {
				innerInput.bootstrapSwitch('state', false, true);
			}
		}.bind(this));


		this.listenTo(table, "switchItem.datatables", function(el, data, state) {
			var userSwitchModelClass = Backbone.Model.extend({
				urlRoot : "/module/courses/item/users/toggle"

			});

			var userSwitchModel = new userSwitchModelClass();
			userSwitchModel.set("course_id", entity_id);
			userSwitchModel.set("user_id", data['id']);
			userSwitchModel.save();

			var exists = userCollection.findWhere({user_id: data['id']});

			if (typeof exists != "undefined") {
				userCollection.remove(exists);
			} else {
				userCollection.add(userSwitchModel);
			}
			//console.warn('SWITCH', data, exists, this.collection.toJSON());
		}.bind(this));
	}.bind(this);

	app.on("added.table", function(name, table) {
		if (name == "view-organization-list") {
			bindTableEvents(table);
		}
	}.bind(this));

});
