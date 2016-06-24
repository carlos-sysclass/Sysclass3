// USE THIS MODULE AS SKELETON TO ANOTHER, 
// 
// TODO: CREATE A JS MODULE UNIFIED MODULE TYPE, DEFINE FIRST, CODE AFTER
$SC.module("dialogs.enroll.users", function(mod, app, Backbone, Marionette, $, _) {

	var baseModel = app.module("models").getBaseModel();

	mod.models = {
		enroll : {
			user : baseModel.extend({
				response_type : "object",
				//idAttribute : "user_id",
				urlRoot : "/module/enroll/item/users"
				/*
				urlRoot : function() {
					return "/module/enroll/item/users/" + this.get("role_id")
				} 
				*/
			}),
			group : Backbone.Model.extend({
				idAttribute : "group_id",
				urlRoot : function() {
					return "/module/enroll/item/groups/" + this.get("role_id")
				} 
			})
		}
	};

    mod.on("start", function(opt) {
      
        var tableViewClass = $SC.module("utils.datatables").tableViewClass;

        var enrollUserTableViewClass = tableViewClass.extend({
        	getTableItemModel : function(data) {
				if (data['type'] == 'user') {
					return itemModelClass = new mod.models.enroll.user({
						'id'   		: data['id'],
						//'enroll_id' : this.getVar("enroll_id"),
						//'course_id' : this.getVar("course_id"),
						'user_id'   : data['user_id']
					});
				} else {
					return itemModelClass = new mod.models.enroll.group({
						'role_id' : this.getVar("role_id"),
						'group_id' : data['id']
					});
				}
        	}
        });
        
        var enrollUserDialogViewClass = Backbone.View.extend({
            initialize: function() {
                console.info('dialogs.enroll.users/roleResourceDialogViewClass::initialize');
                //baseDatatableViewClass.prototype.initialize.apply(this);
                //
                var block_context = app.getResource("enroll_users_context");
                this.tableView = new enrollUserTableViewClass({
	        		el : "#view-enroll_users",
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
	                	model = new mod.models.enroll.user({
	                		enroll_id : this.model.get("enroll_id"),
	                		course_id : this.model.get("course_id"),
	                		user_id : typeId[1]
	                	});

	                	model.save();

	                	this.tableView.refresh();
                	} else if (typeId[0] == "group") {
	                	model = new mod.models.enroll.group({
	                		enroll_id : this.model.get("enroll_id"),
	                		course_id : this.model.get("course_id"),
	                		group_id : typeId[1]
	                	});

	                	model.save();

	                	this.tableView.refresh();
                	}
				}.bind(this));
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

            	this.select2Obj.select2("destroy");
				this.select2Obj.data("url", "/module/enroll/datasource/users/combo/" + JSON.stringify({
					enroll_id : this.model.get("id"),
					exclude : true
				}));


				this.tableView
					.putVar('enroll_id', this.model.get("enroll_id"))
					.putVar('course_id', this.model.get("course_id"))
					.setUrl("/module/enroll/datasource/users/datatable/" + JSON.stringify({
						enroll_id : this.model.get("enroll_id")
					}) + "?block");

            	//app.module("ui").handleSelect2(this.$el);

            	//this.collection.role_id = this.model.get("id");
            	//this.collection.fetch();
            }
        });

        this.dialogView = new enrollUserDialogViewClass({
            el : "#dialogs-enroll-users"
        });

    });
});
