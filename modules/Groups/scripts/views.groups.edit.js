$SC.module("block.groups.definition", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.startWithParent = false;
	mod.on('start', function(opt) {

		var queryBuilderViewClass = Backbone.View.extend({
			dynamicField : "definition",
			initialize : function() {
				this.listenTo(this.model, "change:dynamic", this.render.bind(this));

				this.initializeDynamic();
			},
			initializeDynamic : function() {
				this.$(".jquery-builder").queryBuilder({
				  plugins: [
				    'bt-tooltip-errors',
				    'not-group'
				  ],
				  filters: [{
				    id: 'email',
				    label: 'Email',
				    type: 'string'
				  }, 
				  {
				    id: 'is_supplier',
				    label: 'Is Supplier',
				    type: 'integer',
				    input: 'radio',
				    values: {
				      1: 'Yes',
				      0: 'No'
				    },
				    operators: ['equal']
				  }]
				});

				var tableViewClass = app.module("utils.datatables").tableViewClass;
				var config = app.getResource("group-definition_context");
		        this.dynamicTableView = new tableViewClass({
		            el : "#view-group-definition",
		            datatable : config
		        });


				this.$(".jquery-builder").on('afterAddRule.queryBuilder', this.updateModel.bind(this));
				this.$(".jquery-builder").on('afterDeleteRule.queryBuilder', this.updateModel.bind(this));

				this.$(".jquery-builder").on('afterUpdateRuleValue.queryBuilder', this.updateModel.bind(this));
				this.$(".jquery-builder").on('afterUpdateGroupCondition.queryBuilder', this.updateModel.bind(this));
				
				this.$(".jquery-builder").on('afterAddGroup.queryBuilder', this.updateModel.bind(this));
				this.$(".jquery-builder").on('afterDeleteGroup.queryBuilder', this.updateModel.bind(this));

				//if (this.model.get("definition.rules"))
			},
			updateModel : function() {
				console.warn(arguments);
				console.warn(this.$(".jquery-builder").queryBuilder('getRules'));
				this.model.set(this.dynamicField, this.$(".jquery-builder").queryBuilder('getRules'));

				// CHECK IF IS NEED TO UPDATE THE TABLE
				var definition = this.model.get(this.dynamicField);
				if (_.has(definition, 'rules') && _.size(definition.rules) > 0) {
					this.dynamicTableView
						.setUrl("/module/users/items/me/datatable/" + JSON.stringify(definition) + "?block")
						.redraw();
				} else {
					// CLEAR THE TABLE
				}
			},
			render : function() {
				var definition = this.model.get(this.dynamicField);

				if (_.has(definition, 'rules') && _.size(definition.rules) > 0) {
					this.$(".jquery-builder").queryBuilder('setRules', definition);
				}
				//console.warn();

				if (this.model.get("dynamic") == 1) {
					this.renderDynamic();
				} else {
					this.renderStatic();
				}
			},
			renderStatic : function() {
				this.$(".dynamic-item-dynamic").addClass("hidden");
				this.$(".dynamic-item-static").removeClass("hidden");
			},
			renderDynamic : function() {
				this.$(".dynamic-item-static").addClass("hidden");
				this.$(".dynamic-item-dynamic").removeClass("hidden");
			}
		})

		//alert(1);
		// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
		//$('#jquery-builder').queryBuilder('destroy');
		

		var queryBuilderView = new queryBuilderViewClass({
			el : '#tab-group-definition',
			model: opt.module.getModel()
		})
	});


	app.module("crud.views.add").on("start", function() {
		mod.start({
			module : this
		});
	});
	app.module("crud.views.edit").on("start", function() {
		mod.start({
			module : this
		});
	});


});
