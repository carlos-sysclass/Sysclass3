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
        	_filter : {},
			events : {
				"click .datatable-option-remove" : "removeItem",
				"confirmed.bs.confirmation .datatable-option-remove" : "removeItem",
				"click .datatable-option-check" : "checkItem",
				"switchChange.bootstrapSwitch .datatable-option-switch" : "switchItem",
				"click .datatable-actionable" : "doAction",
				"click [data-datatable-action]" : "doAction",
				"click tbody td" : "_cellClickHandler"
			},
			options : null,
        	initialize : function(opt) {
		        //this.oOptions = $.extend($.fn.dataTable.defaults, datatabledefaults, opt.datatable);

		        if ($.fn.dataTable.isDataTable(this.$el)) {
		        	this.$el.DataTable().destroy();
		        }

		        this.options = opt;
		        var view = this;
		        var datatableOpt = {
		        	dom : "<'row'<'col-lg-4 col-md-4 col-sm-12'l><'col-lg-4 col-md-4 col-sm-12 text-center'B><'col-lg-4 col-md-4 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		        	rowCallback: function( row, data ) {
						mod.trigger("datatable:item:draw", row, data);

						view.trigger("draw.datatables", row, data);
		        	},
					fnFooterCallback: function ( row, data, start, end, display ) {
			            var api = this.api(), data;
			 
			            // Remove the formatting to get integer data for summation
			            var intVal = function ( i ) {
			                return typeof i === 'string' ?
			                    i.replace(/[\$,]/g, '')*1 :
			                    typeof i === 'number' ?
			                        i : 0;
			            };


						//var columns = api.columns("[data-totals='sum']", { page: 'current'});
						var columns = api.columns("[data-totals='sum']");

						columns.every( function () {
						  var data = this.data();
						  var index = this.index();
						  
						  total = this.data()
						    .reduce( function (a, b) {
								return parseFloat(a) + parseFloat(b);
						    }, 0 );

						  // Update footer
						  $( this.footer() ).html(
						    total // +' ( '+ total +' total)'
						  );
						  
						});
						var columns = api.columns("[data-totals='percent']");

						columns.every( function () {
						  var data = this.data();
						  var index = this.index();
						  
						  total = this.data()
						    .reduce( function (a, b) {
								return parseFloat(a) + parseFloat(b);
						    }, 0 );

						  // Update footer
						  $( this.footer() ).html(
						  	app.module("views").formatValue(total, 'percent-custom', '0.##')
						  );
						  
						});
					},
					colReorder : true,
					language: {
			            buttons: {
			                colvis: 'Column'
			            }
			        },
				    buttons: [
				        'colvis', 'excel', 'csv'
				    ],
				    fixedHeader: true
		        };


		        if (opt.datatable != undefined) {
		        	opt.datatable = _.extend(datatableOpt, opt.datatable);
		        } else {
		        	opt.datatable = datatableOpt;
		        }

		        if (_.has(opt.datatable, 'datatable_fields')) {
		        	opt.datatable.aoColumns = opt.datatable.datatable_fields;
		        	 //= opt.datatable_fields;
		        }

		        if (_.isObject(opt.datatable.aoColumns)) {
		        	opt.datatable.aoColumns = _.toArray(opt.datatable.aoColumns);
		        }

		        if (_.has(opt, 'url')) {
		        	opt.datatable.sAjaxSource = opt.url;
		        	 //= opt.datatable_fields;
		        }

		        console.warn(opt.datatable);

		        this.oTable = this.$el.dataTable(opt.datatable);
		        this.getApi().on("init", this.startScrollUI.bind(this));

		        this._baseUrl = this.oTable.api().ajax.url();

		        this.$el.closest(".dataTables_wrapper").find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
		        this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
		        this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').select2(); // initialize select2 dropdown

		        // DEFAULT EVENT HANDLERS
				this.listenTo(this, "cellclick.datatable", this.onCellClick.bind(this));
        	},
        	startScrollUI : function() {
        		if (this.options.scrollY) {
        			var defaults = {
					    size: '7px',
					    wrapperClass: "slimScrollDiv",
					    color: '#a1b2bd',
					    railColor: '#333',
					    position: 'right',
					    alwaysVisible: false,
					    railVisible: false,
					    disableFadeOut: true,
					    allowPageScroll : true,
					    wheelStep : 2
					};
        			var scrollOptions = _.extend(defaults, this.options.slimScroll);
        			this.$el.closest(".dataTables_scrollBody").slimScroll(scrollOptions);
        		}
        	},
        	destroy : function() {
				this.oTable.api().destroy();
        	},
        	getApi : function() {
        		return this.oTable.api();
        	},
        	recreate : function(options) {
        		if (_.isUndefined(options)) {
        			options = this.options;
        		}
        		/*
				var settings = this.oTable.api().init();
				
				$(this.oTable).dataTable(settings);
				*/
				this.initialize(options);
        	},
        	switchItem: function(e, state) {
				e.preventDefault();

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
				//var data = this.oTable._($(e.currentTarget).closest("tr"));

				var tr = $(e.currentTarget).closest("tr");
				var row = this.getApi().row(tr);
        		var data = row.data();				
				var model = this.getTableItemModel(data);

				row
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
        	setFilter : function(filter) {
        		var url = this._baseUrl + "/" + JSON.stringify(filter);
				return this.setUrl(url).redraw();
        	},
        	clearFilter : function(filter) {
        		if (this.oTable.api().ajax.url() != this._baseUrl) {
        			var url = this._baseUrl;
					return this.setUrl(url).redraw();
				}
				return this;
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
			doSearch : function(e, text) {
			    this.getApi().search(
			        text,
			        false,
			        true
			    ).draw();
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
        	/**
        	 * RETURN THE MODEL BASED ON ROW DATA, CAN BE OVERRIDEN
        	 * @param  {array} data the raw JSON data from selected / clicked row.
        	 * @return {object}     The Backbone.Model with data assigned
        	 */
        	getTableItemModel : function(data) {
        		var itemModelClass = app.module("crud.models").itemModelClass;
        		if (_.isUndefined(itemModelClass)) {
        			return false;
        		}
        		return new itemModelClass(data);
        	},

        	// EVENT HANDLERS HELPERS
        	_cellClickHandler : function(e) {
        		var el = $(e.currentTarget)
				var tr = el.closest("tr");
				var row = this.getApi().row(tr);
        		var data = row.data();				
				var model = this.getTableItemModel(data);
       		
        		//var model = this.getTableItemModel(data);

        		this.trigger("cellclick.datatable", model, data, el);
        	},
        	onCellClick : function(model, data, el) {

        	}
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
