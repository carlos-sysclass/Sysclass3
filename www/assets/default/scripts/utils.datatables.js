$SC.module("utils.datatables", function(mod, app, Backbone, Marionette, $, _) {
	//this.config = $SC.module("crud.config").getConfig();
    //this.module_id = this.config.module_id;

	// MODELS
	mod.addInitializer(function() {
        // USE AS SKELETON TO UNIFY A DATATABLE VIEW CLASS, FOR USE IN ALL SYSTEMS, PROVIDING:
        // ATTACH FOR EVENTS RECEIVE, USING app TO GET THE TABLE REFERENCE
        // MODEL DEFINITION, FOR DELETE, ALTER, ETC...
        // EXTENSABILITY (PROVIDE WAY TO ANOTHER MODULE TO EXTEND THE CLASS, AND OVERRIDE THESE METHODS)
        // AND SO ON....

        mod.tableViewClass = Backbone.View.extend({
        	_vars : {},
			events : {
				"click .datatable-option-remove" : "removeItem",
				"confirmed.bs.confirmation .datatable-option-remove" : "removeItem",
				"click .datatable-option-check" : "checkItem",
				"switchChange.bootstrapSwitch .datatable-option-switch" : "switchItem",
				"click .datatable-actionable" : "doAction",
				"click [data-datatable-action]" : "doAction"
			},
			options : null,
        	initialize : function(opt) {
		        //this.oOptions = $.extend($.fn.dataTable.defaults, datatabledefaults, opt.datatable);
		        this.options = opt;
		        var view = this;
		        var datatableOpt = {
		        	"rowCallback": function( row, data ) {
						mod.trigger("datatable:item:draw", row, data);

						view.trigger("draw.datatables", row, data);
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
        	destroy : function() {
				this.oTable.api().destroy();
        	},
        	recreate : function() {
        		/*
				var settings = this.oTable.api().init();
				
				$(this.oTable).dataTable(settings);
				*/
				this.initialize(this.options);
        	},
        	switchItem: function(e, state) {
				e.preventDefault();
				//console.warn(e);

				var data = this.oTable._($(e.currentTarget).closest("tr"));
				this.trigger("switchItem.datatables", $(e.currentTarget).closest("tr").get(0), _.first(data), state);
				/*
				if ($(e.currentTarget).hasClass("btn-danger")) {
					$(e.currentTarget).removeClass("btn-danger").addClass("btn-success");
				} else {
					$(e.currentTarget).removeClass("btn-success").addClass("btn-danger");
				}
				*/
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
				} else {
					var data = this.oTable._($(e.currentTarget).closest("tr"));
					this.trigger("action.datatables", $(e.currentTarget).closest("tr").get(0), _.first(data), $(e.currentTarget).data("datatableAction"), e);


					var item = $(e.currentTarget);
					this.trigger("action.datatable", _.first(data), item);

					//var data = this.oTable._($(e.currentTarget).closest("tr"));
					//mod.trigger("datatable:item:click", item, _.first(data));
				}
			},
			removeItem : function(e) {
				
				e.preventDefault();
				var data = this.oTable._($(e.currentTarget).closest("tr"));



				var model = this.getTableItemModel(data[0]);

				console.warn(model.toJSON());

				this.oTable
					.api()
					.row( $(e.currentTarget).closest("tr") )
					.remove()
					.draw();

				if (model) {
					model.destroy();
				} else {
					this.trigger("action.datatables", $(e.currentTarget).closest("tr").get(0), _.first(data), "remove");
				}
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
        	setUrl : function(url) {
        		this.oTable.api().ajax.url(url).load();
        		return this;
        	},
	        refresh : function() {
	            this.oTable.api().ajax.reload();
	        },
        	redraw : function() {
        		this.oTable.api().draw(false);
        	},

        	/**
        	 * Get a variable from view bags
        	 * @param  {[type]} name [description]
        	 * @return {[type]}      [description]
        	 */
			getVar : function(name) {
				return this._vars[name];
			},
			/**
			 * Put a variable inside view bag's
			 * @param  {[type]} name  [description]
			 * @param  {[type]} value [description]
			 * @return {[type]}       [description]
			 */
			putVar : function(name, value) {
				this._vars[name] = value;
				return this;
			},
        	// 
        	/**
        	 * RETURN THE MODEL BASED ON ROW DATA, CAN BE OVERRIDEN
        	 * @param  {array} data the raw JSON data from selected / clicked row.
        	 * @return {object}     THe Backbone.Model with data assigned
        	 */
        	getTableItemModel : function(data) {
        		var itemModelClass = app.module("crud.models").itemModelClass;
        		if (_.isUndefined(itemModelClass)) {
        			return false;
        		}
        		return new itemModelClass(data);
        	},
        	/**
        	 * Call the "destroy" method on referenced model 
        	 * @param  {Event} e JsEvent from button click
        	 * @return {null}   
        	 */

			/*
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
			*/

        });
	});
});
