// USE THIS MODULE AS SKELETON TO ANOTHER, 
// 
// TODO: CREATE A JS MODULE UNIFIED MODULE TYPE, DEFINE FIRST, CODE AFTER
$SC.module("dialogs.roles.users", function(mod, app, Backbone, Marionette, $, _) {

	mod.models = {
		roles : {
			user : Backbone.Model.extend({
				idAttribute : "user_id",
				urlRoot : function() {
					return "/module/roles/item/users/" + this.get("role_id")
				} 
			}),
			group : Backbone.Model.extend({
				idAttribute : "group_id",
				urlRoot : function() {
					return "/module/roles/item/groups/" + this.get("role_id")
				} 
			})
		}
	};

    mod.on("start", function(opt) {
      
        var tableViewClass = $SC.module("utils.datatables").tableViewClass;

        var rolesUserTableViewClass = tableViewClass.extend({
        	getTableItemModel : function(data) {
				if (data['type'] == 'user') {
					return itemModelClass = new mod.models.roles.user({
						'role_id' : this.getVar("role_id"),
						'user_id' : data['id']
					});
				} else {
					return itemModelClass = new mod.models.roles.group({
						'role_id' : this.getVar("role_id"),
						'group_id' : data['id']
					});
				}
        	}
        });
        
        var roleResourceDialogViewClass = Backbone.View.extend({
            initialize: function() {
                console.info('dialogs.roles.users/roleResourceDialogViewClass::initialize');
                //baseDatatableViewClass.prototype.initialize.apply(this);
                //
                var block_context = app.getResource("roles_users_context");
                this.tableView = new rolesUserTableViewClass({
	        		el : "#view-roles_users",
	        		datatable : {
	            		//"sAjaxSource": "{$T_MODULE_CONTEXT.ajax_source}",
	            		"aoColumns": block_context.datatable_fields
	        		}
	    		});

                var self = this;

                this.select2Obj = this.$(".select2-me");

                this.select2Obj.on("change", function (e, a,b,c,d) { 
                	var data = e.added;
                	var typeId = data.id.split(":");
                	var model = null;

                	if (typeId[0] == "user") {
	                	model = new mod.models.roles.user({
	                		role_id : this.model.get("id"),
	                		user_id : typeId[1]
	                	});

	                	model.save();

	                	this.tableView.refresh();
                	} else if (typeId[0] == "group") {
	                	model = new mod.models.roles.group({
	                		role_id : this.model.get("id"),
	                		group_id : typeId[1]
	                	});

	                	model.save();

	                	this.tableView.refresh();
                	}
				}.bind(this));


                /*
				// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
				var rolesResourcesCollectionClass = Backbone.Collection.extend({
					role_id : null,
					url : function() {
						return "/module/roles/items/users/default/" + JSON.stringify({role_id : this.role_id})
					}
				});

				this.collection = new rolesResourcesCollectionClass();

				this.listenTo(this.collection, "sync", function() {
					app.getTable("view-roles_users").redraw();
				});
				//this.collection.fetch();

				app.module("utils.datatables").on("datatable:item:draw", function(row, data) {
					var exists = this.collection.findWhere({resource_id: data['id']});

					console.warn({resource_id: data['id']}, exists);
					if (typeof exists != "undefined") {
						$(row).find(".datatable-option-check").removeClass("btn-danger").addClass("btn-success");
					} else {
						$(row).find(".datatable-option-check").removeClass("btn-success").addClass("btn-danger");
					}
				}.bind(this));
				
				app.module("utils.datatables").on("datatable:item:check", function(data,a,b,c) {
					console.warn(data,a,b,c);
					
					var resourceSwitchModelClass = Backbone.Model.extend({
						urlRoot : "/module/roles/item/resources/toggle"
					});

					var resourceSwitchModel = new resourceSwitchModelClass();
					resourceSwitchModel.set("role_id", this.model.get("id"));
					resourceSwitchModel.set("resource_id", data['id']);
					resourceSwitchModel.save();
				}.bind(this));
				*/

                //this.on("complete:save", this.close.bind(this));
            },
            open : function() {
                //this.select2Obj.off("select2:select");
            	this.select2Obj.select2("destroy");

                this.$el.modal("show");
                app.module("ui").handleSelect2(this.$el);
                /*
                this.select2Obj = this.$(".select2-me");

                this.select2Obj.on("change", function (e, a,b,c,d) { 
                	console.warn(e,a,b,c,d);
				});
				*/

            },
            close : function() {
                this.$el.modal("hide");
                this.trigger("hide.dialog");
            },
            setModel: function(model) {
            	// UPDATE 
            	this.model = model;

            	//data-url="/module/roles/items/users"

            	this.select2Obj.select2("destroy");
				this.select2Obj.data("url", "/module/roles/items/users/combo/" + JSON.stringify({
					role_id : this.model.get("id"),
					exclude : true
				}));


				this.tableView
					.putVar('role_id', this.model.get("id"))
					.setUrl("/module/roles/datasource/users/datatable/" + JSON.stringify({
						role_id : this.model.get("id")
					}) + "?block");

            	//app.module("ui").handleSelect2(this.$el);

            	//this.collection.role_id = this.model.get("id");
            	//this.collection.fetch();
            }
        });

        this.dialogView = new roleResourceDialogViewClass({
            el : "#dialogs-roles-users"
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
	*/
});
