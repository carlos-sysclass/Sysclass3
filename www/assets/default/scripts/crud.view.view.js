$SC.module("crud.views.edit", function(mod, app, Backbone, Marionette, $, _) {
	this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;
    this.route = this.config['route'];
    this.modelPrefix = this.config['model-prefix'];

	// MODELS
	mod.addInitializer(function() {
        var tableViewClass = app.module("views").baseDatatableViewClass;
        /*
        var tableViewClass = Backbone.View.extend({
			events : {
				"confirmed.bs.confirmation .datatable-option-remove" : "removeItem"
			},
        	initialize : function(opt) {
		        //this.oOptions = $.extend($.fn.dataTable.defaults, datatabledefaults, opt.datatable);
		        if (opt.datatable != undefined) {
		        	this.oTable = this.$el.dataTable(opt.datatable);
		        } else {
		        	this.oTable = this.$el.dataTable();
		        }
		        this.$el.closest(".dataTables_wrapper").find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
		        this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
		        this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').select2(); // initialize select2 dropdown
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
			}
        });
        */

        if (typeof this.modelPrefix == "undefined") {
        	var sAjaxSource = "/module/" + this.module_id + "/items/me/datatable";
        } else {
        	var sAjaxSource = "/module/" + this.module_id + "/" + this.modelPrefix + "/items/me/datatable";
        }

        this.tableView = new tableViewClass({
        	el : "#view-" + this.module_id,
        	datatable : {
		        "sAjaxSource": sAjaxSource,
		        "aoColumns": this.config.datatable_fields
        	}
       	});
	});
});
