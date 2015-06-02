$SC.module("blocks.classes.lessons.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.startWithParent = false;

    mod.on("start", function(opt) {
    	/*
		this.config = opt.config;

		var entity_id = mod.config.entity_id;

		var lessonModelClass = Backbone.Model.extend({
			url : "/module/lessons/item/me?redirect=0"
		});
		*/
	    mod.lessonsCollectionClass = Backbone.Collection.extend({
	        initialize: function(opt) {
	            this.class_id = opt.class_id;
	            this.listenTo(this, "add", function(model, collection, opt) {
	                model.set("class_id", this.class_id);
	            });
	        },
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
	    	template : _.template($("#lessons-edit-item").html(), {variable: 'data'}),
	    	tagName : "li",
	    	className : "list-file-item draggable green-stripe",
			initialize: function(opt) {
				console.info('blocks.classes.lessons.edit/classLessonsView::initialize');

				//this.param = opt.param;

				//this.listen(this.model, 'sync', this.render.bind(this));
			},
			render : function() {
				this.$el.html(this.template(this.model.toJSON()));
				this.$el.data("lessonId", this.model.get("id"));

				return this;
			}
	    });

		mod.classLessonsViewClass = Backbone.View.extend({

			newtemplate : _.template($("#lessons-edit-add-item").html(), {variable: 'data'}),

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
				this.lessonModel = new lessonModelClass({
					class_id : entity_id,
					active : 1
				});

				this.$("ul").append(
					this.newtemplate(this.lessonModel.toJSON())
				);
				$("[name='new-lesson-input']").focus();

				var saveLessonAction = function(e) {
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
				};

				$("[name='new-lesson-input']").on("blur", saveLessonAction);
				$("[name='new-lesson-input']").on("keyup", function(e) {
					if (e.keyCode == 27) {
						$(".new-lesson-input-container").unbind().remove();
					}
				});
				//$(".new-lesson-input-container button").on("click", saveLessonAction);
 			},
			addOne : function(model) {
				console.info('blocks.classes.lessons.edit/classLessonsView::addOne');

				var classLessonsItemView = new mod.classLessonsItemViewClass({
					model : model
				});

				app.module("ui").refresh(
					$(classLessonsItemView.render().el).appendTo(
						this.$("ul")
					)
				);
			},
			render: function() {
				console.info('blocks.classes.lessons.edit/classLessonsView::render');

				var self = this;

				this.collection.each(function(model, i) {
					self.addOne(model);
				});
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
