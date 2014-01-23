$SC.module("portlet.courses", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var courseModelClass = Backbone.Model.extend({
			urlRoot : "/module/courses/item/courses"
			/*
			parse : function(response) {
				for (i in response) {
					response[i].lessons = new Backbone.Collection(response[i].lessons);
				}
				return response;
			}
			*/
		});
		var classModelClass = Backbone.Model.extend({
			initialize : function(opt) {
				//this.courses = opt.courses;

				this.listenTo(opt.courses, "change:id", function(model,course_id) {
					this.set("course_id", course_id);
					this.fetch();
				}, this);
				/*
				this.on("change:id", function(model, id) {
					if (model._changing) {
						this.fetch();
					}
				}, this);
				*/
			},
			urlRoot : function() {
				if (this.get("course_id") == 0) {
					return "/module/courses/item/classes";
				} else {
					return "/module/courses/item/classes/" + this.get("course_id");	
				}
			},
			prev : function() {
				if (this.get("prev") != null) {
					this.set("id", this.get("prev"));
					this.fetch();
				}
			},
			next : function() {
				if (this.get("next") != null) {
					this.set("id", this.get("next"));
					this.fetch();
				}
			}
		});
		var lessonModelClass = Backbone.Model.extend({
			initialize : function(opt) {
				//this.classes = opt.classes;

				this.listenTo(opt.classes, "change:id change:course_id", function(model,class_id) {
					this.set("course_id", opt.classes.get("course_id"));
					this.set("class_id", opt.classes.get("id"));
					this.fetch();
				}, this);
			},
			urlRoot : function() {
				if (this.get("class_id") == 0) {
					return "/module/courses/item/lessons";
				} else {
					return "/module/courses/item/lessons/" + this.get("course_id") + "/" + this.get("class_id")
				}
			},
			prev : function() {
				if (this.get("prev") != null) {
					this.set("id", this.get("prev"));
					this.fetch();
				}
			},
			next : function() {
				if (this.get("next") != null) {
					this.set("id", this.get("next"));
					this.fetch();
				}
			}
		});
/*
		var contentModelClass = Backbone.Model.extend({
			initialize: function() {
			},
			bindEvents : function() {
				this.on("change:id", function(a,b,c,d) {
					this.fetch();
				}, this);
			},
			defaults : {
				course_id 	: 0,
				lesson_id 	: 0
			},
			urlRoot : function() {
				if (this.get("course_id") == 0 && this.get("lesson_id") == 0) {
					return "/module/courses/content";
				} else {
					return "/module/courses/content/" + this.get("course_id") + "/" + this.get("lesson_id");	
				}
			}
			// 31/106
		});
*/

		var fileTreeCollectionClass = Backbone.Collection.extend({
			initialize : function(opt) {
				if (opt.source) {
					this.url = opt.source;
				}
			},
			data: function (options, callback) {
				this.fetch({
					data : options,
					success : function(collection,data) {
						callback({ data: data });
					}
				})
			}
		});

		// VIEWS
		//
		/*
		var filterActionViewClass = Backbone.View.extend({
			el: $('#courses-list'),
			portlet: $('#courses-widget'),
			viewMode : "course",
			courseID : null,
			lessonID : null,

			itemTemplate: _.template($('#courses-list-item-template').html()),
			//noDataFoundTemplate: _.template($('#news-nofound-template').html()),

			events: {
			  "click a.list-group-item": "select"
			},
			initialize: function() {
				this.listenTo(this.collection, 'sync', this.render.bind(this));
				this.listenTo(this.collection, 'add', this.addOne.bind(this));

				this.repaint();
				this.$el.hide();
			},
			repaint : function() {
				var containerWith = this.portlet.find(".portlet-body").width();
				this.$el.width(containerWith);
			},
			toggle : function(viewMode) {
				if (this.$el.is(":visible")) {
					this.$el.hide();
					mod.contentModel.set(
						mod.contentModel.previousAttributes(), {silent : true}
					);
				} else {
					this.reload(viewMode);
				}
			},
			reload : function(viewMode) {
				if (viewMode == undefined || (viewMode != 'course' && viewMode != 'lesson') || mod.contentModel.get("course_id") == 0) {
					this.viewMode = "course";
				} else {
					this.viewMode = viewMode;
				}
				if (this.viewMode == 'lesson') {
					this.openLessonViewMode();
				} else {
					if (this.collection.size() > 1) {
						this.render(this.collection);
						this.portlet.find(".portlet-title > .caption #courses-title").html("Choose...");
						this.portlet.find(".portlet-title > .caption #lessons-title").html("");
					} else {
						var model = this.collection.at(0);
						this.courseID = model.get("id");
						// DO NOT SET 
						this.openLessonViewMode();
					}
				}
				this.$el.slideDown(500);
			},
			openLessonViewMode : function() {
				var model = this.collection.get(this.courseID);

				var lessonCollection = model.get("lessons");

				this.viewMode = "lesson";
				var self = this;
				this.$el.fadeOut(500, function() {
					self.render(lessonCollection);
					self.$el.fadeIn(500);
				});
			},
			select : function(e) {
				// Get collection index from id
				if (this.viewMode == 'course') {

					//mod.contentModel.set("course_id", );
					this.courseID = $(e.currentTarget).data("entity-id");
					this.openLessonViewMode();
				} else if (this.viewMode == 'lesson') {
					var model = this.collection.get(this.courseID);
					var lessonCollection = model.get("lessons");

					this.lessonID = $(e.currentTarget).data("entity-id");
					//mod.contentModel.set("lesson_id", $(e.currentTarget).data("entity-id"));
					//var lessonModel = lessonCollection.get(mod.contentModel.get("lesson_id"));
					//this.portlet.find("#lessons-title").html(lessonModel.get("name"));
					this.model.set({
						"course_id" : this.courseID,
						"lesson_id" : this.lessonID
					});
					this.model.unset('id');
					
					this.$el.hide();
				}
			},
			addOne: function(model) {
				this.$(".list-group").append(this.itemTemplate(model.toJSON()));
			},
			render: function(collection) {
			  this.$(".list-group").empty();
			  collection.each(this.addOne.bind(this));
			}
		});
		*/
		var contentNavigationViewClass = Backbone.View.extend({
			portlet: $('#courses-widget'),
			//template: _.template($('#courses-content-navigation-template').html()),
			events : {
				"click .class-prev-action"		: "prevClass",
				"click .class-next-action"		: "nextClass",
				"click .lesson-prev-action"		: "prevLesson",
				"click .lesson-next-action" 	: "nextLesson"
			},
			initialize: function(opt) {
				console.info('portlet.courses/contentNavigationViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
				console.log(opt.collections);
				this.course = opt.collections.course;
				this.classe = opt.collections.class;
				this.lesson = opt.collections.lesson;

				this.listenTo(this.course, "sync", this.renderCourse.bind(this));
				this.listenTo(this.classe, "sync", this.renderClass.bind(this));
				this.listenTo(this.lesson, "sync", this.renderLesson.bind(this));

				//this.render();
			},
			renderCourse : function() {
				console.info('portlet.courses/contentNavigationViewClass::renderCourse');
				var entityData = this.course.get("data");
				this.$(".course-title").html(entityData['name']);
			},
			renderClass : function() {
				console.info('portlet.courses/contentNavigationViewClass::renderClass');
				var entityData = this.classe.get("data");
				this.$(".class-title").html(entityData['name']);
			},
			renderLesson : function() {
				console.info('portlet.courses/contentNavigationViewClass::renderLesson');
				var entityData = this.lesson.get("data");
				this.$(".lesson-title").html(entityData['name']);
			},
			prevClass : function(e) {
				e.preventDefault();
				this.classe.prev();
			},
			nextClass : function(e) {
				e.preventDefault();
				this.classe.next();
			},
			prevLesson : function(e) {
				e.preventDefault();
				this.lesson.prev();
			},
			nextLesson : function(e) {
				e.preventDefault();
				this.lesson.next();
			}
		});

		var contentGenericViewClass = Backbone.View.extend({
			portlet: $('#courses-widget'),
			template: _.template($('#courses-content-generic-template').html()),
			initialize: function() {
				console.info('portlet.courses/contentGenericViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
				//this.$el.hide();
			},
			render : function() {
				console.info('portlet.courses/contentGenericViewClass::render');

				this.$el.empty().append(
					this.template(this.model.toJSON())
				);
				return this;
			}
		});

		var contentVideoViewClass = contentGenericViewClass.extend({
			portlet: $('#courses-widget'),
			template: _.template($('#courses-content-video-template').html()),
			videoJS : false,
			initialize: function() {
				console.info('portlet.courses/contentVideoViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
				//this.$el.empty();
			},
			render : function() {
				console.info('portlet.courses/contentVideoViewClass::render');
				//contentGenericViewClass.prototype.render.apply(this);
				var videoDomID = "courses-content-video-" + this.model.get("id");
				//this.$(".video-js").hide();
				var entityData = this.model.get("data");

				if (this.videoJS != false) {
					this.videoJS.dispose();
				}

				if (this.$("#" + videoDomID).size() == 0) {
					this.$el.empty().append(
						this.template(this.model.toJSON())
					);

					var videoData = _.pick(entityData["data"], "controls", "preload", "autoplay", "poster", "techOrder", "width", "height");
					videojs(videoDomID, videoData, function() {
						//this.play();
					});
				}

				this.videoJS = videojs(videoDomID);
					
				return this;
			},
			destroy : function() {
				console.info('portlet.courses/contentVideoViewClass::destroy');
				this.$(".video-js").each(function(i, el) {
					var player = videojs($(el).attr("id"));
					player.dispose();
				});

				this.$el.empty();
			}
		});
		var contentTheoryViewClass = contentGenericViewClass.extend({});
		var contentTestsViewClass = contentGenericViewClass.extend({});

		var contentMaterialsViewClass = Backbone.View.extend({
			//portlet: $('#courses-widget'),
			template: _.template($('#courses-content-materials-template').html()),
			initialize: function() {
				//this.$el.empty();
				console.info('portlet.courses/contentMaterialsViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function() {
				console.info('portlet.courses/contentMaterialsViewClass::render');
				var entityData = this.model.get("data");
				var sources = entityData['sources'];
				if (typeof sources['materials'] != undefined) {
					this.fileTree = new fileTreeCollectionClass({source: sources['materials']});
					//this.fileTree.fetch();
				} else {

				}

				this.$el.empty().append(
					this.template()
				);
	            this.$('.tree').tree({
	                selectable: false,
	                dataSource: this.fileTree,
	                loadingHTML: '<img src="/assets/default/img/input-spinner.gif"/>',
	            });
			}
		});



		var contentViewClass = Backbone.View.extend({
			el: $('#courses-content'),
			portlet: $('#courses-widget'),
			//template: _.template($('#courses-content-template').html()),
			contentVideoView : null,
			rendered : false,
			initialize: function() {
				console.info('portlet.courses/contentViewClass::initialize');
				
				//this.contentVideoView = new contentVideoViewClass({model : this.model, el : "#tab_class"});
				this.contentMaterialsView = new contentMaterialsViewClass({model : this.model, el : "#tab_materials"});

				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/contentViewClass::render');
				//this.contentNavigationView.render();
				var entityData = this.model.get("data");
				console.log(entityData["ctg_type"]);

				var content_type = entityData["ctg_type"];
				var contentTypeView = null;
				switch(content_type) {
					case "video" : {
						if (this.contentVideoView == null) {
							this.contentVideoView = new contentVideoViewClass({model : this.model, el : "#tab_class"});
						}
						this.contentVideoView.render();
						break;
					}
					case "theory" : {
						if (this.contentVideoView != null) {
							this.contentVideoView.destroy();
							this.contentVideoView = null;
						}
						var contentTypeView = new contentTheoryViewClass({model : this.model, el : "#tab_class"});
						break;
					}
					case "tests" : {
						if (this.contentVideoView != null) {
							this.contentVideoView.destroy();
							this.contentVideoView = null;
						}

						var contentTypeView = new contentTestsViewClass({model : this.model, el : "#tab_class"});
						break;
					}
				}
				if (contentTypeView != null) {
					contentTypeView.render();
				}
				// @todo RENDER ONLY ON TAB CHANGE!
				this.contentMaterialsView.render();
				
			}
		});

		var userProgressViewClass = Backbone.View.extend({
			el: $('#progress-content'),
			portlet: $('#courses-widget'),
			initialize: function() {
				this.listenTo(this.model, 'change', this.renderSemester.bind(this));
				this.listenTo(this.model, 'change:course_id', this.renderCourse.bind(this));
				this.listenTo(this.model, 'change:lesson_id', this.renderLesson.bind(this));
				this.listenTo(this.model, 'change:id', this.renderTopic.bind(this));
				this.render();
			},
			render : function() {
				if (jQuery.fn.easyPieChart) {
					this.$(".topic").easyPieChart({
						animate: 1000,
						size: 75,
						lineWidth: 3,
						barColor: App.getLayoutColorCode('green')
					});
					this.$(".lesson").easyPieChart({
						animate: 1000,
						size: 75,
						lineWidth: 3,
						barColor: App.getLayoutColorCode('yellow')
					});
					this.$(".semester").easyPieChart({
						animate: 1000,
						size: 75,
						lineWidth: 3,
						barColor: App.getLayoutColorCode('red')
					});
					this.$(".course").easyPieChart({
						animate: 1000,
						size: 75,
						lineWidth: 3,
						barColor: App.getLayoutColorCode('grey')
					});
				}
			},
			renderSemester : function() {
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				percent = 40;
				this.$(".semester span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".semester").data('easyPieChart').update(percent);
				}
			},
			renderCourse : function() {
				var courseID = this.model.get("course_id");
				if (courseID == 0) {
					var percent = 0;
				} else {
					var courseModel = this.collection.get(courseID);
					//var courseStats = courseModel.get("stats");
					//var percent = courseStats.completed * 100 / courseStats.total_lessons;

					var lessonsCollection = courseModel.get("lessons");
					var lessonStatsAll = lessonsCollection.reduce(function(total, item) {
						return total + item.get("stats").overall_progress;
					}, 0);

					var percent = Math.round(lessonStatsAll / lessonsCollection.size());
				}
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				percent = 30;
				this.$(".course span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".course").data('easyPieChart').update(percent);
				}
			},
			renderLesson : function() {
				var courseID = this.model.get("course_id");
				var lessonID = this.model.get("lesson_id");
				if (lessonID == 0) {
					var percent = 0;
				} else {
					var courseModel = this.collection.get(courseID);
					var lessonsCollection = courseModel.get("lessons");
					var lessonModel = lessonsCollection.get(lessonID);
					var lessonStats = lessonModel.get("stats");
					var percent = Math.round(lessonStats.overall_progress);
				}

				percent = 20;

				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				this.$(".lesson span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".lesson").data('easyPieChart').update(percent);
				}
			},
			renderTopic : function() {
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				percent = 80;
				this.$(".topic span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".topic").data('easyPieChart').update(percent);
				}
			}
		});

		var courseWidgetViewClass = Backbone.View.extend({
			el: $('#courses-widget'),
			initialize: function(opt) {
				//opt.collections['course']
				//opt.collections['class']
				//opt.collections['lesson']

				this.contentNavigationView = new contentNavigationViewClass({
					collections : opt.collections,
					el: "#courses-content-navigation"
				});
				
				this.contentView = new contentViewClass({
					model : opt.collections.lesson
				});
				//this.userProgressView = new userProgressViewClass({model : this.model, collection : this.collection});
				//this.filterActionView = new filterActionViewClass({collection : this.collection, model : this.model});

				//this.listenTo(this.model, 'change:course_id', this.renderCourse.bind(this));
				//*this.listenTo(this.model, 'change:lesson_id', this.renderLesson.bind(this));
				/*
				var self = this;
				this.listenTo(this.model, 'sync', function() {
					self.renderCourse();
					self.renderLesson();
				});

				var self = this;
				*/
				opt.collections.course.fetch({
					success : function(collection, response, options) {
						/*
						opt.collections.class.fetch({
							success : function(collection, response, options) {
								opt.collections.lesson.fetch();
							}
						});
*/
					}
				});
				
			},
			renderCourse : function() {
				var model = this.collection.get(this.model.get("course_id"));
				this.$("#courses-title").html(model.get("name"));
			},
			renderLesson : function() {
				var model = this.collection.get(this.model.get("course_id"));
				var lessonCollection = model.get("lessons");
				var lessonModel = lessonCollection.get(this.model.get("lesson_id"));
				this.$el.find("#lessons-title").html(lessonModel.get("name"));
			}
		});

		this.onFilter = function(e, portlet) {
			// INJECT
			//this.contentView.$el.hide();
			if ($(e.currentTarget).attr("id") == "lessons-title") {
				this.courseWidgetView.filterActionView.toggle("lesson");
			} else {
				this.courseWidgetView.filterActionView.toggle();
			}
		};
		this.onSearch = function(e, portlet) {
			/*
			// INJECT
			this.contentView.$el.hide();
			this.filterActionView.reload();
			*/
			return false;
		};
		this.onResized = function(e, portlet) {
			this.filterActionView.repaint();
		};
		this.onFullscreen = function(e, portlet) {
		};
		this.onRestorescreen = function(e, portlet) {
		};

		this.courseModel = new courseModelClass;
		this.classModel = new classModelClass({courses : this.courseModel});
		this.lessonModel = new lessonModelClass({classes : this.classModel});
		//this.contentModel = new contentModelClass();
		
		this.courseWidgetView = new courseWidgetViewClass({
			model : this.contentModel, 
			collections : {
				'course' 	: this.courseModel,
				'class'		: this.classModel,
				'lesson'	: this.lessonModel
			}
		});
	});

});