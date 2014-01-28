$SC.module("views.institution.view", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
        var tableViewClass = Backbone.View.extend({
			events : {
				"click .datatable-option-remove" : "removeItem"
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

		        /*
		        $('#sample_2_column_toggler input[type="checkbox"]').change(function(){
		            var iCol = parseInt($(this).attr("data-column"));
		            var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
		            oTable.fnSetColumnVis(iCol, (bVis ? false : true));
		        });
				*/
        	},
			removeItem : function(e) {
				e.preventDefault();
				var data = this.oTable._($(e.currentTarget).closest("tr"));
				var institutionModelClass = app.module("models.institution").institutionModelClass;

				var self = this;
				var model = new institutionModelClass(data[0]);
				model.destroy({
					success : function() {
						$(e.currentTarget).closest("tr").remove();
					}
				});
			}
        });

        var tableView = new tableViewClass({
        	el : "#sample_2",
        	datatable : {
		        "sAjaxSource": "/module/institution/items/me/datatable",
		        "aoColumns": [
		            { "mData": "id", "sClass" : "text-center"},
		            { "mData": "name" },
		            { "mData": "city"},
		            { "mData": "state"},
		            { "mData": "country_code", "sClass" : "text-center"},
		            { "mData": null, "sClass" : "text-center"},
		            { "mData": "options", 'sType' : 'table-options' },
		        ]
		        		/*
						<th class="text-center">#</th>
						<th>{translateToken value="Name"}</th>
						<th class="text-center">{translateToken value="City"}</th>
						<th class="text-center">{translateToken value="State"}</th>
						<th class="text-center">{translateToken value="Country"}</th>
						<th class="text-center">{translateToken value="Active"}</th>
						<th class="text-center">{translateToken value="Enrolled Users"}</th>
						<th class="text-center table-options">{translateToken value="Actions"}</th>	        
						*/
        	}
       	});
	});
});