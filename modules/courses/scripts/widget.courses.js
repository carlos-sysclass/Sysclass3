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
		/*
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
		*/
		// TODO THINK ABOUT MOVE THIS CLASS INTO THE MAIN VIEW
		//
		var navigationViewClass = Backbone.View.extend({
			events : {
				//"click .class-change-action"		: "goToClass",
				//"click .class-next-action"		: "nextClass",
				//"click .lesson-prev-action"		: "prevLesson",
				//"click .lesson-next-action" 	: "nextLesson",
				"click .nav-prev-action" 		: "prevItem",
				"click .nav-next-action" 		: "nextItem",
				//"shown.bs.tab > .nav-tabs [data-toggle='tab']"		: "refreshScroll",
				//"click .nav-next-action" 		: "searchItem"
			},
			initialize : function(opt) {
				console.info('portlet.courses/navigationViewClass::initialize');
			},
			prevItem : function(e) {
				console.info('portlet.courses/navigationViewClass::prevItem');
				e.preventDefault();
				this.collection.prev();
			},
			nextItem : function(e) {
				console.info('portlet.courses/navigationViewClass::nextItem');
				e.preventDefault();
				this.collection.next();
			},
		});

		/* COURSE TABS VIEW CLASSES */
		var blockableTabViewClass = Backbone.View.extend({
			events : {
				"click .blockable-item" : "onBlockableItemClick"
			},
			blockUi : function(message) {
				var elemId = this.$el.attr('id');
				var mustHide = false;
				if (!$("#" + elemId).hasClass("active")) {
					$("#" + elemId).addClass("active");
					mustHide = true;
				}

				//$("[href='#" + elemId + "']").click();
				var html = '<div class="loading-message loading-message-boxed red">' +
					'<a href="javascript:void(0)" class="btn btn-lg blockable-item">' +
						'<i class="fa fa-edit"></i> ' + message +
					'</a>' +
				'</div>';

				this.$el.block({
					message: html,
					//baseZ: options.zIndex ? options.zIndex : 1000,
					centerY: true,
					css: {
						top: '10%',
						border: '0',
						padding: '0',
						backgroundColor: 'none'
					},
					overlayCSS: {
						backgroundColor: '#555',
						opacity: 0.1,
						cursor: 'wait'
					}
				});

				if (mustHide) {
					$("#" + elemId).removeClass("active");
				}

			},
			unBlockUi : function() {
				this.$el.unblock();
			},
			onBlockableItemClick : function() {

			}
		});

		var courseTabViewClass = blockableTabViewClass.extend({
			initialize: function(opt) {
				console.info('portlet.courses/courseTabViewClass::initialize');

				// TODO CREATE SUB VIEWS!!
				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-lesson"),
					collection : this.collection
				});

				this.listenTo(this.model, 'change:id', this.updateCollectionIndex.bind(this));
				this.listenTo(this.collection, 'sync', this.updateCollectionIndex.bind(this));

				this.listenTo(this.collection, 'prevModel nextModel', function(model, index, collection) {
					this.model.set("id", model.get("id"));
					this.model.fetch();
				}.bind(this));

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
				this.blockUi('No Course Selected');
			},
			render : function(e) {
				console.info('portlet.courses/courseTabViewClass::render');
				this.unBlockUi();
				this.$(".course-title").html(this.model.get("name"));
			},
			updateCollectionIndex : function(e) {
				console.info('portlet.courses/courseTabViewClass::updateCollectionIndex');

				this.collection.find(function(model, index, collection) {
					if (model.get("id") == this.model.get("id")) {
						this.collection.setPointer(index);
						return true;
					}
					return false;
				}.bind(this));
			},
			onBlockableItemClick : function(e) {
				// APLLY USER TO ENROLLMENT PROCESS
            	app.module("utils.toastr").message("warning", "The system will send you to the enroll page");

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
		var classTabViewClass = blockableTabViewClass.extend({
			initialize: function(opt) {
				console.info('portlet.courses/classTabViewClass::initialize');

				// TODO CREATE SUB VIEWS!!
				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-lesson"),
					collection : this.collection
				});

				this.listenTo(this.model, 'change:id', this.updateCollectionIndex.bind(this));
				this.listenTo(this.collection, 'sync', this.updateCollectionIndex.bind(this));

				this.listenTo(this.collection, 'prevModel nextModel', function(model, index, collection) {
					this.model.set("id", model.get("id"));
					this.model.fetch();
				}.bind(this));

				this.listenTo(this.model, 'sync', this.render.bind(this));



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

				this.blockUi('No Class Selected');
			},
			render : function(e) {
				console.info('portlet.courses/classTabViewClass::render');

				this.$(".course-title").html(this.model.get("course.name"));
				this.$(".class-title").html(this.model.get("class.name"));

				this.unBlockUi();
			},
			updateCollectionIndex : function(e) {
				console.info('portlet.courses/courseTabViewClass::updateCollectionIndex');

				this.collection.find(function(model, index, collection) {
					if (model.get("id") == this.model.get("id")) {
						this.collection.setPointer(index);
						return true;
					}
					return false;
				}.bind(this));
			},
			onBlockableItemClick : function(e) {
				$("[href='#course-tab']").click();
				$("[href='#tab_course_classes']").click();

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
				"click .lesson-change-action" : "setLessonId",
				"click .view-test-action" : "openDialog",
				"click .open-test-action" : "doTest"
			},
			testInfoModule : app.module("dialogs.tests.info"),
			tagName : "tr",
			template : _.template($("#tab_class_tests-item-template").html(), null, {variable: "model"}),
			setLessonId : function(e) {
				app.userSettings.set("lesson_id", this.model.get("id"));
			},
            openDialog : function() {
                if (!this.testInfoModule.started) {
                    this.testInfoModule.start();

                    this.listenTo(this.testInfoModule, "action:do-test", this.doTest.bind(this));
                }

                app.module("dialogs.tests.info").setInfo({
                	model : this.model
                });

                app.module("dialogs.tests.info").open();
            },
            doTest : function(model) {
            	//app.module("utils.toastr").message("info", "Test execution not disponible yet!");
            	//alert("Doing Test " + this.model.get("id"));
            	// START TEST EXECUTION this.model
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
		var lessonTabViewClass = blockableTabViewClass.extend({
			initialize: function() {
				console.info('portlet.courses/lessonTabViewClass::initialize');

				// TODO CREATE SUB VIEWS!!
				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-lesson"),
					collection : this.collection
				});

				this.listenTo(this.model, 'change:id', this.updateCollectionIndex.bind(this));
				this.listenTo(this.collection, 'sync', this.updateCollectionIndex.bind(this));

				this.listenTo(this.collection, 'prevModel nextModel', function(model, index, collection) {
					this.model.set("id", model.get("id"));
					this.model.fetch();
				}.bind(this));

				this.listenTo(this.model, 'sync', this.render.bind(this));

				// TODO CREATE SUB VIEWS!!
				//
				this.lessonVideoTabView 	= new lessonVideoTabViewClass({
					el : this.$("#tab_lesson_video"),
					model : this.model,
                	portlet : this.$el
				});

				this.listenTo(this.lessonVideoTabView, "video:viewed", this.setViewed.bind(this));

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

				this.blockUi('No Lesson Selected');

			},
			render : function(e) {
				console.info('portlet.courses/lessonTabViewClass::render');
				this.unBlockUi();
				//
				this.$(".class-title").html(this.model.get("class"));
				this.$(".lesson-title").html(this.model.get("name"));

				var factor = this.model.get("progress.factor");
				if (factor >= 1) {
					this.$(".viewed-status").removeClass("hidden");
				}
			},
			setViewed : function() {
				this.$(".viewed-status").removeClass("hidden");
			},
			updateCollectionIndex : function(e) {
				console.info('portlet.courses/courseTabViewClass::updateCollectionIndex');

				this.collection.find(function(model, index, collection) {
					if (model.get("id") == this.model.get("id")) {
						this.collection.setPointer(index);
						return true;
					}
					return false;
				}.bind(this));
			},
			onBlockableItemClick : function(e) {
				$("[href='#class-tab']").click();
				$("[href='#tab_class_lessons']").click();

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
				var self = this;

				if (_.size(this.model.get("contents")) == 0) {
					// THERE'S NO VIDEO LESSON... DISABLE THE VIEW
					this.disableView();
				} else {
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

					this.videoJS.ready(this.bindStartVideoEvents.bind(this));

					mod.videoJS = this.videoJS;

					app.module("ui").refresh(this.$el);
				}
			},
			bindStartVideoEvents : function() {
				var self = this;
				//this.videoJS.play();
				this.currentProgress = parseFloat(this.videoModel.get("progress.factor"));

				if (_.isNaN(this.currentProgress)) {
					this.currentProgress = 0;
				}

				if (this.currentProgress >= 1) {
					this.trigger("video:viewed");
				} else {

					this.videoJS.on("timeupdate", function() {
						// CALCULATE CURRENT PROGRESS
						var currentProgress = this.videoJS.currentTime() / this.videoJS.duration();

						if (currentProgress > this.currentProgress) {
							var progressDiff =  currentProgress - this.currentProgress;
							if (progressDiff > 0.03 ) {
								this.currentProgress = currentProgress;
								//this.videoModel.set("progress", this.currentProgress);
								var progressModel = new mod.models.content_progress(this.videoModel.get("progress"));
								progressModel.setAsViewed(this.videoModel, this.currentProgress);
							}
						}

					}.bind(this));

					this.videoJS.on("ended", function() {
						this.currentProgress = 1;
						var progressModel = new mod.models.content_progress(this.videoModel.get("progress"));
						progressModel.setAsViewed(this.videoModel, this.currentProgress);

						this.trigger("video:viewed");
					}.bind(this));
				}
			},
			disableView : function() {
				//this.$el.hide();
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
			events : {
				"click .view-content-action" : "viewContentAction"
			},
			tagName : "tr",
			template : _.template($("#tab_lessons_materials-item-template").html(), null, {variable: "model"}),
			initialize : function() {
				this.listenTo(this.model, "change", this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.courses/lessonMaterialsTabViewItemClass::render');
				this.$el.html(
					this.template(this.model.toJSON())
				);
				return this;
			},
			viewContentAction : function(e) {
				// TRACK PROGRESS
				var progressModel = new mod.models.content_progress();
				progressModel.setAsViewed(this.model);

				//e.preventDefault();
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
			//childViewClass : lessonExercisesTabViewItemQuestionClass,
			openExercise : function(e) {
				this.parent.loadExerciseDetails(this.model);
			},
			/*
			onBeforeRender : function() {
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
			exerciseTemplate : _.template($("#tab_lesson_exercises-details-template").html(), null, {variable: "model"}),
			onBeforeRender : function(e) {
				console.info('portlet.courses/lessonExercisesTabViewClass::onBeforeRender');
				var contentsCollection = new mod.collections.contents(this.model.get("contents"));
				this.collection = contentsCollection.getExercises();
			},
			loadExerciseDetails : function(model) {
				var self = this;

				this.$(".exercises-container").html(
					this.exerciseTemplate(model.toJSON())
				);

				_.each(model.get("exercise"), function(data, index) {
					var innermodel = new mod.models.questions(data);

					var questionView = new lessonExercisesQuestionItemClass({
						model : innermodel,
						model_index : index
					});
					this.$(".question-container").append(questionView.render().el);
				}.bind(this));

				app.module("ui").refresh(this.$(".exercises-container"));
			}
		});

		/* TESTS AND EXERCISES UTILITY VIEWS */
		var lessonExercisesQuestionItemClass = Backbone.View.extend({
			tagName : "li",
			templates : {
				"combine" : _.template($("#tab_lesson_exercises-question-combine-template").html(), null, {variable: "model"}),
				"true_or_false" : _.template($("#tab_lesson_exercises-question-true_or_false-template").html(), null, {variable: "model"}),
				"simple_choice" : _.template($("#tab_lesson_exercises-question-simple_choice-template").html(), null, {variable: "model"}),
				"multiple_choice" : _.template($("#tab_lesson_exercises-question-multiple_choice-template").html(), null, {variable: "model"}),
				"fill_blanks" : _.template($("#tab_lesson_exercises-question-fill_blanks-template").html(), null, {variable: "model"}),
				"free_text" : _.template($("#tab_lesson_exercises-question-free_text-template").html(), null, {variable: "model"})
			},
	        initialize: function(opt) {
	            this.model_index = opt.model_index;
	        },
			render : function() {
				if (_.has(this.templates, this.model.get("type_id"))) {
					var template = this.templates[this.model.get("type_id")];
		            this.$el.html(
		                template(_.extend(this.model.toJSON(), {
		                    model_index : this.model_index
		                }))
		            );

				}
	            return this;
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
			coursesCollection : null,
			courseModel : null,
			courseTabView : null,
			classesCollection : null,
			classModel : null,
			classTabView : null,
			lessonsCollection : null,
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
				//this.listenTo(this.model, 'change:course_id', this.startCourseView.bind(this));

				//if (this.model.get("course_id")) {
					this.startCourseView();
				//} else {
					// NO COURSE SELECT, SO OPEN A BLOCK FOR SELECTION
				//}

				//this.listenTo(this.model, 'change:class_id', this.startClassView.bind(this));
				//if (this.model.get("class_id")) {
					//this.startClassView();
				//}


				//this.listenTo(this.model, 'change:lesson_id', this.startLessonView.bind(this));
				//if (this.model.get("lesson_id")) {
					//this.startLessonView();
				//}

				Marionette.triggerMethodOn(this, "start");
			},
			startCourseView : function() {
				console.info('portlet.courses/courseWidgetViewClass::startCourseView');
				var bindEvents = false;
				if (_.isNull(this.courseModel)) {
					this.courseModel = new fullCourseModelClass();

					this.listenToOnce(this.courseModel, 'sync', function(model, model_id) {
						// CREATE CLASS VIEW
						this.startClassView();
					}.bind(this));

					this.listenTo(this.courseModel, 'sync', function(model, model_id) {
						this.model.set("course_id", model.get("id"), {silent : true});

						// SET CLASS.... IF THE CURRENT IS INSIDE, JUST FETCH, IF NOT, SELECT THE FIRST ONE
						var currentClass = this.model.get("class_id");

						var exists = _.findWhere(model.get("classes"), {id : currentClass});

						if (!_.isUndefined(exists)) {
							if (!_.isNull(this.classModel)) {
								this.classModel.set("id", exists.id);
								this.classModel.fetch();
							} else {
								this.model.save();
							}
							if (!_.isNull(this.classesCollection)) {
								this.classesCollection.reset(exists.classes);
								if (_.size(exists.classes) > 0) {
									this.classesCollection.setPointer(0);
								} else {
									this.classesCollection.setPointer(-1);
									this.classTabView.blockUi("No classes avaliable");
								}
							}
						} else {
							var firstClass = _.first(model.get("classes"));
							if (_.isObject(firstClass) && _.has(firstClass, 'id')) {
								if (!_.isNull(this.classModel)) {
									this.classModel.set("id", firstClass.id);
									this.classModel.fetch();
								}
							} else {
								this.model.set("class_id", null, {silent: true});
								this.model.set("lesson_id", null, {silent: true});
								this.model.save();
							}
							if (!_.isNull(this.classesCollection)) {
								this.classesCollection.reset(model.get("classes"));
								if (_.size(model.get("classes")) > 0) {
									this.classesCollection.setPointer(0);
								} else {
									this.classesCollection.setPointer(-1);
									this.classTabView.blockUi("No classes avaliable");
								}
							}
						}
						// RESELECT THE CLASS
						//this.model.save();

					}.bind(this));
				}
				if (_.isNull(this.coursesCollection)) {
					this.coursesCollection = new mod.collections.courses();
				}

				if (_.isNull(this.courseTabView)) {
					this.courseTabView = new courseTabViewClass({
						model : this.courseModel,
						collection : this.coursesCollection,
						el : this.$("#course-tab"),
            			portlet : this.$el
						//classes : opt.collections.class,
					});
				}

				if (this.model.get("course_id")) {
					// create a view to show a list off courses to select
					// IF THERE'S ONLY A COURSE, AUTO SELECT
					this.courseModel.set("id", this.model.get("course_id"), {silent : true});
					this.courseModel.fetch();

					this.coursesCollection.fetch();
				}
			},
			startClassView : function() {
				console.info('portlet.courses/courseWidgetViewClass::startClassView');
				console.info('portlet.courses/courseWidgetViewClass::startCourseView');
				if (_.isNull(this.classModel)) {
					this.classModel = new fullClassModelClass();

					this.listenToOnce(this.classModel, 'sync', function(model, model_id) {
						// CREATE CLASS VIEW
						this.startLessonView();
					}.bind(this));

					this.listenTo(this.classModel, 'sync', function(model) {
						this.model.set("class_id", model.get("id"), {silent : true});

						var currentLesson = this.model.get("lesson_id");

						var exists = _.findWhere(model.get("lessons"), {id : currentLesson});

						if (!_.isUndefined(exists)) {
							if (!_.isNull(this.lessonModel)) {
								this.lessonModel.set("id", exists.id);
								this.lessonModel.fetch();
							} else {
								this.model.save();
							}
							if (!_.isNull(this.classesCollection)) {
								this.lessonsCollection.reset(exists.lessons);
								if (_.size(exists.lessons) > 0) {
									this.lessonsCollection.setPointer(0);
								} else {
									this.lessonsCollection.setPointer(-1);
									this.lessonTabView.blockUi("No lessons avaliable");
								}
							}
						} else {
							var firstLesson = _.first(model.get("lessons"));
							if (_.isObject(firstLesson) && _.has(firstLesson, 'id')) {
								if (!_.isNull(this.lessonModel)) {
									this.lessonModel.set("id", firstLesson.id);
									this.lessonModel.fetch();
								}
							} else {
								this.model.set("lesson_id", null, {silent: true});
								this.model.save();
							}
							if (!_.isNull(this.lessonsCollection)) {
								this.lessonsCollection.reset(model.get("lessons"));
								if (_.size(model.get("lessons")) > 0) {
									this.lessonsCollection.setPointer(0);
								} else {
									this.lessonsCollection.setPointer(-1);
									this.lessonTabView.blockUi("No lessons avaliable");
								}
							}
						}
						// RESELECT THE CLASS
						//this.model.save();
					}.bind(this));
				}

				if (_.isNull(this.classesCollection)) {
					this.classesCollection = new mod.collections.classes(this.courseModel.get("classes"));
				}

				if (_.isNull(this.classTabView)) {
					this.classTabView = new classTabViewClass({
						model : this.classModel,
						collection : this.classesCollection,
						el : this.$("#class-tab"),
            			portlet : this.$el
						//classes : opt.collections.class,
					});
				}

				if (this.model.get("class_id")) {
					// create a view to show a list off courses to select
					// IF THERE'S ONLY A COURSE, AUTO SELECT
					this.classModel.set("id", this.model.get("class_id"), {silent : true});
					//this.classModel.fetch();

					this.classesCollection.fetch();
				}
			},
			startLessonView : function() {
				console.info('portlet.courses/courseWidgetViewClass::startLessonView');
				if (_.isNull(this.lessonModel)) {
					this.lessonModel = new fullLessonModelClass();

					this.listenTo(this.lessonModel, 'sync', function(model) {
						this.model.set("lesson_id", model.get("id"), {silent : true});

						// RESELECT THE CLASS
						this.model.save();
					}.bind(this));
				}

				if (_.isNull(this.lessonsCollection)) {
					this.lessonsCollection = new mod.collections.lessons(this.classModel.get("lessons"));
				}

				if (_.isNull(this.lessonTabView)) {
					this.lessonTabView = new lessonTabViewClass({
						model : this.lessonModel,
						collection : this.lessonsCollection,
						el : this.$("#lesson-tab"),
            			portlet : this.$el
						//classes : opt.collections.class,
					});
				}

				if (this.model.get("lesson_id")) {
					// create a view to show a list off courses to select
					// IF THERE'S ONLY A COURSE, AUTO SELECT
					this.lessonModel.set("id", this.model.get("lesson_id"), {silent : true});
					//this.classModel.fetch();

					this.lessonsCollection.fetch();
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

	this.models = {
		questions : Backbone.DeepModel.extend({}),
		content_progress : Backbone.DeepModel.extend({
			urlRoot : "/module/roadmap/item/content-progress",
			setAsViewed : function(model, factor) {
				if (_.isUndefined(factor)) {
					factor = 1;
				}
				this.set("content_id", model.get("id"));
				this.set("factor", factor);
				this.save();

				model.set("progress", this.toJSON());
			}
		})
	};

	var navigableCollection = Backbone.Collection.extend({
		initialize : function(data) {
			this.pointer = -1;
			if (_.size(data) > 0) {
				this.pointer = 0;
			}
		},
		setPointer : function(pointer) {
			this.pointer = pointer;
		},
		prev : function() {
			var newPointer = _.max([0, this.pointer-1]);
			if (newPointer != this.pointer) {
				this.pointer = newPointer;
				this.trigger("prevModel", this.at(this.pointer), this.pointer, this);
			}
			return this.pointer;

		},
		next : function() {
			var newPointer = _.min([this.size()-1, this.pointer+1]);
			if (newPointer != this.pointer) {
				this.pointer = newPointer;
				this.trigger("nextModel", this.at(this.pointer), this.pointer, this);
			}
			return this.pointer;
		}
	});

	this.collections = {
		courses : navigableCollection.extend({
			//model : fullCourseModelClass,
			url : "/module/roadmap/items/courses",
		}),
		classes : navigableCollection.extend({}),
		lessons : navigableCollection.extend({}),
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

				if (_.size(childs) > 0) {
					// GET SUBTITLES CHILDS
					var subchilds = _.map(
						this.where({
							parent_id : childs[0].id
						}),
						function(model, index) {
							return model.toJSON();
						}
					);
					mainVideo.set("childs", _.union(childs, subchilds));
				} else {
					mainVideo.set("childs", childs);
				}

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
			}
		}),
		questions : Backbone.Collection.extend({

		})
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
