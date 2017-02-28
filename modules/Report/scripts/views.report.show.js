$SC.module("views.report.show", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.startWithParent = false;
	/*
    var baseModelClass = app.module("models").getBaseModel();

    mod.models = {
        groups : {
        	users : baseModelClass.extend({
            	idAttribute : "user_id",
            	response_type : "object",
				sync : function(method, model, options) {
		            if (method == "update") {
		            	method = "create";
		            }
		            return baseModelClass.prototype.sync.apply(this, [method, model, options]);
		        },
            	urlRoot : function() {
            		return "/module/groups/item/users/" + this.get("group_id")
            	}
            })
        }
    };
    */

	mod.on('start', function(opt) {
		var tableViewClass = app.module("utils.datatables").tableViewClass;
		
		var dynamicHeaderTableViewClass = tableViewClass.extend({
			headerTemplate : _.template($("#dynamic-table-header-item-template").html(), null, {variable : 'field'}),
			initialize : function(opt) {
		        if ($.fn.dataTable.isDataTable(this.$el)) {
		        	this.getApi().clear(true);
		        	this.getApi().destroy(false);
		        }

				this.createHeader(opt.fields);

				opt.datatable_fields = opt.fields;

				tableViewClass.prototype.initialize.apply(this, [opt]);
			},
			createHeader : function(fields) {
				this.$("thead").empty();

				var row = $("<tr></tr>");

				for (var i in fields) {
					row.append(
						this.headerTemplate(fields[i])
					);
				}

				row.appendTo(this.$("thead"));
			}
		});

		var queryBuilderViewClass = Backbone.View.extend({
			dynamicField : "filters",
			initialize : function() {
				if (!_.isUndefined(this.model.get("datasource_id"))) {
					this.render();
				}
			},
			render : function() {
				this.loadDatasource(this.model.get("datasource_id"));
			},
			loadDatasource : function(name) {
				//this.model.unset("fields");
				this.clearDatasource();

	            return $.ajax({
                    url: "/module/report/datasource/" + name,
                    type: "GET",
                    success: this.renderDatasource.bind(this, name),
	                error: function( XMLHttpRequest, textStatus, errorThrown) {
	                        
					}
	            });
			},
			clearDatasource : function() {
			},
			renderDatasource(name, info) {
				// RENDER FILTER
				this.$(".datasource-title").html(info.title);

				

				var definition = this.model.get(this.dynamicField);

				var report_fields = this.model.get("report_fields");
				// REMOVE NOT MATCHED FIELDS
				var fields = _.filter(info.fields, function(field) {
					return ($.inArray(field.name, report_fields) != -1);
				});

				if (_.size(fields) == 0) {
					for(var i in info.fields) {
						if (info.fields[i].freeze || info.fields[i].default) {
							fields.push(info.fields[i]);
						}
					}
				}

				info.datatable.aoColumns = fields;

			
		        this.tableView = new dynamicHeaderTableViewClass({
		            el : "#report-datatable",
		            datatable : _.extend(info.datatable, {
		            	pageLength : -1,
		            	buttons: [
				        	'excel', 'csv',  {
				                extend: 'print',
				                autoPrint: false,
				                title : this.model.get("name")
				            }
				    	],
				    	sAjaxSource : info.datatable.sAjaxSource + "/" + JSON.stringify(definition)
		            }),
		            fields : fields
		        });

				this.startDatasource();
			},
			startDatasource : function() {
				/*
				var definition = this.model.get(this.dynamicField);

				if (_.has(definition, 'rules') && _.size(definition.rules) > 0) {
					this.tableView.setFilter(definition);
				}
				*/
				// FIELDS EVENTS
				this.renderFields();
			},
			renderFields : function() {
				var api = this.tableView.getApi();

				var fields = this.model.get("report_fields");

				api.columns().every(function () {
					//this.visible($.inArray(this.dataSrc(), fields) != -1);
				});
			}

		});

		this.listenToOnce(opt.module.getModel(), "sync", function() {
			this.queryBuilderView = new queryBuilderViewClass({
				el : '#tab-report-definition',
				model: opt.module.getModel()
			});
		}.bind(this));
		

	});


	app.module("crud.views.edit").on("start", function() {
		mod.start({
			module : this
		});
	});


});
