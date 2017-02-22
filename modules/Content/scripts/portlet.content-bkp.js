$SC.module("portlet.content", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.on("start", function() {
		var parent = app.module("portlet");
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
			pointer: 0,
			initialize : function(opt) {
				console.info('portlet.content/navigationViewClass::initialize');

				this.pointer = opt.pointer;
			},
			render : function() {
				this.$(".entity-count")
					.html(this.collection().size());
					//cnsole.warn(this.$(".entity-current"), this.pointer);
				this.$(".entity-current")
					.html(this.pointer() + 1);
			},
			prevItem : function(e) {
				console.info('portlet.content/navigationViewClass::prevItem');
				//this.pointer--;

				e.preventDefault();

				this.collection().prev();

				if (this.pointer() <= 0) {
					//this.pointer = 0;
					this.$(".nav-prev-action").addClass("btn-disabled");
				}
				this.render();
			},
			nextItem : function(e) {
				console.info('portlet.content/navigationViewClass::nextItem');
				//this.pointer++;

				e.preventDefault();

				this.collection().next();

				if (this.pointer() >= this.collection().size()) {
					//this.pointer = this.collection.size() - 1;
					this.$(".nav-next-action").addClass("btn-disabled");
				}
				this.render();
			},
		});

		var baseChangeModelViewClass = Backbone.View.extend({
			//portlet: $('#courses-widget'),
			setModel : function(model) {
				this.model = model;
				this.render();
			}
		});

		var baseChildTabViewClass = baseChangeModelViewClass.extend({
			nofoundTemplate : _.template($("#tab_all_child-nofound-template").html()),
			initialize: function(opt) {
				console.info('portlet.content/baseChildTabViewClass::initialize');

				//this.listenTo(this.model, 'sync', this.render.bind(this));
				this.render();
			},
			render : function(e) {
				console.info('portlet.content/baseChildTabViewClass::render');

				this.collection = this.makeCollection();

				this.$el.empty();

				if (this.collection.size() === 0) {
					this.$el.append(this.nofoundTemplate());
					this.disableView();
				} else {
					this.enableView();
					var self = this;
					this.collection.each(function(model, i) {
						var childView = new self.childViewClass({model : model});
						self.$el.append(childView.render().el);
					});
				}
				app.module("ui").refresh(this.$el);
			},
			disableView : function() {

			},
			enableView : function() {
				
			}
		});

		var baseChildTabViewItemClass = Backbone.View.extend({
			tagName : "tr",
			//template : _.template($("#tab_class_lessons-item-template").html(), null, {variable: "model"}),
			initialize : function() {
				this.listenTo(mod.progressCollection, "sync", this.checkProgress.bind(this));
			},
			render : function(e) {
				console.info('portlet.content/baseChildTabViewItemClass::render');
				this.$el.html(
					this.template(this.model.toJSON())
				);
				return this;
			}
		});

		/* COURSE TABS VIEW CLASSES */
		var blockableTabViewClass = baseChangeModelViewClass.extend({
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
		/*
		var programTabViewClass = blockableTabViewClass.extend({
			initialize: function(opt) {
				console.info('portlet.content/programTabViewClass::initialize');

				// TODO CREATE SUB VIEWS!!
				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-lesson"),
					collection : mod.programsCollection.getCurrentPrograms.bind(mod.programsCollection),
					pointer : mod.programsCollection.getProgramIndex.bind(mod.programsCollection)
				});
				this.navigationView.render();

				//this.listenTo(this.model, 'change:id', this.updateCollectionIndex.bind(this));
				//this.listenTo(this.collection, 'sync', this.updateCollectionIndex.bind(this));

				//this.listenTo(this.model, 'sync', this.render.bind(this));

				// TODO CREATE SUB VIEWS!!
				this.programDescriptionTabView 	= new programDescriptionTabViewClass({
					el : this.$("#tab_program_description"),
					model : this.model
				});

				//this.programMoreinfoTabView 	= new programMoreinfoTabViewClass({
				//	el : this.$("#tab_course_moreinfo"),
				//	model : this.model
				//});
				

				//this.programCoordinatorTabView 	= new programCoordinatorTabViewClass({
				//	el : this.$("#tab_program_coordinator"),
				//	model : this.model
				//});
				
				this.programCoursesTabView = new programCoursesTabViewClass({
					el : this.$("#tab_program_courses table tbody"),
					model : this.model
				//});
				this.blockUi('No Course Selected');

				//this.updateCollectionIndex();
			},
			render : function(e) {
				console.info('portlet.content/programTabViewClass::render');
				this.unBlockUi();
				this.$(".program-title").html(this.model.get("name"));

				var factor = this.model.get("progress.factor");
				if (factor >= 1) {
					this.$(".viewed-status").removeClass("hidden");
				} else {
					this.$(".viewed-status").addClass("hidden");
				}

			},
			onBlockableItemClick : function(e) {
				// APLLY USER TO ENROLLMENT PROCESS
            	app.module("utils.toastr").message("warning", "The system will send you to the enroll page");

			}
		});
		*/
		
		var programDescriptionTabViewClass = Backbone.View.extend({
			template : _.template($("#tab_program_description-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.content/programDescriptionTabViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
				this.render();
			},
			render : function(e) {
				console.info('portlet.content/programDescriptionTabViewClass::render');
				this.$el.empty().append(this.template(this.model.toJSON()));
			}
		});
		/*
		var programCoordinatorTabViewClass = Backbone.View.extend({
			template : _.template($("#tab_program_coordinator-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.content/programCoordinatorTabViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
				this.render();
			},
			render : function(e) {
				console.info('portlet.content/programCoordinatorTabViewClass::render');
				this.$(".scroller").empty();

				if (_.size(this.model.get("coordinator")) === 0) {
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
		*/
		var programCoursesTabViewItemClass = baseChildTabViewItemClass.extend({
			events : {
				"click .class-change-action" : "setClassId"
			},
			template : _.template($("#tab_program_courses-item-template").html(), null, {variable: "model"}),
			setClassId : function(e) {
				//app.userSettings.set("class_id", this.model.get("id"));
				mod.programsCollection.moveToCourse(this.model.get("id"));

				$("[href='#course-tab']").tab('show');
			},
			checkProgress : function(model) {
				var progress = _.findWhere(model.get("courses"), {class_id : this.model.get("id")});
				if (!_.isUndefined(progress)) {
					this.model.set("progress", progress);
					this.render();
				}
			}
		})

		var programCoursesTabViewClass = baseChildTabViewClass.extend({
			nofoundTemplate : _.template($("#tab_program_courses-nofound-template").html()),

			childViewClass : programCoursesTabViewItemClass,
			makeCollection: function() {
				return mod.programsCollection.getCurrentCourses();
			}
		});

		/*
		var programMoreinfoTabViewClass = Backbone.View.extend({
			template : _.template($("#tab_course_moreinfo-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.content/programMoreinfoTabViewClass::initialize');
				this.listenTo(this.model, 'sync', this.render.bind(this));
			},
			render : function(e) {
				console.info('portlet.content/programMoreinfoTabViewClass::render');
				this.$(".scroller").empty().append(this.template(this.model.toJSON()));
			}
		});
		var courseRoadmapTabViewClass = Backbone.View.extend({
			portlet: $('#courses-widget'),
			seasonsSynced	: false,
			classesSynced	: false,

			initialize: function(opt) {
				console.info('portlet.content/courseRoadmapTabViewClass::initialize');

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
				console.info('portlet.content/courseRoadmapTabViewClass::render');
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
				console.info('portlet.content/courseRoadmapTabSeasonViewClass::render');
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

		/*
		var courseTabViewClass = blockableTabViewClass.extend({
			initialize: function(opt) {
				console.info('portlet.content/classTabViewClass::initialize');

				// TODO CREATE SUB VIEWS!!
				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-lesson"),
					collection : mod.programsCollection.getCurrentCourses.bind(mod.programsCollection),
					pointer : mod.programsCollection.getCourseIndex.bind(mod.programsCollection)
				});
				this.listenTo(mod.programsCollection, "course.changed", this.setModel.bind(this));

				this.listenTo(mod.progressCollection, "sync", this.checkProgress.bind(this));


				this.courseInfoTabView = new courseInfoTabViewClass({
					el : this.$("#tab_course_info"),
					model : this.model
				});

				this.courseInstructorTabView = new courseInstructorTabViewClass({
					el : this.$("#tab_course_instructor"),
					model : this.model
				});

				this.courseUnitsTabView = new courseUnitsTabViewClass({
					el : this.$("#tab_course_units table tbody"),
					model : this.model
				});

				this.blockUi('No Course Selected');

				//this.updateCollectionIndex();
			},
			checkProgress : function(model) {
				var progress = _.findWhere(model.get("courses"), {class_id : this.model.get("id")});
				var factor = null;
				if (!_.isUndefined(progress)) {
					factor = progress.factor;
				} else {
					factor = this.model.get("progress.factor");
				}

				if (factor == 1) {
					this.$(".viewed-status").removeClass("hidden");
				} else {
					this.$(".viewed-status").addClass("hidden");
				}
			},
			render : function() {
				console.info('portlet.content/courseTabViewClass::render');

				this.navigationView.render();
				this.courseInfoTabView.render();
				this.courseInstructorTabView.render();
				this.courseUnitsTabView.render();

				this.checkProgress(mod.progressCollection);

				this.unBlockUi();
			},
			focus : function() {
				$("[href='#course-tab']").tab('show');
			},
			onBlockableItemClick : function(e) {
				$("[href='#course-tab']").click();
				$("[href='#tab_course_units']").click();
			}
		});
		*/
		/*
		var courseInfoTabViewClass = baseChangeModelViewClass.extend({
			//portlet: $('#courses-widget'),
			template : _.template($("#tab_courses_info-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.content/classInfoTabViewClass::initialize');
				this.listenTo(mod.programsCollection, "course.changed", this.setModel.bind(this));
			},
			render : function(e) {
				console.info('portlet.content/classInfoTabViewClass::render');
				this.$(".scroller").empty().append(this.template(this.model.toJSON()));
			}
		});
		var courseInstructorTabViewClass = baseChangeModelViewClass.extend({
			//portlet: $('#courses-widget'),
			template : _.template($("#tab_courses_instructor-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.content/classInstructorTabViewClass::initialize');
				this.listenTo(mod.programsCollection, "course.changed", this.setModel.bind(this));
			},
			render : function(e) {
				console.info('portlet.content/classInstructorTabViewClass::render');
				this.$(".scroller").empty();

				if (_.size(this.model.get("classe.instructor")) === 0) {
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
		*/

		var courseUnitsTabViewItemClass = baseChildTabViewItemClass.extend({
			events : {
				"click .lesson-change-action" : "setLessonId",
				"click .view-test-action" : "openDialog",
				"click .open-test-action" : "doTest"
			},
			testInfoModule : app.module("dialogs.tests.info"),
			lessonTemplate : _.template($("#tab_courses_units-item-template").html(), null, {variable: "model"}),
            testTemplate : _.template($("#tab_courses_tests-item-template").html(), null, {variable: "model"}),
			setLessonId : function(e) {
				//app.userSettings.set("lesson_id", this.model.get("id"));
				//app.userSettings.set("class_id", this.model.get("id"));
				mod.programsCollection.moveToUnit(this.model.get("id"));

				$("[href='#unit-tab']").tab('show');
			},
			getMappedModel : function() {

			},
			render : function(e) {
				console.info('portlet.content/courseUnitsTabViewItemClass::render');



				if (this.model.get("type") == "lesson") {
					this.$el.html(
						this.lessonTemplate(this.getMappedModel())
					);
				} else {
					this.$el.html(
						this.testTemplate(this.getMappedModel())
					);
				}
				return this;
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
			checkProgress : function(model) {
				var progress = _.findWhere(model.get("units"), {lesson_id : this.model.get("id")});
				if (!_.isUndefined(progress)) {
					this.model.set("progress", progress);
					this.render();
				}

			},

            doTest : function(model) {
            	//app.module("utils.toastr").message("info", "Test not available yet!");
            	//alert("Doing Test " + this.model.get("id"));
            	// START TEST EXECUTION this.model
            }
		});

		var courseUnitsTabViewClass = baseChildTabViewClass.extend({
			nofoundTemplate : _.template($("#tab_courses_child-nofound-template").html()),
			childViewClass : courseUnitsTabViewItemClass,
			initialize: function() {
				console.info('portlet.content/classInfoTabViewClass::initialize');

				baseChildTabViewClass.prototype.initialize.apply(this, arguments);

				this.listenTo(mod.programsCollection, "course.changed", this.setModel.bind(this));


			},
			makeCollection: function() {
				return mod.programsCollection.getCurrentUnits();
			}
		});
		/*
		var courseTestsTabViewItemClass = baseChildTabViewItemClass.extend({
			events : {
				"click .lesson-change-action" : "setLessonId",
				"click .view-test-action" : "openDialog",
				"click .open-test-action" : "doTest"
			},
			testInfoModule : app.module("dialogs.tests.info"),
			template : _.template($("#tab_courses_tests-item-template").html(), null, {variable: "model"}),
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
            	//app.module("utils.toastr").message("info", "Test not available yet!");
            	//alert("Doing Test " + this.model.get("id"));
            	// START TEST EXECUTION this.model
            }
		});
		var courseTestsTabViewClass = baseChildTabViewClass.extend({
			nofoundTemplate : _.template($("#tab_courses_child-nofound-template").html()),
			childViewClass : courseTestsTabViewItemClass,
			makeCollection: function() {
				return mod.programsCollection.getCurrentUnits();
			}
		});
		*/
		

		/* LESSONS / TESTS VIEW CLASSES */
		/*
		var unitTabViewClass = blockableTabViewClass.extend({
			initialize: function() {
				console.info('portlet.content/unitTabViewClass::initialize');

				// TODO CREATE SUB VIEWS!!
				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-lesson"),
					collection : mod.programsCollection.getCurrentUnits.bind(mod.programsCollection),
					pointer : mod.programsCollection.getUnitIndex.bind(mod.programsCollection)
				});

				this.listenTo(mod.programsCollection, "unit.changed", this.setModel.bind(this));

				// TODO CREATE SUB VIEWS!!
				this.unitVideoTabView 	= new unitVideoTabViewClass({
					el : this.$("#tab_unit_video"),
					model : this.model,
                	portlet : this.$el
				});

				this.listenTo(this.unitVideoTabView, "video:viewed", this.setViewed.bind(this));

				this.unitMaterialsTabView 	= new unitMaterialsTabViewClass({
					el : this.$("#tab_unit_materials table tbody"),
					model : this.model,
                	portlet : this.$el
				});

				this.unitTestTabView 	= new unitTestTabViewClass({
					el : this.$("#tab_unit_tests"),
					model : this.model,
                	portlet : this.$el
				});


				this.listenTo(mod.progressCollection, "sync", this.checkProgress.bind(this));


				this.blockUi('No Unit Selected');
			},
			checkProgress : function(model) {
				var progress = _.findWhere(model.get("units"), {lesson_id : this.model.get("id")});
				if (!_.isUndefined(progress)) {
					if (progress.factor == 1) {
						this.$(".viewed-status").removeClass("hidden");
					} else {
						this.$(".viewed-status").addClass("hidden");
					}
				} else {
					this.$(".viewed-status").addClass("hidden");
				}
			},
			render : function(e) {
				console.info('portlet.content/unitTabViewClass::render');
				this.unBlockUi();

				this.navigationView.render();
				this.unitVideoTabView.render();
				this.unitMaterialsTabView.render();
				this.unitTestTabView.render();
				
				var factor = this.model.get("progress.factor");

				if (factor >= 1) {
					this.$(".viewed-status").removeClass("hidden");
				} else {
					this.$(".viewed-status").addClass("hidden");
				}

			},
			focus : function() {
				$("[href='#unit-tab']").tab('show');
			},
			setViewed : function() {
				
			},
			onBlockableItemClick : function(e) {
				$("[href='#class-tab']").click();
				$("[href='#tab_class_lessons']").click();

			}
		});
		*/
		/*
		var unitVideoTabViewClass = baseChangeModelViewClass.extend({
			videoJS : null,
			nofoundTemplate : _.template($("#tab_unit_video-nofound-template").html()),
			template : _.template($("#tab_unit_video-item-template").html(), null, {variable: "model"}).bind(this),
			initialize: function(opt) {
				console.info('portlet.content/unitVideosTabViewClass::initialize');

				this.listenTo(mod.programsCollection, "unit.changed", this.setModel.bind(this));
			},
			render : function(e) {
				console.info('portlet.content/unitVideosTabViewClass::render');
				var self = this;

				var videos = mod.programsCollection.getCurrentContents('video');

				if (_.size(videos) == 0) {
					// THERE'S NO VIDEO LESSON... DISABLE THE VIEW
					this.disableView();
				} else {
					this.enableView();
					this.videoModel = mod.programsCollection.getCurrentContents().getMainVideo(videos.at(0));

					if (!_.isNull(this.videoJS)) {
						this.videoJS.dispose();
					}

					if (this.videoModel) {
						var videoDomID = "unit-video-" + this.videoModel.get("id");

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
					}

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
				$("[href='#tab_unit_video'").hide();
				$("[href='#tab_unit_materials']").tab('show');
				//this.$el.hide();
			},
			enableView : function() {
				$("[href='#tab_unit_video'").show().tab('show');
				//this.$el.show();
			}
		});
		*/
		/*
		var unitVideosTabViewItemClass = Backbone.View.extend({
			tagName : "tr",
			//template : _.template($("#tab_units_videos-item-template").html(), null, {variable: "model"}),

			render : function(e) {
				console.info('portlet.content/unitVideosTabViewItemClass::render');
				this.$el.html(
					this.template(this.model.toJSON())
				);
				return this;
			}
		});
		*/
		/*
		var unitTestTabViewClass = baseChangeModelViewClass.extend({
			nofoundTemplate : _.template($("#tab_unit_video-nofound-template").html()),
			template : _.template($("#tab_unit_video-item-template").html(), null, {variable: "model"}).bind(this),
			initialize: function(opt) {
				console.info('portlet.content/unitTestTabViewClass::initialize');

				this.listenTo(mod.programsCollection, "unit.changed", this.setModel.bind(this));
			},
			render : function(e) {
				console.info('portlet.content/unitTestTabViewClass::render');
				if (this.model.get("type") == "test") {
					this.enableView();
                	this.$el.load("/module/tests/open/" + this.model.get("id") + "?dialog");
	                app.module("ui").refresh(this.$el);
				} else {
					this.disableView();
				}
			},
			disableView : function() {
				$("[href='#tab_unit_tests'],#tab_unit_tests").addClass("hidden");
			},
			enableView : function() {
				$("[href='#tab_unit_tests'],#tab_unit_tests").removeClass("hidden");
				$("[href='#tab_unit_tests']").tab('show');
			}
		});
		*/
		/*
		var overallProgressViewClass = Backbone.View.extend({
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
						lineCap : 'butt',
						barColor: App.getLayoutColorCode('green')
					});
					this.$(".class").easyPieChart({
						animate: 1000,
						size: 75,
						lineWidth: 3,
						lineCap : 'butt',
						barColor: App.getLayoutColorCode('yellow')
					});
					this.$(".course").easyPieChart({
						animate: 1000,
						size: 75,
						lineWidth: 3,
						lineCap : 'butt',
						barColor: App.getLayoutColorCode('red')
					});


					//this.renderCourse();
					//this.renderSemester();
					//this.renderClass();
					//this.renderLesson();
				}
			},
			renderCourse : function(factor) {
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				//percent = 30;
				this.$(".course span").html(
					app.module("views").formatValue(
						factor,
						'decimal-custom',
						'0.[0]%'
					)
				);

				if (jQuery.fn.easyPieChart) {
					var percent = factor * 100;

					if (_.isObject(this.$(".course").data('easyPieChart'))) {
						this.$(".course").data('easyPieChart').update(percent);
					}
				}
			},
			renderSemester : function() {
			},

			renderClass : function(factor) {
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				this.$(".class span").html(
					app.module("views").formatValue(
						factor,
						'decimal-custom',
						'0.[0]%'
					)
				);

				if (jQuery.fn.easyPieChart) {
					var percent = factor * 100;

					if (_.isObject(this.$(".class").data('easyPieChart'))) {
						this.$(".class").data('easyPieChart').update(percent);
					}
				}
			},
			renderLesson : function(factor) {
				// INJECT HERE PARTIAL PROGRESS FROM LESSONS
				this.$(".lesson span").html(app.module("views").formatValue(
						factor,
						'decimal-custom',
						'0.[0]%'
				));

				if (jQuery.fn.easyPieChart) {
					var percent = factor * 100;

					if (_.isObject(this.$(".lesson").data('easyPieChart'))) {
						this.$(".lesson").data('easyPieChart').update(percent);
					}
				}
			}
		});
		*/

		this.widgetViewClass = parent.widgetViewClass.extend({
			programTabView : null,
			courseTabView : null,
			unitTabView : null,
			start : function() {
				console.info('portlet.content/widgetViewClass::start');
				Marionette.triggerMethodOn(this, "beforeStart");
				// CREATE SUB-VIEWS AND FETCH MODELS AND COLLECTIONS

				this.$(".portlet-title").height(26);

				this.startViews();
				//this.startCourseView();
				//this.startUnitView();

				Marionette.triggerMethodOn(this, "start");

				this.listenTo(this.collection, "program.changed", this.renderProgram.bind(this));
				this.listenTo(this.collection, "course.changed", this.renderCourse.bind(this));
				this.listenTo(this.collection, "unit.changed", this.renderUnit.bind(this));

				this.renderProgram();
				this.renderCourse();
				this.renderUnit();
			},
			renderProgram : function() {
				console.info('portlet.content/widgetViewClass::render');
				this.$(".program-title").html(this.collection.getCurrentProgram().get("name"));
				this.$(".program-count").html(this.collection.getCurrentPrograms().size());
			},
			renderCourse : function() {
				this.$(".course-title").html(this.collection.getCurrentCourse().get("name"));
				this.$(".course-count").html(this.collection.getCurrentCourses().size());
			},
			renderUnit : function() {
				this.$(".unit-title").html(this.collection.getCurrentUnit().get("name"));
				this.$(".unit-count").html(this.collection.getCurrentUnits().size());
			},			
			startOverallProgress : function() {
				//this.overallProgressView = new overallProgressViewClass();
			},
			startViews : function() {
				console.info('portlet.content/widgetViewClass::startProgramView');

				if (_.isNull(this.programTabView)) {
					/*
					this.programTabView = new programTabViewClass({
						model : this.collection.getCurrentProgram(),
						//collection : this.collection,
						el : this.$("#program-tab"),
            			portlet : this.$el
					});
					*/

					this.programDescriptionTabView 	= new programDescriptionTabViewClass({
						el : $("#tab_program_description"),
						model : this.collection.getCurrentProgram()
					});

					this.programCoursesTabView = new programCoursesTabViewClass({
						el : $("#tab_program_courses table tbody"),
						model : this.collection.getCurrentProgram()
					});

					this.courseUnitsTabView = new courseUnitsTabViewClass({
						el : this.$("#tab_course_units table tbody"),
						model : this.model
					});

					this.programDescriptionTabView.render();
					this.programCoursesTabView.render();
					this.courseUnitsTabView.render();

					//this.programTabView.render();
				}
			},
			/*
			startCourseView : function() {
				console.info('portlet.content/widgetViewClass::startCourseView');

				if (_.isNull(this.courseTabView)) {
					this.courseTabView = new courseTabViewClass({
						model : this.collection.getCurrentCourse(),
						el : this.$("#course-tab"),
            			portlet : this.$el
					});
					this.courseTabView.render();
				}
			},
			startUnitView : function() {
				console.info('portlet.content/widgetViewClass::startUnitView');

				if (_.isNull(this.unitTabView)) {
					this.unitTabView = new unitTabViewClass({
						model : this.collection.getCurrentUnit(),
						//collection : this.collection,
						el : this.$("#unit-tab"),
            			portlet : this.$el
					});
					this.unitTabView.render();
				}
			}
			*/
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
	var baseModel = app.module("models").getBaseModel();

	this.models = {
		program : baseModel.extend({}),
		course : baseModel.extend({}),
		unit : baseModel.extend({}),
		content : baseModel.extend({
			isVideo : function() {
				return /^video\/.*$/.test(this.get("file.type"));
			},
			isRemoteVideo : function() {
				return /\.mp4$/.test(this.get("content"));
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
			isSubtitle : function() {
				return this.get("content_type") == "subtitle" || this.get("content_type") == "subtitle-translation";
			},
			isMaterial : function() {
				return !this.isVideo() && !this.isSubtitle() && !this.isAudio() && !this.isImage();

			}
		}),
		content_progress : baseModel.extend({
			response_type : "silent",
			urlRoot : "/module/roadmap/item/content-progress",
			setAsViewed : function(model, factor) {
				if (_.isUndefined(factor)) {
					factor = 1;
				}
				this.set("content_id", model.get("id"));
				this.set("factor", factor);
				this.save();

				if (factor == 1) {
					mod.progressCollection.fetch();
				}

				model.set("progress", this.toJSON());
			}
		}),
		progress : Backbone.Model.extend({
			url : "/module/content/datasource/progress"
		})	
	};

	var navigableCollection = Backbone.Collection.extend({
		prev : function() {
			this.trigger("previous");
		},
		next : function() {
			this.trigger("next");
		}
	});

	this.collections = {
		programs : navigableCollection.extend({
			current : {},
			model : this.models.program,
			programs : null,
			courses : null,
			units : null,
			contents : null,
			url : "/module/roadmap/datasources/courses",
			initialize : function(opt) {
				this.current = opt.current;

				//this.listenTo(this, "reset", this.flatenTree.bind(this));
				//this.listenTo(this, "course.changed", this.recalculateUnitIndex.bind(this));
			},
			updateCourseIndex : function() {
				// DESTROY DEPENDENT COLLECTIONS
				this.units = null;
				this.contents = null;

				// CREATE NEW ONES
				
				// UPDATE THE SERVER TO RECEIVE NEW VARS
				$.ajax(
					"/module/content/set-pointer",
					{
						async : false,
						method : 'POST',
						data : {
							scope : 'course',
							entity_id : this.current.course_id
						},
						dataType : 'json',
						success : function(data, textStatus, jqXHR) {
							this.current = data;

							this.trigger("unit.changed", this.getCurrentUnit());
						}.bind(this)
					}
				);
			},
			updateUnitIndex : function() {
				// DESTROY DEPENDENT COLLECTIONS
				this.contents = null;

				// CREATE NEW ONES
				
				// UPDATE THE SERVER TO RECEIVE NEW VARS

				$.ajax(
					"/module/content/set-pointer",
					{
						async : false,
						method : 'POST',
						data : {
							scope : 'unit',
							entity_id : this.current.unit_id
						},
						dataType : 'json',
						success : function(data, textStatus, jqXHR) {
							this.current = data;

							this.trigger("content.changed", this.getCurrentContent());
						}.bind(this)
					}
				);
			},

			// MODELS
			getCurrentProgram : function() {
				var program = this.findWhere({id : this.current.program_id});
				return program;
			},
			getCurrentCourse : function() {
				if (_.isNull(this.courses)) {
					this.courses = this.getCurrentCourses();
				}
				var course = this.courses.findWhere({id : this.current.course_id});
				return course;
			},
			getCurrentUnit : function() {
				if (_.isNull(this.units)) {
					this.units = this.getCurrentUnits();
				}

				var unit = this.units.findWhere({id : this.current.unit_id});
				return unit;
			},
			getCurrentContent : function() {
				if (_.isNull(this.contents)) {
					this.contents = this.getCurrentContents();
				}

				var unit = this.contents.findWhere({id : this.current.content_id});
				return unit;
			},

			// INDEXES
			getProgramIndex : function() {
				return this.indexOf(
  					this.getCurrentProgram()
				);
			},
			getCourseIndex : function() {
				return this.getCurrentCourses().indexOf(
  					this.getCurrentCourse()
				);
			},
			getUnitIndex : function() {
				return this.getCurrentUnits().indexOf(
  					this.getCurrentUnit()
				);
			},
			moveToCourse : function(course_id) {
				var model = this.courses.findWhere({id : course_id});
				var courseIndex = this.getCurrentCourses().indexOf(model);
				if (courseIndex >= 0) {
					// CALCULATE NEW POINTERS
					this.current.course_id = course_id;
					this.updateCourseIndex();

					this.trigger("course.changed", model, courseIndex);

				}
			},
			toPreviousCourseIndex : function() {
				var courseIndex = this.getCourseIndex();
				if (courseIndex <= 0) {
					return false;
				}
				courseIndex--;
				var model = this.getCurrentCourses().at(courseIndex);
				if (!_.isUndefined(model)) {
					this.moveToCourse(model.get("id"));
				}

			},
			toNextCourseIndex : function() {
				var courseIndex = this.getCourseIndex();
				if (courseIndex >= this.getCurrentCourses().size()) {
					return false;
				}
				courseIndex++;
				var model = this.getCurrentCourses().at(courseIndex);
				if (!_.isUndefined(model)) {
					this.moveToCourse(model.get("id"));
				}
			},
			moveToUnit : function(unit_id) {
				var model = this.units.findWhere({id : unit_id});
				var unitIndex = this.getCurrentUnits().indexOf(model);
				if (unitIndex >= 0) {
					this.current.unit_id = unit_id;
					this.updateUnitIndex();

					unitIndex = this.getCurrentUnits().indexOf(model);

					this.trigger("unit.changed", model, unitIndex);
				}
			},
			toPreviousUnitIndex : function() {
				var unitIndex = this.getUnitIndex();
				if (unitIndex <= 0) {
					return false;
				}
				unitIndex--;
				var model = this.getCurrentUnits().at(unitIndex);
				if (!_.isUndefined(model)) {
					this.moveToUnit(model.get("id"));

					//this.current.unit_id = model.get("id");
					//this.trigger("unit.changed", model, unitIndex);
				}

			},
			toNextUnitIndex : function() {
				var unitIndex = this.getUnitIndex();
				if (unitIndex >= this.getCurrentUnits().size()) {
					return false;
				}
				unitIndex++;
				var model = this.getCurrentUnits().at(unitIndex);
				if (!_.isUndefined(model)) {
					this.moveToUnit(model.get("id"));

					//this.current.unit_id = model.get("id");
					//this.trigger("unit.changed", model, unitIndex);
				}
			},
			// COLLECTIONS
			getCurrentPrograms : function() {
				return this;
			},
			getCurrentCourses : function() {
				if (_.isNull(this.courses)) {
					var program = this.getCurrentProgram();
					this.courses = new mod.collections.courses(program.get("courses"));

					this.listenTo(this.courses, "previous", this.toPreviousCourseIndex.bind(this));
					this.listenTo(this.courses, "next", this.toNextCourseIndex.bind(this));
				}
				
				return this.courses;
			},
			getCurrentUnits : function() {
				if (_.isNull(this.units)) {
					var course = this.getCurrentCourse();
					this.units = new mod.collections.units(course.get("units"));

					this.listenTo(this.units, "previous", this.toPreviousUnitIndex.bind(this));
					this.listenTo(this.units, "next", this.toNextUnitIndex.bind(this));
				}

				return this.units;
			},
			getCurrentContents : function(type) {
				if (_.isNull(this.contents)) {
					var unit = this.getCurrentUnit();
					this.contents = new mod.collections.contents(unit.get("contents"));
				}

				if (type == "video") {
					var data = this.contents.filter(function(model, index) {
						return model.isVideo();
					});

					return new mod.collections.contents(data);

				} else 	if (type == "material") {
					var data = this.contents.filter(function(model, index) {
						return model.isMaterial();
					});
					return new mod.collections.contents(data);
				}
				return this.contents;
			}
		}),
		courses : navigableCollection.extend({
			model : this.models.course
		}),
		units : navigableCollection.extend({
			model : this.models.unit,
		}),
		contents : navigableCollection.extend({
			model : this.models.content,
			getMainVideo : function(videoModel) {
				var mainVideo = null;				
				if (_.isUndefined(videoModel)) {
					var filteredCollection = this.where({
						content_type : "file"
					});

					var filteredVideoCollection = _.filter(filteredCollection, function(model, index) {
						return model.isVideo();
					});

					if (_.size(filteredVideoCollection) === 0) {
						filteredCollection = this.where({
							content_type : "url"
						});
						filteredVideoCollection = _.filter(filteredCollection, function(model, index) {
							return model.isRemoteVideo();
						});
						if (_.size(filteredVideoCollection) === 0) {
							return false;
						}
					}

					var mainVideo = _.findWhere(filteredVideoCollection, {main : "1"});

					if (_.size(mainVideo) === 0) {
						mainVideo = _.first(filteredVideoCollection);
					}
				} else {
					mainVideo = videoModel;
				}
				

				// GET CHILDS OBJECTS
				var poster = _.map(
					this.where({
						parent_id : mainVideo.get("id"),
						content_type : "poster"
					}),
					function(model, index) {
						return model.toJSON();
					}
				);

				if (_.size(poster) > 0) {
					mainVideo.set("poster", _.first(poster));
				}

				// GET CHILDS OBJECTS
				var childs = _.map(
					this.where({
						parent_id : mainVideo.get("id"),
						content_type : "subtitle"
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
			}
		})
		
		/*
		courses : navigableCollection.extend({
			//model : fullCourseModelClass,
			url : "/module/roadmap/datasources/courses",
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
					filteredCollection = this.where({
						content_type : "url"
					});
					filteredVideoCollection = _.filter(filteredCollection, function(model, index) {
						return model.isRemoteVideo();
					});
					if (_.size(filteredVideoCollection) === 0) {
						return false;
					}
				}

				var mainVideo = _.findWhere(filteredVideoCollection, {main : "1"});

				if (_.size(mainVideo) === 0) {
					mainVideo = _.first(filteredVideoCollection);
				}

				// GET CHILDS OBJECTS
				var poster = _.map(
					this.where({
						parent_id : mainVideo.get("id"),
						content_type : "poster"
					}),
					function(model, index) {
						return model.toJSON();
					}
				);

				if (_.size(poster) > 0) {

					mainVideo.set("poster", _.first(poster));
				}

				// GET CHILDS OBJECTS
				var childs = _.map(
					this.where({
						parent_id : mainVideo.get("id"),
						content_type : "subtitle"
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
		*/
	};
	mod.programsCollection = null;

	mod.on("start", function() {
		//var userSettingsModel = new userSettingsModelClass();
		//
		var contentInfo = $SC.getResource("content_widget_data");

		mod.programsCollection = new this.collections.programs({
			current : contentInfo.current
		});

		mod.programsCollection.reset(contentInfo.tree);

		mod.progressCollection = new this.models.progress();


		app.trigger("progress.started");

		//mod.progressCollection.fetch();

		//for(var i in contentCollection)

		//this.listenToOnce(app.userSettings, "sync", function(model, data, options) {

			this.widgetView = new this.widgetViewClass({
				model : app.userSettings,
				collection : mod.programsCollection,
				el: '#content-widget'
			});

			//this.courseWidgetView.start();

		//}.bind(this));

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
	/*
	var fullCourseModelClass = Backbone.DeepModel.extend({
		urlRoot : "/module/roadmap/item/courses"
	});

	var fullClassModelClass = Backbone.DeepModel.extend({
		urlRoot : "/module/roadmap/item/course-classes"
	});
	var fullLessonModelClass = Backbone.DeepModel.extend({
		urlRoot : "/module/roadmap/item/lessons"
	});
	var contentModelClass = Backbone.DeepModel.extend({
		isVideo : function() {
			return /^video\/.*$/.test(this.get("file.type"));
		},
		isRemoteVideo : function() {
			return /\.mp4$/.test(this.get("content"));
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
	*/

});
