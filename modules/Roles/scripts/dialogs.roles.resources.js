$SC.module("dialogs.roles.resources", function(mod, app, Backbone, Marionette, $, _) {

    mod.on("start", function(opt) {
        //var baseDatatableViewClass = app.module("views").baseDatatableViewClass;
        //var roleResourceDialogViewClass = baseDatatableViewClass.extend({
        var roleResourceDialogViewClass = Backbone.View.extend({
            initialize: function() {
                console.info('dialogs.roles.resources/roleResourceDialogViewClass::initialize');
                //baseDatatableViewClass.prototype.initialize.apply(this);

                var self = this;

				// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
				var rolesResourcesCollectionClass = Backbone.Collection.extend({
					role_id : null,
					url : function() {
						return "/module/roles/items/resources/default/" + JSON.stringify({role_id : this.role_id})
					}
				});

				this.collection = new rolesResourcesCollectionClass();

				this.listenTo(this.collection, "sync", function() {
					app.getTable("view-roles_resources").redraw();
				});

				app.on("added.table", function(name, table) {
					if (name == "view-roles_resources") {
						this.bindTableEvents(table);
					}
				}.bind(this));
            },
            bindTableEvents : function(table) {
				this.listenTo(this.collection, "sync", function() {
					table.redraw();
				});

            	this.listenTo(table, "draw.datatables", function(row, data) {
					var exists = this.collection.findWhere({resource_id: data['id']});

					var innerInput = $(row).find(".datatable-option-switch");

					if (typeof exists != "undefined") {
						innerInput.bootstrapSwitch('state', true, true);
					} else {
						innerInput.bootstrapSwitch('state', false, true);
					}
				}.bind(this));

				this.listenTo(table, "switchItem.datatables", function(el, data, state) {
					var resourceSwitchModelClass = Backbone.Model.extend({
						urlRoot : "/module/roles/datasource/resources/toggle"
					});

					var exists = this.collection.findWhere({resource_id: data['id']});

					if (typeof exists != "undefined") {
						this.collection.remove(exists);
					} else {
						this.collection.add({resource_id: data['id']});
					}

					var resourceSwitchModel = new resourceSwitchModelClass();
					resourceSwitchModel.set("role_id", this.model.get("id"));
					resourceSwitchModel.set("resource_id", data['id']);
					resourceSwitchModel.save();

				}.bind(this));
            },
            open : function() {
                this.$el.modal("show");
            },
            close : function() {
                this.$el.modal("hide");
                this.trigger("hide.dialog");
            },
            setModel: function(model) {
            	// UPDATE 
            	this.model = model;

            	this.collection.role_id = this.model.get("id");
            	this.collection.fetch();
            }
        });
		/*        
        this.models = {
            roles : Backbone.Model.extend({
                defaults : {
                    name : "",
                    active : 1,
                    in_course : 0,
                    in_class : 0
                },
                urlRoot : "/module/roles/item/resource"
            })
        };
		*/
        this.dialogView = new roleResourceDialogViewClass({
            el : "#dialogs-roles-resources"
        });
    });
	// MODELS
	//this.config = $SC.module("crud.config").getConfig();
	//var entity_id = mod.config.entity_id;

	/*
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
	*/
});
