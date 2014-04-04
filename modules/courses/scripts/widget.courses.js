$SC.module("portlet.courses", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
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
		// TODO THINK ABOUT MOVE THIS CLASS INTO THE MAIN VIEW
		var contentNavigationViewClass = Backbone.View.extend({
			portlet: $('#courses-widget'),
			//template: _.template($('#courses-content-navigation-template').html()),
			events : {
				"click .class-change-action"		: "goToClass",
				//"click .class-next-action"		: "nextClass",
				//"click .lesson-prev-action"		: "prevLesson",
				//"click .lesson-next-action" 	: "nextLesson",
				"click .nav-prev-action" 		: "prevItem",
				"click .nav-next-action" 		: "nextItem"
				//"click .nav-next-action" 		: "searchItem"
			},
			initialize: function(opt) {
				console.info('portlet.courses/contentNavigationViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));

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
			goToPrevLesson : function() {
				this.lesson.prev();
			},
			goToPrevClass : function() {
				this.classe.prev();
			},
			goToPrevCourse : function() {
				this.course.prev();
			},			
			goToNextLesson : function() {
				this.lesson.next();
			},
			goToNextClass : function() {
				this.classe.next();
			},
			goToNextCourse : function() {
				this.course.next();
			},
			prevItem : function(e) {
				e.preventDefault();
				var activeTab = this.$(".nav-tabs li.active");
				if (activeTab.is(".the-lesson-tab")) {
					this.goToPrevLesson();
				} else if (activeTab.is(".the-class-tab")) {
					this.goToPrevClass();
				} else if (activeTab.is(".the-course-tab")) {
					this.goToPrevCourse();
				}
			},
			nextItem : function(e) {
				e.preventDefault();
				var activeTab = this.$(".nav-tabs li.active");
				if (activeTab.is(".the-lesson-tab")) {
					this.goToNextLesson();
				} else if (activeTab.is(".the-class-tab")) {
					this.goToNextClass();
				} else if (activeTab.is(".the-course-tab")) {
					this.goToNextCourse();
				}
			},
			goToClass : function(e) {
				e.preventDefault();
				var classId = $(e.currentTarget).data("refId");
				if (!_.isUndefined(classId)) {
					this.classe.goToID(classId);
				}
				// SELECTING THE CLASS TAB
				this.$(".the-class-tab a").tab('show');
			},
			searchItem : function(e) {
				e.preventDefault();

				

				
				var activeTab = this.$(".nav-tabs li.active");
				if (activeTab.is(".the-lesson-tab")) {
					this.goToNextLesson();
				} else if (activeTab.is(".the-class-tab")) {
					this.goToNextClass();
				} else if (activeTab.is(".the-course-tab")) {
					this.goToNextCourse();
				}
			},
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

				this.$(".scroller").empty().append(
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
					this.$(".scroller").empty().append(
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

				this.$(".scroller").empty();
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
					var fileTreeCollectionClass = app.module("models.courses").fileTreeCollectionClass;
					this.fileTree = new fileTreeCollectionClass({source: sources['materials']});
					//this.fileTree.fetch();
				} else {

				}

				this.$(".scroller").empty().append(
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
				
				this.contentMaterialsView = new contentMaterialsViewClass({model : this.model, el : "#tab_lesson_materials"});

				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/contentViewClass::render');
				//this.contentNavigationView.render();
				var entityData = this.model.get("data");

				var content_type = entityData["ctg_type"];
				var contentTypeView = null;
				switch(content_type) {
					case "video" : {
						if (this.contentVideoView == null) {
							this.contentVideoView = new contentVideoViewClass({model : this.model, el : "#tab_lesson_content"});
						}
						this.contentVideoView.render();
						break;
					}
					case "theory" : {
						if (this.contentVideoView != null) {
							this.contentVideoView.destroy();
							this.contentVideoView = null;
						}
						var contentTypeView = new contentTheoryViewClass({model : this.model, el : "#tab_lesson_content"});
						break;
					}
					case "tests" : {
						if (this.contentVideoView != null) {
							this.contentVideoView.destroy();
							this.contentVideoView = null;
						}

						var contentTypeView = new contentTestsViewClass({model : this.model, el : "#tab_lesson_content"});
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

		var courseViewClass = Backbone.View.extend({
			el: $('#course-tab'),
			portlet: $('#courses-widget'),
			//template: _.template($('#courses-content-template').html()),
			//contentVideoView : null,
			rendered : false,
			classesCollection : null,
			courseClassesTabView : null,
			courseRoadmapTabView : null,
			initialize: function() {
				console.info('portlet.courses/courseViewClass::initialize');

				var classesCollectionClass = app.module("models.courses").classesCollectionClass;
				this.classesCollection = new classesCollectionClass();

				var seasonsCollectionClass = app.module("models.courses").seasonsCollectionClass;
				this.seasonsCollection = new seasonsCollectionClass();

				// TODO CREATE SUB VIEWS!!
				this.courseDescriptionTabView 	= new courseDescriptionTabViewClass({
					el : "#tab_course_description > .scroller",
					model : this.model
				});

				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/courseViewClass::render');

				if (_.isNull(this.courseClassesTabView)) {
					this.courseClassesTabView = new courseClassesTabViewClass({
						el : "#tab_course_classes table tbody",
						collection : this.classesCollection
					});
				}
				//this.courseClassesTabView.setCourseID(this.model.get("id"));

				if (_.isNull(this.courseRoadmapTabView)) {
					this.courseRoadmapTabView = new courseRoadmapTabViewClass({
						el : "#tab_course_roadmap",
						collections : {
							seasons 	: this.seasonsCollection,
							classes 	: this.classesCollection
						}
					});
				}
				//this.courseRoadmapTabView.setCourseID();
				this.seasonsCollection.course_id = this.model.get("id");
				this.seasonsCollection.fetch();

				this.classesCollection.course_id = this.model.get("id");
				this.classesCollection.fetch();

				//this.courseDescriptionTabView.render();
				//this.courseClassesTabView.render(); 
			}
		});

		var courseDescriptionTabViewClass = Backbone.View.extend({
			portlet: $('#courses-widget'),
			template : _.template($("#tab_course_description-template").html()),
			initialize: function() {
				console.info('portlet.courses/courseDescriptionTabViewClass::initialize');

				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/courseDescriptionTabViewClass::render');
				var modelData = this.model.get("data");

				this.$el.empty().append(this.template(modelData));
			}
		});
		var courseClassesTabViewClass = Backbone.View.extend({
			portlet: $('#courses-widget'),
			nofoundTemplate : _.template($("#tab_course_classes-nofound-template").html()),

			initialize: function(opt) {
				console.info('portlet.courses/courseClassesTabViewClass::initialize');

				this.template = _.has(opt, "template") ? opt.template : this.template;

				this.collection.datatable = false;
				
				this.listenTo(this.collection, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/courseClassesTabViewClass::render');
				this.$el.empty();

				if (this.collection.size() == 0) {
					this.$el.append(this.nofoundTemplate());
				} else {
					var self = this;
					this.collection.each(function(model, i) {
						var courseClassesTabViewItem = new courseClassesTabViewItemClass({model : model});
						self.$el.append(courseClassesTabViewItem.render().el);
					});
				}
			}
		});
		var courseClassesTabViewItemClass = Backbone.View.extend({
			tagName : "tr",
			template 		: _.template($("#tab_course_classes-item-template").html()),

			render : function(e) {
				console.info('portlet.courses/courseClassesTabViewClass::render');
				this.$el.append(
					this.template(this.model.toJSON())
				);
				return this;
			}
		});
		var courseRoadmapTabViewClass = Backbone.View.extend({
			portlet: $('#courses-widget'),
			seasonsSynced	: false,
			classesSynced	: false,
			
			initialize: function(opt) {
				console.info('portlet.courses/courseRoadmapTabViewClass::initialize');

				this.collections = opt.collections;

				var self = this;
				this.$('#tab_course_roadmap-accordion').on('shown.bs.collapse', function (e) {
					self.$("a[href='#" + e.target.id + "']").prev("i").removeClass("icon-angle-right").addClass("icon-angle-down");
				});
				this.$('#tab_course_roadmap-accordion').on('hidden.bs.collapse', function (e) {
					self.$("a[href='#" + e.target.id + "']").prev("i").removeClass("icon-angle-down").addClass("icon-angle-right");
				});

				this.listenTo(this.collections.seasons, 'request', (function() {
					this.seasonsSynced = false;
				}).bind(this));
				this.listenTo(this.collections.classes, 'request', (function() {
					this.classesSynced = false;
				}).bind(this));
			
				this.listenTo(this.collections.seasons, 'sync', this.renderSeasons.bind(this));
				this.listenTo(this.collections.classes, 'sync', this.renderClasses.bind(this));

	            this.$("#tab_course_roadmap-accordion").sortable({
	                connectWith: ".list-group",
	                items: "li.list-group-item",
	                opacity: 0.8,
	                axis : "y",
	                placeholder: 'list-group-item list-group-item btn btn-block btn-default',
	                dropOnEmpty : true,
	                forceHelperSize : true,
	                forcePlaceholderSize: true,
	                tolerance: "intersect",
					sort : function( event, ui ) {
						console.warn(event, ui);
					}
	            });

				//this.$el.nestable();
			},
			renderSeasons : function(e) {
				this.seasonsSynced = true;
				this.render();
			},
			renderClasses : function(e) {
				this.classesSynced = true;
				this.render();
			},
			render : function() {
				console.info('portlet.courses/courseRoadmapTabViewClass::render');
				if (this.seasonsSynced && this.classesSynced) {
					// ORDER LESSONS BY SEMESTER
					//this.$el.empty();

					if (this.collections.seasons.size() > 0) {
						var self = this;
						
						this.collections.seasons.each((function (seasonModel, i) {
							// FILTER CLASSES TO RETURN ONLY CLASSES IN THAT SEASON
							/*
							var classesArray = this.collections.classes.filter(function (classModel) {
								console.warn(model.get("classes"), classModel.get("id"), _.contains(model.get("classes"), classModel.get("id")));
								return _.contains(model.get("classes"), classModel.get("id"));
							});
							*/
							var classesArray = [];
							this.collections.classes.each(function(classModel, i) {
								if (_.contains(seasonModel.get("classes"), classModel.get("id"))) {
									classesArray.push(classModel.toJSON());
								}
							});


							var courseRoadmapTabSeasonView = new courseRoadmapTabSeasonViewClass({model : seasonModel, collection : classesArray});
							self.$("#tab_course_roadmap-accordion").append(courseRoadmapTabSeasonView.render().el);
						}).bind(this));


						/*
						var classesArray = [];
						this.collections.classes.each(function(classModel, i) {
							if (_.contains(seasonModel.get("classes"), classModel.get("id"))) {
								classesArray.push(classModel.toJSON());
							}
						});
						*/
						
					}
					// SHOW THE CLASSES BACKLOGS
					// GET ALL CLASSES WITH SEASONS AND SHOW IN THE "BACKLOG"
					var allSeasonClassesIds = this.collections.seasons.pluck("classes");
					allSeasonClassesIds = _.flatten(allSeasonClassesIds);

					var noSeasonClasses = [];
					this.collections.classes.each(function(classModel, i) {
						if (!_.contains(allSeasonClassesIds, classModel.get("id"))) {
							noSeasonClasses.push(classModel.toJSON());
						}
					});
					var courseRoadmapTabSeasonView = new courseRoadmapTabSeasonViewClass({collection : noSeasonClasses});
					self.$("#tab_course_roadmap-accordion").append(courseRoadmapTabSeasonView.render().el);

					this.$("#tab_course_roadmap-accordion ul.list-group").each(function() {

					});
				}
			}
		});
		var courseRoadmapTabSeasonViewClass = Backbone.View.extend({
			template : _.template($("#tab_roadmap-season-template").html()),
			initialize : function() {
				
			},
			render : function() {
				console.info('portlet.courses/courseRoadmapTabSeasonViewClass::render');
				if (_.isUndefined(this.model)) {
					var modelData = {
						"id"	: "all",
						"name"	: "All other classes"
					};
				} else {
					var modelData = this.model.toJSON();
				}
				this.$el.append(this.template(_.extend(
					modelData,
					{classes : this.collection }
				)));

				var self = this;

				this.$(".list-group").sortable({
	                connectWith: ".list-group",
	                items: "li.list-group-item",
	                opacity: 0.8,
	                axis : "y",
	                placeholder: 'list-group-item list-group-item btn btn-block btn-default',
	                dropOnEmpty : true,
	                forceHelperSize : true,
	                forcePlaceholderSize: true,
	                tolerance: "intersect",
	                helper : 'original',
	                receive : function( event, ui ) {
						$(this).removeClass("empty-list-group");
						self.refreshCounters();
					},
					remove : function( event, ui ) {
						if ($(this).children().size() == 0) {
							$(this).addClass("empty-list-group");
						}
						self.refreshCounters();
					},
					over : function( event, ui ) {
						$(this).addClass("ui-sortable-hover");
					},
					out  : function( event, ui ) {
						$(this).removeClass("ui-sortable-hover");
					},
	            });

				return this;
			},
			refreshCounters : function() {
				this.$(".size-counter").html(
					this.$(".list-group").children().size()
				);
			}
		});

		var userProgressViewClass = Backbone.View.extend({
			el: $('#progress-content'),
			portlet: $('#courses-widget'),
			initialize: function() {
				//this.listenTo(this.model, 'change', this.renderSemester.bind(this));
				//this.listenTo(this.model, 'change:course_id', this.renderCourse.bind(this));
				//this.listenTo(this.model, 'change:lesson_id', this.renderLesson.bind(this));
				//this.listenTo(this.model, 'change:id', this.renderTopic.bind(this));
				this.render();
			},
			render : function() {
				if (jQuery.fn.easyPieChart) {
					this.$(".lesson").easyPieChart({
						animate: 1000,
						size: 75,
						lineWidth: 3,
						barColor: App.getLayoutColorCode('green')
					});
					this.$(".class").easyPieChart({
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

					
					this.renderCourse();
					this.renderSemester();
					this.renderClass();
					this.renderLesson();
				}
			},
			renderCourse : function() {
				/*
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
				*/
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				percent = 30;
				this.$(".course span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".course").data('easyPieChart').update(percent);
				}
			},
			renderSemester : function() {
				/*
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				percent = 40;
				this.$(".semester span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".semester").data('easyPieChart').update(percent);
				}
				*/
			},
			
			renderClass : function() {
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
				*/
				percent = 20;

				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				this.$(".class span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".class").data('easyPieChart').update(percent);
				}
			},
			renderLesson : function() {
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				percent = 80;
				this.$(".lesson span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".lesson").data('easyPieChart').update(percent);
				}
			}
		});

		this.courseWidgetViewClass = Backbone.View.extend({
			el: $('#courses-widget'),
			initialize: function(opt) {
				//opt.collections['course']
				//opt.collections['class']
				//opt.collections['lesson']
				this.$(".portlet-title").height(26);

				this.contentNavigationView = new contentNavigationViewClass({
					collections : opt.collections,
					el: "#courses-content-navigation"
				});

				this.courseView = new courseViewClass({
					model : opt.collections.course,
					//classes : opt.collections.class,
				});
				
				this.contentView = new contentViewClass({
					model : opt.collections.lesson
				});
				this.userProgressView = new userProgressViewClass();
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
		this.onSearch = function(e, portlet,q) {
			/*
			// INJECT
			this.contentView.$el.hide();
			this.filterActionView.reload();
			*/
			return false;
		};
		this.onResized = function(e, portlet) {
			//this.filterActionView.repaint();
		};
		this.onFullscreen = function(e, portlet) {
		};
		this.onRestorescreen = function(e, portlet) {
		};
	});

	mod.on("start", function() {
		var coursesModule 		= app.module("models.courses");
		var courseModelClass 	= coursesModule.courseModelClass;
		var classModelClass		= coursesModule.classModelClass;
		var lessonModelClass	= coursesModule.lessonModelClass;

		this.courseModel = new courseModelClass;
		this.classModel = new classModelClass({courses : this.courseModel});
		this.lessonModel = new lessonModelClass({classes : this.classModel});
		//this.contentModel = new contentModelClass();
		
		this.courseWidgetView = new this.courseWidgetViewClass({
			model : this.contentModel, 
			collections : {
				'course' 	: this.courseModel,
				'class'		: this.classModel,
				'lesson'	: this.lessonModel
			}
		});
	});

});