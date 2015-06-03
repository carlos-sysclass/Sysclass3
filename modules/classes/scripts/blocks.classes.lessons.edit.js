$SC.module("blocks.classes.lessons.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.startWithParent = false;

    mod.on("start", function(opt) {

		mod.lessonModelClass = Backbone.Model.extend({
			urlRoot : function() {
				if (this.get("id")) {
					return "/module/lessons/item/me";
				} else {
					return "/module/lessons/item/me?redirect=0";
				}
			}
		});

	    mod.lessonsCollectionClass = Backbone.Collection.extend({
	        initialize: function(opt) {
	            this.class_id = opt.class_id;
	            this.listenTo(this, "add", function(model, collection, opt) {
	                model.set("class_id", this.class_id);
	            });
	        },
	        model : mod.lessonModelClass,
	        url: function() {
	            return "/module/lessons/items/me/default/" + JSON.stringify({ class_id : this.class_id });
	        },
	        setContentOrder : function(order) {
	            $.ajax(
	                "/module/classes/items/lessons/set-order/" + this.class_id,
	                {
	                    data: {
	                        position: order
	                    },
	                    method : "PUT"
	                }
	            );
	        }
	    });

	    mod.classLessonsItemViewClass = Backbone.View.extend({
			events : {
				"click .enable-item-action" : "enable",
				"confirmed.bs.confirmation .disable-item-action" : "disable"
			},
	    	template : _.template($("#lessons-edit-item").html(), {variable: 'data'}),
	    	tagName : "li",
	    	className : "list-file-item draggable blue-stripe",
			initialize: function(opt) {
				console.info('blocks.classes.lessons.edit/classLessonsView::initialize');

				this.opened = opt.opened ? opt.opened : false;

				//this.listen(this.model, 'sync', this.render.bind(this));
			},
			render : function() {
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
				//this.$el.data("lessonId", this.model.get("id"));
				this.$el.attr("data-lesson-id", this.model.get("id"));

				if (this.$el.length) {
					app.module("ui").refresh(this.$el);
				}

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
			        //console.warn(e, params);
			        if (!self.model.get("id")) {
			        	var response = params.response;
			        	self.model.set(response);
			        	self.trigger("lesson:add", self.model);
			        	self.render();
			        }
			    });

				if (this.$el.length) {
					app.module("ui").refresh(this.$el);
				}
			},
			enable: function() {
				this.model.set("active", 1);
				this.model.save();
				this.render();
			},
			disable: function() {
				this.model.set("active", 0);
				this.model.save();
				this.render();
			}
	    });
		/*
	    mod.classLessonsNewItemViewClass = Backbone.View.extend({
	    	newtemplate : _.template($("#lessons-edit-add-item").html(), {variable: 'data'}),
	    	tagName : "li",
	    	className : "new-lesson-input-container",
			initialize: function(opt) {
				console.info('blocks.classes.lessons.edit/classLessonsView::initialize');

				//this.param = opt.param;

				//this.listen(this.model, 'sync', this.render.bind(this));
			},
			render : function() {
				this.$el.html(this.newtemplate(this.model.toJSON()));

				return this;
			},
			start : function() {
				this.$("[name='new-lesson-input']").focus();

				var saveLessonAction = function(e) {

				};

				this.$("[name='new-lesson-input']").on("blur", this.save.bind(this));
				$("[name='new-lesson-input']").on("keyup", function(e) {
					if (e.keyCode == 27) {
						$(".new-lesson-input-container").unbind().remove();
					}
				});

				//$(".new-lesson-input-container button").on("click", saveLessonAction);
			},
			save : function(e) {
				e.preventDefault();
				if ($("[name='new-lesson-input']").valid()) {
					// ADD INPUT SPINNER
					$("[name='new-lesson-input']").addClass("spinner");
					// SAVE THE NEW LESSON
					var name = $("[name='new-lesson-input']").val();

					self.lessonModel.set("name", name);
					self.lessonModel.save(null, {
						success: function(model, response) {
							$(".new-lesson-input-container").remove()
							self.addOne(model.toJSON());
						}
					});

					// REMOVE VIEW, ADD TO COLLECTION
				}
			}
	    });
		*/
		mod.classLessonsViewClass = Backbone.View.extend({
			events : {
				"click .add-item-action" : "addItem",
				"confirmed.bs.confirmation .remove-item-action" : "remove"
			},
			initialize: function(opt) {
				console.info('blocks.classes.lessons.edit/classLessonsView::initialize');

				//this.param = opt.param;

				this.listenToOnce(this.collection, 'sync', this.render.bind(this));

				this.initializeSortable();
			},
			initializeSortable : function() {
                var self = this;

				this.$(".list-group").sortable({
	                //connectWith: ".list-group",
	                //handle : ".drag-handler",
	                items: "li.list-file-item.draggable",
	                opacity: 0.8,
	                // axis : "y",
	                placeholder: 'list-file-item placeholder',
	                dropOnEmpty : true,
	                forceHelperSize : true,
	                forcePlaceholderSize: true,
	                tolerance: "intersect",
                    update : function( event, ui ) {
                        var contentOrder = $(this).sortable("toArray", {attribute : "data-lesson-id"});

                        self.collection.setContentOrder(contentOrder);
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
			addItem : function(e) {
				var self = this;

				var lessonModel = new mod.lessonModelClass({
					active : 1
				});

				var classLessonsNewItemView = new mod.classLessonsItemViewClass({
					model : lessonModel,
					opened : true
				});

				app.module("ui").refresh(
					$(classLessonsNewItemView.render().el).appendTo( this.$("ul") )
				);

				this.listenTo(classLessonsNewItemView, "lesson:add", function(model) {
					self.collection.add(model);
					model.save();
					console.warn(self.collection.toJSON());
				});

				classLessonsNewItemView.start();
 			},
			addOne : function(model) {
				console.info('blocks.classes.lessons.edit/classLessonsView::addOne');

				var classLessonsItemView = new mod.classLessonsItemViewClass({
					model : model
				});

				//app.module("ui").refresh(
					$(classLessonsItemView.render().el).appendTo(
						this.$("ul")
					)
				//);

			},
			render: function() {
				console.info('blocks.classes.lessons.edit/classLessonsView::render');

				var self = this;

				this.collection.each(function(model, i) {
					self.addOne(model);
				});
				app.module("ui").refresh( this.$("ul") );
			},
			remove : function(e) {
				var fileId = $(e.currentTarget).data("fileId");
				var fileObject = new mod.lessonFileModelClass();
				fileObject.set("id", fileId);
				fileObject.destroy();
				$(e.currentTarget).parents("li").remove();
			}
		});


/*

		$SC.module("crud.views.edit").on("start", function() {
			// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
	        mod.lessonsEditView = new lessonsEditViewClass({
				el : "#block_lessons_edit",
				param : "lessons",
				model : this.itemModel
			});
		});
		*/

		$("[data-widget-id='lessons-edit-widget']").each(function() {
			mod.createWidget(this, {
				class_id : $(this).data("classId")
			});
		});

    });

	mod.createWidget = function(el, data) {

		var lessonsCollection = new mod.lessonsCollectionClass({
			class_id : data.class_id
		});

		var classLessonsView = new mod.classLessonsViewClass({
			collection : lessonsCollection,
			el : el
		});


		lessonsCollection.fetch();
	};
	/*
    app.module("crud.config").on("start", function() {
        var config = this.getConfig();

        mod.start({
        	config : config
        });
    });
	*/
    app.module("crud.views.edit").on("start", function() {
    	var self = this;
        mod.listenToOnce(this.getForm(), "form:rendered", function() {
        	console.warn(self.itemModel);
            mod.start();
        });
    });

    //
});
