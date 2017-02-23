$SC.module("blocks.classes.units", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.startWithParent = false;

    mod.on("start", function(opt) {

	    var baseClassItemViewClass = Backbone.View.extend({
			events : {
				"switchChange.bootstrapSwitch .bootstrap-switch-me" : "toogleActive",
				"confirmed.bs.confirmation .delete-item-action" : "delete",
				"click .delete-unsaved-item-action" : "delete"
			},
	    	tagName : "li",
	    	className : "list-file-item blue-stripe",
			initialize: function(opt) {
				console.info('blocks.classes.units/baseClassItemViewClass::initialize');

				this.opened = opt.opened ? opt.opened : false;

				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function() {
				console.info('blocks.classes.units/baseClassItemViewClass::render');
				this.$el.html(this.template(this.model.toJSON()));
				if (this.model.get("id")) {
					if (this.model.get("active") == 0) {
						this.$el.removeClass("green-stripe");
						this.$el.removeClass("blue-stripe");
						this.$el.addClass("red-stripe");
					} else {
						this.$el.removeClass("red-stripe");
						this.$el.removeClass("blue-stripe");
						this.$el.addClass("green-stripe");
					}
				}
				//this.$el.data("unitId", this.model.get("id"));
				this.$el.attr("data-unit-id", this.model.get("id"));

				if (this.$el.length) {
					app.module("ui").refresh(this.$el);
				}

				this.trigger("view:rendered");

				return this;
			},
			start : function() {
				var editableItem = this.$(".editable-me");

				if (this.opened) {
					window.setTimeout(function() {
						editableItem.editable('show');
					}, 350);
				}

				editableItem.editable("option", "ajaxOptions", {
					type: editableItem.data("method")
				});

				var self = this;
			    editableItem.on('save', function(e, params) {
                    self.model.set($(this).data("name"), params.newValue);
                    self.model.save();

                    // TRIGGER A COUNTER UPDATE
                    
                    /*
			        if (!self.model.get("id")) {
			        	var response = params.response;
			        	self.model.set(response);
			        	self.trigger("unit:added", self.model);
			        	self.render();
			        }
			        */
			    });

				if (this.$el.length) {
					app.module("ui").refresh(this.$el);
				}
			},
            toogleActive : function(e, state) {
                this.model.set("active", state);
                this.model.save();
                //this.render();
            },
            /*
			enable: function() {
				this.model.set("active", 1);
				this.model.save();
				this.render();
			},
			disable: function() {
				this.model.set("active", 0);
				this.model.save();
				this.render();
			},
			*/
			delete: function() {
				this.remove();
				this.model.destroy();
				this.trigger("unit:removed", this.model);
				this.trigger("view:removed");
			}
	    });

		var classLessonItemViewClass = baseClassItemViewClass.extend({
	    	template : _.template($("#class-unit-item-template").html(), null, {variable: 'model'})
		});
		var classTestItemViewClass = baseClassItemViewClass.extend({
	    	template : _.template($("#class-test-item-template").html(), null, {variable: 'model'}),
	    	className : "list-file-item blue-stripe test-item"
		});



		mod.classLessonsViewClass = Backbone.View.extend({
			events : {
				"click .add-unit-action" : "addLessonItem",
				"click .add-test-action" : "addTestItem"
			},
			initialize: function(opt) {
				console.info('blocks.classes.units.edit/classLessonsView::initialize');

				//this.param = opt.param;
				//
				this.listenToOnce(this.collection, 'sync', this.render.bind(this));

                this.listenTo(this.collection, 'add', this.refreshCounters.bind(this));
                this.listenTo(this.collection, 'remove', this.refreshCounters.bind(this));
                //this.listenTo(this.collection, 'change', this.refreshCounters.bind(this));

				this.initializeSortable();
			},
			initializeSortable : function() {
                var self = this;

				this.$(".list-group").sortable({
	                //connectWith: ".list-group",
	                handle : ".drag-handler",
	                items: "li.list-file-item",
	                opacity: 0.8,
	                // axis : "y",
	                placeholder: 'list-file-item placeholder',
	                dropOnEmpty : true,
	                forceHelperSize : true,
	                forcePlaceholderSize: true,
	                tolerance: "intersect",
                    update : function( event, ui ) {
                        var contentOrder = $(this).sortable("toArray", {attribute : "data-unit-id"});

                        self.collection.setContentOrder(contentOrder);

                        self.refreshCounters();
                    },
	                // helper : 'original',
	                /*
	                receive : function( event, ui ) {
	                    $(this).removeClass("empty-list-group");
	                    self.refreshCounters();
	                    // HANDLE COLLECTIONS
	                    var classe_id = ui.item.data('classId');

	                    var item = mod.classesCollection.findWhere({id : classe_id.toString()});

	                    var classes = self.model.get('classes');

	                    classes.push(item.toJSON());
	                    self.model.set('classes', classes);
	                },
	                remove : function( event, ui ) {
	                    if ($(this).children().size() == 0) {
	                        $(this).addClass("empty-list-group");
	                    }
	                    self.refreshCounters();

	                    // HANDLE COLLECTIONS
	                    var classe_id = ui.item.data('classId');

	                    var classes = self.model.get('classes');

	                    remainingClasses = _.filter(classes, function(model, i) {
	                        if (model.id == classe_id) {
	                            return false;
	                        }
	                        return true;
	                    });

	                    self.model.set('classes', remainingClasses);
	                },
	                */
	                over : function( event, ui ) {
	                    $(this).addClass("ui-sortable-hover");
	                },
	                out  : function( event, ui ) {
	                    $(this).removeClass("ui-sortable-hover");
	                }
	            });


                this.$("div.content-timeline-items").sortable({
                    //connectWith: ".list-group",
                    handle : ".drag-handler",
                    items: "div.timeline-item",
                    opacity: 0.8,
                    /* axis : "y", */
                    placeholder: 'list-file-item placeholder',
                    dropOnEmpty : true,
                    forceHelperSize : true,
                    forcePlaceholderSize: true,
                    tolerance: "intersect",
                    /* helper : 'original',  */

                });
            },
            refreshCounters : function() {
                console.info('blocks.classes.units.edit/classLessonsViewClass::refreshCounters');
                var total = this.collection.size();
                //alert(total);
                this.$("ul > li.list-file-item .total").html(total);

                var counter = 0;

                this.$("ul > li.list-file-item").each(function(i, item) {
                	if ($(this).find(".counter").size() > 0) {
                    	$(this).find(".counter").html(counter+1);
                    	counter++;
                    }
                });
            },
			addLessonItem : function(e) {
				var self = this;

				var itemModel = new mod.models.unit({
					active : 1
				});

				self.collection.add(itemModel);

				var classLessonsNewItemView = new classLessonItemViewClass({
					model : itemModel,
					opened : true
				});

				$(classLessonsNewItemView.render().el).appendTo( this.$("ul") );
				/*
				this.listenTo(classLessonsNewItemView, "unit:added", function(model) {
					self.collection.add(model);
					model.save();
				});
				this.listenTo(classLessonsNewItemView, "unit:removed", function(model) {
					self.collection.remove(model);
				});
				*/
				classLessonsNewItemView.start();

				this.listenTo(classLessonsNewItemView, "view:rendered", this.refreshCounters.bind(this));
 			},
			addTestItem : function(e) {
				var self = this;

				var itemModel = new mod.models.test({
					active : 1
				});

				self.collection.add(itemModel);

				var classLessonsNewItemView = new classTestItemViewClass({
					model : itemModel,
					opened : true
				});

				$(classLessonsNewItemView.render().el).appendTo( this.$("ul") );

				classLessonsNewItemView.start();

				this.listenTo(classLessonsNewItemView, "view:rendered", this.refreshCounters.bind(this));
 			},

			addOne : function(model) {
				console.info('blocks.classes.units.edit/classLessonsView::addOne');

				var self = this;
				var classItemView = null;

				if (model.get("type") == "unit") {
					classItemView = new classLessonItemViewClass({
						model : model
					});
				} else if (model.get("type") == "test") {
					classItemView = new classTestItemViewClass({
						model : model
					});
				}
				if (!_.isNull(classItemView)) {
					this.listenTo(classItemView, "unit:removed", function(model) {
						self.collection.remove(model);
					});
					$(classItemView.render().el).appendTo( this.$("ul") );

					classItemView.start();

					this.listenTo(classItemView, "view:rendered", this.refreshCounters.bind(this));
				}
			},
			render: function() {
				console.info('blocks.classes.units.edit/classLessonsView::render');

				var self = this;

				console.log(this.collection.toJSON());

				this.collection.each(function(model, i) {
					self.addOne(model);
				});

				this.refreshCounters();

				app.module("ui").refresh( this.$("ul") );
			}/*,
			remove : function(e) {
				var fileId = $(e.currentTarget).data("fileId");
				var fileObject = new mod.unitFileModelClass();
				fileObject.set("id", fileId);
				fileObject.destroy();
				$(e.currentTarget).parents("li").remove();
			}
			*/
		});

		$("[data-widget-id='units-edit-widget']").each(function() {
            var class_id = null;
            if (_.isEmpty($(this).data("classId"))) {
                // LOAD COURSE MODEL TO BY-PASS
                class_id = opt.entityModel.get("id");
            } else {
                class_id = $(this).data("classId");
            }

            if (!_.isNull(class_id)) {
                mod.createBlock(this, {
                    class_id : class_id,
                    entityModel : opt.entityModel
                });
            }
		});

    });

    mod.createBlock = function(el, data) {
		var unitsCollection = new mod.collections.unit(null, {
			class_id : data.class_id
		});

		var classLessonsView = new mod.classLessonsViewClass({
			collection : unitsCollection,
			el : el,
            model : data.entityModel
		});

		unitsCollection.fetch();
    };

    this.models = {
    	unit : Backbone.Model.extend({
    		defaults : {
    			type : "unit"
    		},
			urlRoot : function() {
				if (this.get("id")) {
					return "/module/units/item/me";
				} else {
					return "/module/units/item/me?redirect=0";
				}
			}
		}),
    	test : Backbone.Model.extend({
    		defaults : {
    			type : "test"
    		},
			urlRoot : function() {
				if (this.get("id")) {
					return "/module/tests/item/me";
				} else {
					return "/module/tests/item/me?redirect=0";
				}
			}
		})
    };

    this.collections = {
    	unit : Backbone.Collection.extend({
	        initialize: function(data, opt) {
	            this.class_id = opt.class_id;
	            this.on("add", function(model, collection, opt) {
	                model.set("class_id", this.class_id);
	            });
	            this.on("remove", function(model, collection, opt) {

	            });

	        },
	        model : function(info) {
	        	if (info.type == 'unit') {
	        		return new mod.models.unit(info);
	        	} else if (info.type == 'test') {
	        		return new mod.models.test(info);
	        	}
	        },
	        url: function() {
	            return "/module/units/items/unit-and-test/default/" + JSON.stringify({ class_id : this.class_id, type : ['test','unit'] });
	        },
	        setContentOrder : function(order) {
	            $.ajax(
	                "/module/classes/items/units/set-order/" + this.class_id,
	                {
	                    data: {
	                        position: order
	                    },
	                    method : "PUT"
	                }
	            );
	        }
	    })
    };

    app.module("crud.views.edit").on("start", function() {
        var self = this;

        mod.listenToOnce(this.getForm(), "form:rendered", function() {
            mod.start({
                entityModel : self.itemModel
            });
        });
    });
});
