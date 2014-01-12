$SC.module("portlet.courses", function(mod, MyApp, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var coursesCollectionClass = Backbone.Collection.extend({
			url : "/module/courses/list",
			parse : function(response) {
				for (i in response) {
					response[i].lessons = new Backbone.Collection(response[i].lessons);
				}
				return response;
			}
		});
		mod.coursesCollection = new coursesCollectionClass;

		var contentModelClass = Backbone.Model.extend({
			initialize: function() {
				/**
				  * @todo LISTEN TO COLLECTION, TO GRAB INITIAL COURSE AND LESSON AND IF FOUND, SET
				 */
				 /*
				this.on("change:id", function(a,b,c,d) {
					this.fetch();
				}, this);
				*/
				/*
				this.on("change:course_id", function() {
					this.set({lesson_id : 0}, { silent: true });
				}, this);

				this.on("change:lesson_id", function() {
					this.unset("id");
					this.fetch();
				}, this);

				this.on("change:id", function() {
					this.fetch();
				}, this);
				*/
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

		mod.contentModel = new contentModelClass();

		// VIEWS
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

		var contentNavigationViewClass = Backbone.View.extend({
			portlet: $('#courses-widget'),
			template: _.template($('#courses-content-navigation-template').html()),
			events : {
				"click .prev"	: "prev",
				"click .next" 	: "next"
			},
			initialize: function() {
				console.info('portlet.courses/contentNavigationViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
				this.$el.hide();
			},
			render : function() {
				console.info('portlet.courses/contentNavigationViewClass::render');
				this.$el.empty().show().append(
					this.template(this.model.toJSON())
				);
				return this;
			},
			prev : function(e) {
				e.preventDefault();
				if (this.model.get("prev") != null) {
					this.model.set("id", this.model.get("prev").id);
					//this.model.fetch();
				}
			},
			next : function(e) {
				e.preventDefault();
				if (this.model.get("next") != null) {
					this.model.set("id", this.model.get("next").id);
					//this.model.fetch();
				}
			}
		});

		var contentGenericViewClass = Backbone.View.extend({
			portlet: $('#courses-widget'),
			template: _.template($('#courses-content-generic-template').html()),
			initialize: function() {
				console.info('portlet.courses/contentGenericViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
				this.$el.hide();
			},
			render : function() {
				console.info('portlet.courses/contentGenericViewClass::render');

				this.$el.empty().show().append(
					this.template(this.model.toJSON())
				);
				return this;
			}
		});

		var currentVideoJS = false;


		var contentVideoViewClass = contentGenericViewClass.extend({
			portlet: $('#courses-widget'),
			template: _.template($('#courses-content-video-template').html()),
			videoJS : false,
			initialize: function() {
				console.info('portlet.courses/contentVideoViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
				this.$el.empty();
			},
			render : function() {
				console.info('portlet.courses/contentVideoViewClass::render');
				//contentGenericViewClass.prototype.render.apply(this);

				var videoDomID = "courses-content-video-" + this.model.get("id");
				this.$(".video-js").hide();

				if (this.$("#" + videoDomID).size() == 0) {
					this.$el.append(
						this.template(this.model.toJSON())
					);
					var videoData = _.pick(this.model.get("data"), "controls", "preload", "autoplay", "poster", "techOrder", "width", "height");
					videojs(videoDomID, videoData, function() {
						//this.play();
					});
				}

				console.log(this.videoJS);
				if (this.videoJS != false) {
					this.videoJS.pause();
				}
				console.log(this.videoJS);
				this.$("#" + videoDomID).show();

				this.videoJS = videojs(videoDomID);
					
				return this;
			}
		});
		var contentTheoryViewClass = contentGenericViewClass.extend({});
		var contentTestsViewClass = contentGenericViewClass.extend({});

		var contentViewClass = Backbone.View.extend({
			el: $('#courses-content'),
			portlet: $('#courses-widget'),
			//template: _.template($('#courses-content-template').html()),
			rendered : false,
			initialize: function() {
				console.info('portlet.courses/contentViewClass::initialize');
				this.contentNavigationView = new contentNavigationViewClass({model : this.model, el: "#courses-content-navigation"});
				this.contentVideoView = new contentVideoViewClass({model : this.model, el : "#tab_class"});
				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/contentViewClass::render');
				this.contentNavigationView.render();

				var content_type = this.model.get("ctg_type");
				var contentTypeView = null;
				switch(content_type) {
					case "video" : {
						this.contentVideoView.render();
						break;
					}
					case "theory" : {
						var contentTypeView = new contentTheoryViewClass({model : this.model, el : "#tab_class"});
						break;
					}
					case "tests" : {
						var contentTypeView = new contentTestsViewClass({model : this.model, el : "#tab_class"});
						break;
					}
				}
				if (contentTypeView != null) {
					contentTypeView.render();
				}
				
			}
		});

		var userProgressViewClass = Backbone.View.extend({
			el: $('#progress-content'),
			portlet: $('#courses-widget'),
			initialize: function() {
				this.listenTo(this.model, 'change:course_id', this.renderCourse.bind(this));
				this.listenTo(this.model, 'change:lesson_id', this.renderLesson.bind(this));
				this.listenTo(this.model, 'change:id', this.renderContent.bind(this));
				this.render();
			},
			render : function() {
				if (jQuery.fn.easyPieChart) {
					this.$(".courses,.lessons,.topics").easyPieChart({
						animate: 1000,
						size: 75,
						lineWidth: 3,
						barColor: App.getLayoutColorCode('blue')
					});
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
				this.$(".courses span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".courses").data('easyPieChart').update(percent);
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

				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				this.$(".lessons span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".lessons").data('easyPieChart').update(percent);
				}
			},
			renderContent : function() {
				/*
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

				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				this.$(".lessons span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".lessons").data('easyPieChart').update(percent);
				}
				*/
			}
		});

		var courseWidgetViewClass = Backbone.View.extend({
			el: $('#courses-widget'),
			initialize: function() {
				this.contentView = new contentViewClass({model : this.model, collection : this.collection});
				this.userProgressView = new userProgressViewClass({model : this.model, collection : this.collection});
				this.filterActionView = new filterActionViewClass({collection : this.collection, model : this.model});

				this.listenTo(this.model, 'change:course_id', this.renderCourse.bind(this));
				this.listenTo(this.model, 'change:lesson_id', this.renderLesson.bind(this));

				var self = this;
				this.listenTo(this.model, 'sync', function() {
					self.renderCourse();
					self.renderLesson();
				});

				var self = this;

				this.collection.fetch({
					success : function(collection, response, options) {
						//console.log(this.model)
						self.model.fetch({
							success : function(model,b,c,d) {
								model.bindEvents();
							}
						});
					}
				});
				
			},
			renderCourse : function() {
				var model = this.collection.get(this.model.get("course_id"));
				this.$(".portlet-title > .caption #courses-title").html(model.get("name"));
			},
			renderLesson : function() {
				var model = this.collection.get(this.model.get("course_id"));
				var lessonCollection = model.get("lessons");
				var lessonModel = lessonCollection.get(this.model.get("lesson_id"));
				this.$el.find("#lessons-title").html(lessonModel.get("name"));
			}
		});
		this.courseWidgetView = new courseWidgetViewClass({model : mod.contentModel, collection : mod.coursesCollection});

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

	});

});