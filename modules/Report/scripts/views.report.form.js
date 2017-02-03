$SC.module("views.report.form", function(mod, app, Backbone, Marionette, $, _) {
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
		var dialogViewClass = app.module("views").dialogViewClass;
		

		var fieldListDialogViewClass = dialogViewClass.extend({
			events : {
				"click [data-field-name]" : "toggleField"
			},
			fields: null,
			tabTemplate: _.template($("#report-field-add-category-tab-template").html(), null, {variable: "model"}),
			tabContentTemplate : _.template($("#report-field-add-category-tab-content-template").html(), null, {variable: "model"}),
			itemFieldTemplate : _.template($("#report-field-add-field-item-template").html(), null, {variable: "model"}),
			setFields : function(columns, selected) {
				this.columns = columns;
				this.selected = selected;
				this.render();
			},
			render : function() {
				// RENDER CATEGORY AND ITENS
				var categories = _.uniq(_.pluck(this.columns, "category"));
				console.warn(categories);

				this.$(".nav-tabs, .tab-content").empty();

				for (var i in categories) {
					var columns = _.where(this.columns, {category: categories[i]});
					this.renderCategory({
						name: categories[i],
						columns: columns
					});
				}


			},
			renderCategory : function(category) {
				this.$(".nav-tabs").append(
					this.tabTemplate(category)
				);

				this.$(".tab-content").append(
					this.tabContentTemplate(category)
				);


				for (var i in category.columns) {
					console.warn(category.columns[i]);
					this.$("#tab-report-add-field-" + category.name + " .report-field-list").append(
						this.itemFieldTemplate(_.extend(
							category.columns[i],
							{selected : $.inArray(category.columns[i].name, this.selected) != -1}
						))
					);
				}

				this.$(".nav-tabs a:first").tab('show');
			},
			toggleField : function(e) {
				var item = $(e.currentTarget);
				var field = item.data("field-name");

				if (item.hasClass("freeze")) {
					return true;
				}

				item.toggleClass("bg-green");
				this.trigger("tooglefield.report", field);
			}
		});
		/*
		var fieldListItemViewClass = Backbone.View.extend({
			tagName: "li",
			className: "col-md-3",
			template: _.template($("#report-field-item-template").html(),null,{variable: "model"}),
			render: function() {
				this.$el.html(this.template(this.model.toJSON()));
				return this;
			}
		});
		*/
		var fieldListViewClass = Backbone.View.extend({		
			events : {
				"click .report-addfield" : "openFieldDialog"
			},
			columns: null,
			fields: null,
			//childViews : {},

			initialize : function(opt1) {

				this.addFieldDialogView = new fieldListDialogViewClass({
					el: "#report-field-add-dialog"
				});
				this.listenTo(this.addFieldDialogView, "tooglefield.report", this.toggleField.bind(this));
				this.listenTo(this.model, "change:report_fields", this.render.bind(this));

				this.columns = opt1.columns;
				this.fields = this.model.get('report_fields');

				// FIRST 
				if (_.isUndefined(this.fields)) {
					this.fields = [];
					for(var i in this.columns) {
						if (this.columns[i].freeze || this.columns[i].default) {
							this.fields.push(this.columns[i].name);
						}
					}

					this.model.set("report_fields", this.fields);
				} else {
					this.render();
				}
				
				//this.render();
			},
			render : function() {
				this.$(".report-field-list").empty();

				this.fields = this.model.get('report_fields');

				console.warn(this.fields);

				var fieldnames = [];

				for(var i in this.columns) {
					if (this.columns[i].freeze || $.inArray(this.columns[i].name, this.fields) != -1 || (_.isNull(this.fields) && this.columns[i].default)) {
						fieldnames.push(this.columns[i].label);
						/*
						this.childViews[this.columns[i].name] = new fieldListItemViewClass({
							model : new Backbone.Model(this.columns[i])
						});
						this.$(".report-field-list").append(this.childViews[this.columns[i].name].render().el);
						*/
					}
				}
				this.$(".report-field-list").html(fieldnames.join(", "));
			},
			toggleField : function(field) {
				if ($.inArray(field, this.fields) != -1) {
					// REMOVE FROM VECTOR, DESTROY THE VIEW CLASS
					this.fields = _.without(this.fields, field);

					//this.childViews[field].remove();
				} else {
					// ADD TO VECTOR, DESTROY THE VIEW CLASS
					this.fields.push(field);
				}

				this.model.unset("report_fields", {
					silent : true
				});
				this.model.set("report_fields", this.fields);

			},
			openFieldDialog : function() {
				// ADD A CATEGORIZED LIST OS AVALIABLE FIELDS
				var avaliable = _.filter(this.columns, function(col) {
					return !col.freeze && $.inArray(col.name, this.fields) == -1;
				}, this);
				console.warn(this.columns);
				console.warn(avaliable);

				this.addFieldDialogView.setFields(this.columns, this.fields);
				this.addFieldDialogView.open();
			}
		});

		var dynamicHeaderTableViewClass = tableViewClass.extend({
			headerTemplate : _.template($("#dynamic-table-header-item-template").html(), null, {variable : 'field'}),
			initialize : function(opt) {
				this.createHeader(opt.fields);

				opt.datatable_fields = opt.fields;

				tableViewClass.prototype.initialize.apply(this, [opt]);
			},
			createHeader : function(fields) {
				this.$("thead").empty();

				var row = $("<tr></tr>");

				for (var i in fields) {
					console.warn(this.headerTemplate(fields[i]));
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
				this.listenTo(this.model, "change:datasource_id", this.render.bind(this));
				//this.listenTo(this.model, "change:datasource_id", this.render.bind(this));

				//this.initializeFilters();
				if (!_.isUndefined(this.model.get("datasource_id"))) {
					this.render();
				}
			},
			updateModel : function() {
				this.model.unset(this.dynamicField, {silent : true});
				this.model.set(this.dynamicField, this.$(".jquery-builder").queryBuilder('getRules'));

				// CHECK IF IS NEED TO UPDATE THE TABLE
				var definition = this.model.get(this.dynamicField);
				if (_.has(definition, 'rules') && _.size(definition.rules) > 0) {

					this.tableView.setFilter(definition);
				} else {
					this.tableView.clearFilter();
				}
			},
			render : function() {
				this.loadDatasource(this.model.get("datasource_id"));

				//if (this.model.get("dynamic") == 1) {
				//	this.renderDynamic();
				//} else {
					//this.renderStatic();
				//}
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
				this.stopListening(this.model, "change:report_fields");
				this.$(".jquery-builder").off('*.queryBuilder');
			},
			renderDatasource(name, info) {
				// RENDER FILTER
				alert(1);

				this.$(".datasource-title").html(info.title);

				this.filterView = this.$(".jquery-builder").queryBuilder({
					plugins: [
						'bt-tooltip-errors',
						//'not-group',
					],
					filters: info.filters 
				});
				
				this.fieldListView = new fieldListViewClass({
					el: "#field-list-container",
					columns: info.fields,
					model: this.model
				});

				info.datatable.aoColumns = info.fields;
				
		        this.tableView = new dynamicHeaderTableViewClass({
		            el : "#report-datatable",
		            datatable : info.datatable,
		            fields : info.fields
		        });

				this.startDatasource();
			},
			startDatasource : function() {
				var definition = this.model.get(this.dynamicField);

				if (_.has(definition, 'rules') && _.size(definition.rules) > 0) {
					this.$(".jquery-builder").queryBuilder('setRules', definition);
					this.tableView.setFilter(definition);
				}

				// FILTER EVENTS
				this.$(".jquery-builder").on('afterDeleteRule.queryBuilder', this.updateModel.bind(this));

				this.$(".jquery-builder").on('afterUpdateRuleValue.queryBuilder', this.updateModel.bind(this));
				this.$(".jquery-builder").on('afterUpdateGroupCondition.queryBuilder', this.updateModel.bind(this));
				
				this.$(".jquery-builder").on('afterAddGroup.queryBuilder', this.updateModel.bind(this));
				this.$(".jquery-builder").on('afterDeleteGroup.queryBuilder', this.updateModel.bind(this));

				// FIELDS EVENTS
				this.renderFields();

				this.listenTo(this.model, "change:report_fields", this.renderFields.bind(this));
			},
			renderFields : function() {
				var api = this.tableView.getApi();

				var fields = this.model.get("report_fields");

				console.warn(fields);

				api.columns().every(function () {
					console.warn(this.dataSrc(), $.inArray(this.dataSrc(), fields));
					this.visible($.inArray(this.dataSrc(), fields) != -1);
				});
			}

		});

		//alert(1);
		// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
		//$('#jquery-builder').queryBuilder('destroy');

		if (opt.waitSync) {

			this.listenToOnce(opt.module.getModel(), "sync", function() {
				this.queryBuilderView = new queryBuilderViewClass({
					el : '#tab-report-definition',
					model: opt.module.getModel()
				});
			}.bind(this));
		} else {
			this.queryBuilderView = new queryBuilderViewClass({
				el : '#tab-report-definition',
				model: opt.module.getModel()
			});
		}		
	});


	app.module("crud.views.add").on("start", function() {
		mod.start({
			module : this,
			waitSync : false
		});
	});
	app.module("crud.views.edit").on("start", function() {
		mod.start({
			module : this,
			waitSync : true
		});
	});


});
