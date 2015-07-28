$SC.module("utils.datatables", function(mod, app, Backbone, Marionette, $, _) {
	//this.config = $SC.module("crud.config").getConfig();
    //this.module_id = this.config.module_id;

	// MODELS
	mod.addInitializer(function() {
        mod.tableViewClass = Backbone.View.extend({
			events : {
				"click .datatable-option-remove" : "removeItem",
				"click .datatable-option-check" : "checkItem",
				"click .datatable-actionable" : "doAction",
			},
        	initialize : function(opt) {
		        //this.oOptions = $.extend($.fn.dataTable.defaults, datatabledefaults, opt.datatable);
		        var datatableOpt = {
		        	"rowCallback": function( row, data ) {
						mod.trigger("datatable:item:draw", row, data);
		        	}
		        };

		        if (opt.datatable != undefined) {
		        	opt.datatable = _.extend(datatableOpt, opt.datatable);
		        	this.oTable = this.$el.dataTable(opt.datatable);
		        } else {
		        	opt.datatable = _.extend(datatableOpt, opt.datatable);
		        	this.oTable = this.$el.dataTable(datatableOpt);
		        }
		        this.$el.closest(".dataTables_wrapper").find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
		        this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
		        this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').select2(); // initialize select2 dropdown

        	},
        	checkItem: function(e) {
				e.preventDefault();
				var data = this.oTable._($(e.currentTarget).closest("tr"));
				//var itemModelClass = app.module("crud.models").itemModelClass;
				mod.trigger("datatable:item:check", _.first(data));

				if ($(e.currentTarget).hasClass("btn-danger")) {
					$(e.currentTarget).removeClass("btn-danger").addClass("btn-success");
				} else {
					$(e.currentTarget).removeClass("btn-success").addClass("btn-danger");
				}
        	},
			removeItem : function(e) {
				e.preventDefault();
				var data = this.oTable._($(e.currentTarget).closest("tr"));
				var itemModelClass = app.module("crud.models").itemModelClass;

				var self = this;
				var model = new itemModelClass(data[0]);
				model.destroy({
					success : function() {
						// TODO REMOVE DATA FROM DATATABLE TOO
						self.oTable
							.api()
							.row( $(e.currentTarget).closest("tr") )
							.remove()
							.draw();
					}
				});
			},
			doAction : function(e) {
				var item = $(e.currentTarget);
				if (item.data("actionUrl")) {
					var url = item.data("actionUrl");
					var method = "GET";
					if (item.data("actionMethod")) {
						method = item.data("actionMethod");
					}

	                $.ajax(url, { method : method } );
				}
			}
        });
	});
});
