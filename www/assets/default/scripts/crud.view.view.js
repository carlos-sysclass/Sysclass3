$SC.module("crud.views.edit", function(mod, app, Backbone, Marionette, $, _) {
	this.config = $SC.module("crud.config").getConfig();
    this.module_id = this.config.module_id;
    this.route = this.config['route'];
    this.modelPrefix = this.config['model-prefix'];

	// MODELS
	mod.addInitializer(function() {
        //var tableViewClass = app.module("views").baseDatatableViewClass;
        var tableViewClass = $SC.module("utils.datatables").tableViewClass;

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

       	app.addTable("view-" + this.module_id, this.tableView);

	});
});
