// USE THIS MODULE AS SKELETON TO ANOTHER, 
// 
// TODO: CREATE A JS MODULE UNIFIED MODULE TYPE, DEFINE FIRST, CODE AFTER
$SC.module("block.enroll.users", function(mod, app, Backbone, Marionette, $, _) {

//	this.startWithParent = false;


    mod.on("start", function(opt) {

		var baseItemModelClass = app.module("crud.models").baseItemModelClass;

		mod.models = {
			enroll : baseItemModelClass.extend({
				response_type : "object",
	    		urlRoot : "/module/enroll/item/course"
			})

		};
      
        var tableViewClass = $SC.module("utils.datatables").tableViewClass;
        
        var enrollUserTableViewClass = tableViewClass.extend({
			removeItem : function(e) {
				e.preventDefault();
				var data = this.oTable._($(e.currentTarget).closest("tr"));

				var model = new mod.models.enroll(data[0]);
				
				this.oTable
					.api()
					.row( $(e.currentTarget).closest("tr") )
					.remove()
					.draw();

				model.destroy();
			}
        });
        
        var enrollUserBlockViewClass = Backbone.View.extend({
            initialize: function() {
                console.info('block.enroll.users/enrollUserBlockViewClass::initialize');
                //baseDatatableViewClass.prototype.initialize.apply(this);
                //
                var block_context = app.getResource("enroll_users_context");

                this.tableView = new enrollUserTableViewClass({
	        		el : "#view-" + block_context.block_id,
	        		datatable : {
	            		//"sAjaxSource": "{$T_MODULE_CONTEXT.ajax_source}",
	            		"aoColumns": block_context.datatable_fields
	        		}
	    		});

                var self = this;

                this.select2Obj = this.$(".select2-me");

                this.select2Obj.on("change", function (e,a,b,c,d) { 
                	var data = e.added;

					var enroll = new mod.models.enroll();
					enroll.set("user_id", this.model.get("id"));
					enroll.set("course_id", data['id']);

					enroll.save();

					this.tableView.refresh();
				}.bind(this));

            },
            setModel: function(model) {
            	// UPDATE 
            	this.model = model;

				this.tableView
					.putVar('user_id', this.model.get("id"))
					.setUrl("/module/enroll/items/course/datatable/" + JSON.stringify({
						user_id : this.model.get("id")
					}) + "?block");
            }
        });

        this.dialogView = new enrollUserBlockViewClass({
            el : "#block-enroll-user"
        });
    });

    app.module("crud.views.edit").on("start", function() {
        mod.dialogView.setModel(this.itemModel);
    });
});
