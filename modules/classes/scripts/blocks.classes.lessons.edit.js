$SC.module("blocks.classes.lessons.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.startWithParent = false;

    app.module("crud.config").on("start", function() {
        var config = this.getConfig();

        mod.start({
        	config : config
        });
    });

    mod.on("start", function(opt){
		this.config = opt.config;

		var entity_id = mod.config.entity_id;

		var lessonModelClass = Backbone.Model.extend({
			url : "/module/lessons/item/me?redirect=0"
		});

		var lessonsEditViewClass = Backbone.View.extend({
			template : _.template($("#lessons-edit-item").html(), {variable: 'data'}),
			newtemplate : _.template($("#lessons-edit-add-item").html(), {variable: 'data'}),

			events : {
				"click .add-item-action" : "addItem",
				"confirmed.bs.confirmation .remove-item-action" : "remove"
			},
			initialize: function(opt) {
				console.info('blocks.classes.lessons.edit/lessonsEditViewClass::initialize');

				this.param = opt.param;

				this.listenToOnce(this.model, 'change:lessons', this.render.bind(this));

				this.$(".list-group").sortable({
	                //connectWith: ".list-group",
	                items: "li.list-file-item.draggable",
	                opacity: 0.8,
	                /* axis : "y", */
	                placeholder: 'list-file-item placeholder',
	                dropOnEmpty : true,
	                forceHelperSize : true,
	                forcePlaceholderSize: true,
	                tolerance: "intersect",
	                /* helper : 'original',  */
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
	                over : function( event, ui ) {
	                    $(this).addClass("ui-sortable-hover");
	                },
	                out  : function( event, ui ) {
	                    $(this).removeClass("ui-sortable-hover");
	                },
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
				/*
				$("[name='new-lesson-input']").on("keyup", function(e) {
					e.preventDefault();
					if ($(this).valid() && e.keyCode == 13) {
						// ADD INPUT SPINNER
						$(this).addClass("spinner");
						// SAVE THE NEW LESSON
						var name = $(this).val();

						self.lessonModel.set("name", name);
						self.lessonModel.save(null, {
							success: function(model, response) {
								$(".new-lesson-input-container").fadeOut(500);
								self.addOne(model.toJSON());
							}
						});

						// REMOVE VIEW, ADD TO COLLECTION
					}
				});
*/
 			},
			addOne : function(data) {
				console.info('blocks.classes.lessons.edit/lessonsEditViewClass::addOne');

				var html = this.template(data);

				app.module("ui").refresh(
					$(html).appendTo(
						this.$("ul")
					)
				);
			},
			render: function() {
				console.info('blocks.classes.lessons.edit/lessonsEditViewClass::render');

				var data = this.model.get(this.param);
				//this.$el.empty();
				for (i in data) {
					this.addOne(data[i]);
				}
			},
			remove : function(e) {
				var fileId = $(e.currentTarget).data("fileId");
				var fileObject = new mod.lessonFileModelClass();
				fileObject.set("id", fileId);
				fileObject.destroy();
				$(e.currentTarget).parents("li").remove();
			}
		});

		$SC.module("crud.views.edit").on("start", function() {
			// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
	        mod.lessonsEditView = new lessonsEditViewClass({
				el : "#block_lessons_edit",
				param : "lessons",
				model : this.itemModel
			});
		});

    });
});
