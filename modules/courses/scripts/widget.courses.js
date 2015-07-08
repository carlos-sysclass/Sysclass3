$SC.module("portlet.courses", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.on("start", function() {
		var parent = app.module("portlet");
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
				"click .nav-next-action" 		: "nextItem",
				"shown.bs.tab > .nav-tabs [data-toggle='tab']"		: "refreshScroll",
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

				this.refreshScroll({
					currentTarget : this.$("> .nav-tabs li.active [data-toggle='tab']")
				});

			},
			refreshScroll : function(e) {
				console.info('portlet.courses/contentNavigationViewClass::refreshScroll');
				var context = $(e.currentTarget).attr("href");
				app.module("ui").handleScrollers($(context));
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

		/*
		CONTENT TABS VIEW CLASSES (TO_REVIEW )

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

					var videoData = _.pick(entityData["data"], "controls", "preload", "autoplay", "poster", "techOrder", "width", "height", "ytcontrols");
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

				this.contentMaterialsView = new contentMaterialsViewClass({model : this.model, el : "#tab_lesson_materials_container"});

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
		*/

		/* COURSE TABS VIEW CLASSES */
		var courseTabViewClass = Backbone.View.extend({
			initialize: function() {
				console.info('portlet.courses/courseTabViewClass::initialize');

				this.listenTo(this.model, 'sync', this.render.bind(this));

				// TODO CREATE SUB VIEWS!!
				this.courseDescriptionTabView 	= new courseDescriptionTabViewClass({
					el : this.$("#tab_course_description"),
					model : this.model
				});

				this.courseClassesTabView = new courseClassesTabViewClass({
					el : this.$("#tab_course_classes table tbody"),
					model : this.model/*,
					collection : new mod.collections.classes(this.model.get("classes")) */
				});

			},
			render : function(e) {
				console.info('portlet.courses/courseTabViewClass::render');
			}
		});

		var courseDescriptionTabViewClass = Backbone.View.extend({
			template : _.template($("#tab_course_description-template").html(), null, {variable : 'data'}),
			initialize: function() {
				console.info('portlet.courses/courseDescriptionTabViewClass::initialize');
				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/courseDescriptionTabViewClass::render');
				this.$(".scroller").empty().append(this.template(this.model.toJSON()));
			}
		});

		var courseClassesTabViewClass = Backbone.View.extend({
			nofoundTemplate : _.template($("#tab_course_classes-nofound-template").html()),

			initialize: function(opt) {
				console.info('portlet.courses/courseClassesTabViewClass::initialize');

				this.template = _.has(opt, "template") ? opt.template : this.template;

				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/courseClassesTabViewClass::render');

				this.collection = new mod.collections.classes(this.model.get("classes"));

				this.$el.empty();

				if (this.collection.size() === 0) {
					this.$el.append(this.nofoundTemplate());
				} else {
					var self = this;
					this.collection.each(function(model, i) {
						var courseClassesTabViewItem = new courseClassesTabViewItemClass({model : model});
						self.$el.append(courseClassesTabViewItem.render().el);
					});
				}
				app.module("ui").refresh(this.$el);
			}
		});
		var courseClassesTabViewItemClass = Backbone.View.extend({
			events : {
				"click .class-change-action" : "setClassId"
			},
			tagName : "tr",
			template : _.template($("#tab_course_classes-item-template").html(), null, {variable: "model"}),
			setClassId : function(e) {
				app.userSettings.set("class_id", this.model.get("id"));
			},
			render : function(e) {
				console.info('portlet.courses/courseClassesTabViewItemClass::render');
				this.$el.html(
					this.template(this.model.toJSON())
				);
				return this;
			}
		});

		/*
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
							var classesArray = [];
							this.collections.classes.each(function(classModel, i) {
								if (_.contains(seasonModel.get("classes"), classModel.get("id"))) {
									classesArray.push(classModel.toJSON());
								}
							});


							var courseRoadmapTabSeasonView = new courseRoadmapTabSeasonViewClass({model : seasonModel, collection : classesArray});
							self.$("#tab_course_roadmap-accordion").append(courseRoadmapTabSeasonView.render().el);
						}).bind(this));

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
		*/

		/* CLASSES TABS VIEW CLASSES */
		var classTabViewClass = Backbone.View.extend({
			initialize: function(opt) {
				console.info('portlet.courses/classTabViewClass::initialize');

				this.classInfoTabView = new classInfoTabViewClass({
					el : this.$("#tab_class_info"),
					model : this.model
				});

				this.classInstructorTabView = new classInstructorTabViewClass({
					el : this.$("#tab_class_instructor"),
					model : this.model
				});

				this.classLessonsTabView = new classLessonsTabViewClass({
					el : this.$("#tab_class_lessons table tbody"),
					model : this.model
				});

				this.classTestsTabView = new classTestsTabViewClass({
					el : this.$("#tab_class_tests table tbody"),
					model : this.model
				});

				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/classTabViewClass::render');
			}
		});
		var classInfoTabViewClass = Backbone.View.extend({
			//portlet: $('#courses-widget'),
			template : _.template($("#tab_classes-info-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.courses/classInfoTabViewClass::initialize');
				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/classInfoTabViewClass::render');
				this.$(".scroller").empty().append(this.template(this.model.toJSON()));
			}
		});

		var classInstructorTabViewClass = Backbone.View.extend({
			//portlet: $('#courses-widget'),
			template : _.template($("#tab_classes-instructor-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.courses/classInstructorTabViewClass::initialize');
				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/classInstructorTabViewClass::render');
				this.$(".scroller").empty();

				if (_.size(this.model.get("class.instructors")) === 0) {
					this.disable();
				} else {
					this.enable();
					this.$(".scroller").html(this.template(this.model.toJSON()));
				}

			},
			disable : function() {
				//this.$el.hide();
				var elId = this.$el.attr("id");
				$("[href='#" + elId + "']").hide();
			},
			enable : function() {
				//this.$el.show();
				var elId = this.$el.attr("id");
				$("[href='#" + elId + "']").show();
			}
		});

		var baseClassChildTabViewItemClass = Backbone.View.extend({
			tagName : "tr",
			template : _.template($("#tab_class_lessons-item-template").html(), null, {variable: "model"}),
			render : function(e) {
				console.info('portlet.courses/baseClassChildTabViewItemClass::render');
				this.$el.html(
					this.template(this.model.toJSON())
				);
				return this;
			}
		});
		var classLessonsTabViewItemClass = baseClassChildTabViewItemClass.extend({
			events : {
				"click .lesson-change-action" : "setLessonId"
			},
			//template : _.template($("#tab_class_lessons-item-template").html(), null, {variable: "model"}),
			setLessonId : function(e) {
				app.userSettings.set("lesson_id", this.model.get("id"));
			}
		});
		var classTestsTabViewItemClass = baseClassChildTabViewItemClass.extend({
			events : {
				"click .lesson-change-action" : "setLessonId"
			},
			tagName : "tr",
			template : _.template($("#tab_class_lessons-item-template").html(), null, {variable: "model"}),
			setLessonId : function(e) {
				app.userSettings.set("lesson_id", this.model.get("id"));
			}
		});

		var baseClassChildTabViewClass = Backbone.View.extend({
			nofoundTemplate : _.template($("#tab_class_child-nofound-template").html()),

			initialize: function(opt) {
				console.info('portlet.courses/baseClassChildTabViewClass::initialize');

				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/baseClassChildTabViewClass::render');

				this.collection = this.makeCollection();

				this.$el.empty();

				if (this.collection.size() === 0) {
					this.$el.append(this.nofoundTemplate());
				} else {
					var self = this;
					this.collection.each(function(model, i) {
						var childView = new self.childViewClass({model : model});
						self.$el.append(childView.render().el);
					});
				}
				app.module("ui").refresh(this.$el);
			}
		});
		var classLessonsTabViewClass = baseClassChildTabViewClass.extend({
			childViewClass : classLessonsTabViewItemClass,
			makeCollection: function() {
				var collection = new mod.collections.lessons(this.model.get("lessons"));
				return collection;
			}
		});
		var classTestsTabViewClass = classLessonsTabViewClass.extend({
			childViewClass : classTestsTabViewItemClass,
			makeCollection: function() {
				var collection = new mod.collections.tests(this.model.get("tests"));
				return collection;
			}
		});

		/* LESSONS / TESTS VIEW CLASSES */
		var lessonTabViewClass = Backbone.View.extend({
			initialize: function() {
				console.info('portlet.courses/lessonTabViewClass::initialize');
				this.listenTo(this.model, 'sync', this.render.bind(this));

				// TODO CREATE SUB VIEWS!!
				//
				this.lessonVideoTabView 	= new lessonVideoTabViewClass({
					el : this.$("#tab_lesson_video"),
					model : this.model,
                	portlet : this.$el
				});

				this.lessonMaterialsTabView 	= new lessonMaterialsTabViewClass({
					el : this.$("#tab_lesson_materials table tbody"),
					model : this.model,
                	portlet : this.$el
				});

				this.lessonExercisesTabView 	= new lessonExercisesTabViewClass({
					el : this.$("#tab_lesson_exercises"),
					model : this.model,
                	portlet : this.$el
				});
			},
			render : function(e) {
				console.info('portlet.courses/courseTabViewClass::render');
			}
		});

		var lessonVideoTabViewClass = Backbone.View.extend({
			videoJS : null,
			nofoundTemplate : _.template($("#tab_lessons_video-nofound-template").html()),
			template : _.template($("#tab_lessons_video-item-template").html(), null, {variable: "model"}).bind(this),
			initialize: function(opt) {
				console.info('portlet.courses/lessonVideosTabViewClass::initialize');
				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/lessonVideosTabViewClass::render');
				var contentsCollection = new mod.collections.contents(this.model.get("contents"));
				this.videoModel = contentsCollection.getMainVideo();

				if (!_.isNull(this.videoJS)) {
					this.videoJS.dispose();
				}

				var videoDomID = "lesson-video-" + this.videoModel.get("id");

				if (this.$("#" + videoDomID).size() === 0) {
					this.$el.empty().append(
						this.template(this.videoModel.toJSON())
					);

					//var videoData = _.pick(entityData["data"], "controls", "preload", "autoplay", "poster", "techOrder", "width", "height", "ytcontrols");
					videojs(videoDomID, {
						"controls": true,
						"autoplay": false,
						"preload": "auto",
						"width" : "auto",
						"height" : "auto",
						"techOrder" : [
							'html5', 'flash'
						]
					}, function() {
						//this.play();
					});
				}

				this.videoJS = videojs(videoDomID);

				mod.videoJS = this.videoJS;

				app.module("ui").refresh(this.$el);
			}
		});

		var lessonVideosTabViewItemClass = Backbone.View.extend({
			/*
			events : {
				"click .class-change-action" : "setClassId"
			},
			*/
			tagName : "tr",
			//template : _.template($("#tab_lessons_videos-item-template").html(), null, {variable: "model"}),
			/*
			setClassId : function(e) {
				app.userSettings.set("class_id", this.model.get("id"));
			},
			*/
			render : function(e) {
				console.info('portlet.courses/lessonVideosTabViewItemClass::render');
				this.$el.html(
					this.template(this.model.toJSON())
				);
				return this;
			}
		});

		var lessonMaterialsTabViewClass = Backbone.View.extend({
			nofoundTemplate : _.template($("#tab_lessons_materials-nofound-template").html()),
			initialize: function(opt) {
				console.info('portlet.courses/lessonMaterialsTabViewClass::initialize');
				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/lessonMaterialsTabViewClass::render');
				var contentsCollection = new mod.collections.contents(this.model.get("contents"));
				this.collection = contentsCollection.getMaterials();

				this.$el.empty();

				if (this.collection.size() === 0) {
					this.$el.append(this.nofoundTemplate());
				} else {
					var self = this;
					this.collection.each(function(model, i) {
						var lessonFilesTabViewItem = new lessonMaterialsTabViewItemClass({model : model});
						self.$el.append(lessonFilesTabViewItem.render().el);
					});
				}
				app.module("ui").refresh(this.$el);
			}
		});

		var lessonMaterialsTabViewItemClass = Backbone.View.extend({
			/*
			events : {
				"click .class-change-action" : "setClassId"
			},
			*/
			tagName : "tr",
			template : _.template($("#tab_lessons_materials-item-template").html(), null, {variable: "model"}),
			/*
			setClassId : function(e) {
				app.userSettings.set("class_id", this.model.get("id"));
			},
			*/
			render : function(e) {
				console.info('portlet.courses/lessonMaterialsTabViewItemClass::render');
				this.$el.html(
					this.template(this.model.toJSON())
				);
				return this;
			}
		});
		/*
		var lessonExercisesTabViewItemQuestionClass = parent.blockViewItemClass.extend({
			template : _.template($("#tab_lesson_exercises-item-question-template").html(), null, {variable : 'data'}),
		});
		*/

		var lessonExercisesTabViewItemClass = parent.blockViewItemClass.extend({
			events : {
				"click .open-exercise-action" : "openExercise"
			},
			tagName : "tr",
			//nofoundTemplate : _.template($("#tab_lesson_exercises-nofound-template").html()),
			template : _.template($("#tab_lesson_exercises-item-template").html(), null, {variable : 'model'}),
			openExercise : function(e) {

			}
			//childViewClass : lessonExercisesTabViewItemQuestionClass,
			/*
			onBeforeRender : function() {
				console.warn(this.model.toJSON());
				this.collection = new mod.collections.questions(this.model.get("exercise"));
			},
			onRender : function() {
				// INJECT QUESTIONS INSIDE MODEL
				var self = this;
				var container = this.$(".inner-container");

				container.empty();

                this.collection.each(function(model, i) {

                    var childView = new self.childViewClass({
                        model : model,
                        portlet : self.portlet
                    });
                    container.append(childView.render().el);
                });
			}
			*/
		});

		var lessonExercisesTabViewClass = parent.blockViewClass.extend({
			nofoundTemplate : _.template($("#tab_lesson_exercises-nofound-template").html()),
			childViewClass : lessonExercisesTabViewItemClass,
			childContainer : "table tbody",
			onBeforeRender : function(e) {
				console.info('portlet.courses/lessonExercisesTabViewClass::onBeforeRender');
				var contentsCollection = new mod.collections.contents(this.model.get("contents"));
				this.collection = contentsCollection.getExercises();
			}
		});

		/*
		var classDropboxTabViewClass = Backbone.View.extend({
			//portlet: $('#courses-widget'),
			//template: _.template($('#courses-content-materials-template').html()),
			initialize: function() {
				//this.$el.empty();
				console.info('portlet.courses/contentMaterialsViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));

				this.render();
			},
			render : function() {
				console.info('portlet.courses/contentMaterialsViewClass::render');

				//var entityData = this.model.get("data");
				//var sources = entityData['sources'];
				//if (typeof sources['materials'] != undefined) {
					var fileTreeCollectionClass = app.module("models.courses").fileTreeCollectionClass;
					// GET HERE SEND AND RECEIVED FILES FROM DROPBOX, BASED ON THIS CLASS
					this.fileTree = new fileTreeCollectionClass({source: "/module/courses/materials/list/47/188/4297"});
					//this.fileTree.fetch();
				//} else {

				//}
	            this.$('.tree-professor').tree({
	                selectable: false,
	                dataSource: this.fileTree,
	                loadingHTML: '<img src="/assets/default/img/input-spinner.gif"/>',
	            });
	            this.$('.tree-student').tree({
	                selectable: false,
	                dataSource: this.fileTree,
	                loadingHTML: '<img src="/assets/default/img/input-spinner.gif"/>',
	            });

	            return this;
			}
		});
		*/
		/*
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
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				percent = 30;
				this.$(".course span").html(percent);

				if (jQuery.fn.easyPieChart) {
					this.$(".course").data('easyPieChart').update(percent);
				}
			},
			renderSemester : function() {
			},

			renderClass : function() {
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
		*/

		this.courseWidgetViewClass = parent.widgetViewClass.extend({
			courseModel : null,
			courseTabView : null,
			classModel : null,
			classTabView : null,
			lessonModel : null,
			lessonTabView : null,
			start : function() {
				console.info('portlet.courses/courseWidgetViewClass::start');
				Marionette.triggerMethodOn(this, "beforeStart");
				// CREATE SUB-VIEWS AND FETCH MODELS AND COLLECTIONS

				//opt.collections['course']
				//opt.collections['class']
				//opt.collections['lesson']
				this.$(".portlet-title").height(26);

				/*
				this.contentNavigationView = new contentNavigationViewClass({
					collections : opt.collections,
					el: "#courses-content-navigation"
				});
				*/
				this.listenTo(this.model, 'change:course_id', this.startCourseView.bind(this));
				if (this.model.get("course_id")) {
					this.startCourseView();
				}



				this.listenTo(this.model, 'change:class_id', this.startClassView.bind(this));
				if (this.model.get("class_id")) {
					this.startClassView();
				}


				this.listenTo(this.model, 'change:lesson_id', this.startLessonView.bind(this));
				if (this.model.get("lesson_id")) {
					this.startLessonView();
				}

				Marionette.triggerMethodOn(this, "start");
			},
			startCourseView : function() {
				console.info('portlet.courses/courseWidgetViewClass::startCourseView');
				if (this.model.get("course_id")) {
					if (_.isNull(this.courseModel)) {
						this.courseModel = new fullCourseModelClass();
					}

					this.courseModel.set("id", this.model.get("course_id"));
					//courseModel.set("id", "1");

					if (_.isNull(this.courseTabView)) {
						this.courseTabView = new courseTabViewClass({
							model : this.courseModel,
							el : this.$("#course-tab"),
                			portlet : this.$el
							//classes : opt.collections.class,
						});
					}
					this.courseModel.fetch();
				}
			},
			startClassView : function() {
				console.info('portlet.courses/courseWidgetViewClass::startClassView');
				if (this.model.get("class_id")) {
					if (_.isNull(this.classModel)) {
						this.classModel = new fullClassModelClass();
					}

					this.classModel.set("id", this.model.get("class_id"));
					//courseModel.set("id", "1");

					if (_.isNull(this.classTabView)) {
						this.classTabView = new classTabViewClass({
							model : this.classModel,
							el : this.$("#class-tab"),
                			portlet : this.$el
							//classes : opt.collections.class,
						});
					}
					this.classModel.fetch();
				}
			},
			startLessonView : function() {
				console.info('portlet.courses/courseWidgetViewClass::startLessonView');
				if (this.model.get("lesson_id")) {
					if (_.isNull(this.lessonModel)) {
						this.lessonModel = new fullLessonModelClass();
					}

					this.lessonModel.set("id", this.model.get("lesson_id"));
					//courseModel.set("id", "1");

					if (_.isNull(this.lessonTabView)) {
						this.lessonTabView = new lessonTabViewClass({
							model : this.lessonModel,
							el : this.$("#lesson-tab"),
                			portlet : this.$el
							//classes : opt.collections.class,
						});
					}
					this.lessonModel.fetch();
				}
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
			return true;
		};
		this.onFullscreen = function(e, portlet) {
			return true;
		};
		this.onRestorescreen = function(e, portlet) {
			return true;
		};
	});
	/*
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
	*/
	var fullCourseModelClass = Backbone.DeepModel.extend({
		urlRoot : "/module/roadmap/item/courses"
	});
	var fullClassModelClass = Backbone.DeepModel.extend({
		urlRoot : "/module/roadmap/item/classes"
	});
	var fullLessonModelClass = Backbone.DeepModel.extend({
		urlRoot : "/module/roadmap/item/lessons"
	});
	var contentModelClass = Backbone.DeepModel.extend({
		isVideo : function() {
			return /^video\/.*$/.test(this.get("file.type"));
		},
		isAudio : function() {
			return /^audio\/.*$/.test(this.get("file.type"));
		},
		isPdf : function() {
			return /.*\/pdf$/.test(this.get("file.type"));
		},
		isImage : function() {
			return /^image\/.*$/.test(this.get("file.type"));
		},
		isMaterial : function() {
			return !this.isVideo() && !this.isAudio() && !this.isImage();
		}
	});


	this.collections = {
		classes : Backbone.Collection.extend({}),
		lessons : Backbone.Collection.extend({}),
		tests : Backbone.Collection.extend({}),
		contents : Backbone.Collection.extend({
			model: contentModelClass,
			getMainVideo : function() {
				var filteredCollection = this.where({
					content_type : "file"
				});

				var filteredVideoCollection = _.filter(filteredCollection, function(model, index) {
					return model.isVideo();
				});

				if (_.size(filteredVideoCollection) === 0) {
					return false;
				}

				var mainVideo = _.findWhere(filteredVideoCollection, {main : "1"});

				if (_.size(mainVideo) === 0) {
					mainVideo = _.first(filteredVideoCollection);
				}

				// GET CHILDS OBJECTS
				var childs = _.map(
					this.where({
						parent_id : mainVideo.get("id")
					}),
					function(model, index) {
						return model.toJSON();
					}
				);

				mainVideo.set("childs", childs);

				return mainVideo;
			},
			getMaterials : function() {
				var filteredCollection = this.where({
					content_type : "file"
				});

				filteredCollection = _.filter(filteredCollection, function(model, index) {
					return model.isMaterial();
				});
				return new mod.collections.contents(filteredCollection);
			},
			getExercises : function() {
				var filteredCollection = this.where({
					content_type : "exercise"
				});

				return new mod.collections.contents(filteredCollection);
			},

		}),
		questions : Backbone.Collection.extend({})
	};

	mod.on("start", function() {
		//var userSettingsModel = new userSettingsModelClass();

		this.listenToOnce(app.userSettings, "sync", function(model, data, options) {

			this.courseWidgetView = new this.courseWidgetViewClass({
				model : app.userSettings,
				el: '#courses-widget'
			});

		}.bind(this));
	});

});
