$SC.module("block.groups.definition", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.startWithParent = false;

    var baseModelClass = app.module("models").getBaseModel();

    mod.models = {
        groups : {
        	users : baseModelClass.extend({
            	idAttribute : "user_id",
            	response_type : "object",
            	urlRoot : function() {
            		return "/module/groups/item/users/" + this.get("group_id");
            	}
            })
        }
    };

	mod.on('start', function(opt) {
		var tableViewClass = app.module("utils.datatables").tableViewClass;

		var staticTableViewClass = tableViewClass.extend({
        	getTableItemModel : function(info) {
        		if (!info) {
        			return false;
        		}
				return new mod.models.groups.users({
					'group_id' : this.getVar("group_id"),
                    'user_id' : info['id']
                });
        	}
		})


		var queryBuilderViewClass = Backbone.View.extend({
			dynamicField : "definition",
			initialize : function() {
				this.listenTo(this.model, "change:dynamic", this.render.bind(this));
				this.initializeStatic();
				this.initializeDynamic();
			},
			initializeStatic : function() {

				var config = app.getResource("group-static-definition_context");
		        this.staticTableView = new staticTableViewClass({
		            el : "#view-group-static-definition",
		            datatable : config,
		            url : "/module/groups/items/users/datatable/" + JSON.stringify({
                    	group_id : this.model.get("id")
                	})
		        });

		        this.staticTableView.putVar("group_id", this.model.get("id"));

                var self = this;

                this.select2Obj = this.$(".select2-me.user-search");

                this.select2Obj.select2("destroy");
                
                this.select2Obj.data("url", 
                    this.select2Obj.data("url") + JSON.stringify({
                        group_id : this.model.get("id")
                    })
                );

                app.module("ui").handleSelect2(this.$el);


                this.select2Obj.on("change", function (e, a,b,c,d) { 
                    var data = e.added;

                    var model = new mod.models.groups.users({
                        'group_id' : this.model.get("id"),
                        'user_id' : data['id']
                    });
                    model.save();

                    this.staticTableView.refresh();
                }.bind(this));
			},
			initializeDynamic : function() {
				this.$(".jquery-builder").queryBuilder({
				  plugins: [
				    'bt-tooltip-errors',
				    //'not-group',

				  ],
				  filters: [{
				    id: 'email',
				    label: 'Email',
				    type: 'string',
				    no_invert: true
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
				    operators: ['equal'],
				    no_invert: true
				  }]
				});

				var config = app.getResource("group-dynamic-definition_context");
		        this.dynamicTableView = new tableViewClass({
		            el : "#view-group-dynamic-definition",
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
				this.model.unset(this.dynamicField, {silent : true});
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
		

		this.queryBuilderView = new queryBuilderViewClass({
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
