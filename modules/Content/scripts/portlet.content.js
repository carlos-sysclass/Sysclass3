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

		var programCoursesTabViewItemClass = baseChildTabViewItemClass.extend({
			events : {
				"click .class-change-action" : "setClassId"
			},
			template : _.template($("#tab_program_courses-item-template").html(), null, {variable: "model"}),
			setClassId : function(e) {
				//app.userSettings.set("class_id", this.model.get("id"));
				mod.programsCollection.moveToCourse(this.model.get("id"));

				$("[href='#tab_course_units']").tab('show');
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

		/* CLASSES TABS VIEW CLASSES */
		var courseUnitsTabViewItemClass = baseChildTabViewItemClass.extend({
			events : {
				"click .unit-change-action" : "setLessonId",
				"click .view-test-action" : "openDialog",
				"click .open-test-action" : "doTest"
			},
			testInfoModule : app.module("dialogs.tests.info"),
			dialogContentUnit : app.module("dialogs.content.unit"),
			lessonTemplate : _.template($("#tab_courses_units-item-template").html(), null, {variable: "model"}),
            testTemplate : _.template($("#tab_courses_tests-item-template").html(), null, {variable: "model"}),
			setLessonId : function(e) {
				//app.userSettings.set("lesson_id", this.model.get("id"));
				//app.userSettings.set("class_id", this.model.get("id"));
				mod.programsCollection.moveToUnit(this.model.get("id"));

                if (!this.dialogContentUnit.started) {
                    this.dialogContentUnit.start({
                        modelClass : mod.models.unit,
                    });
                }

                this.model.getCourse();

                this.dialogContentUnit.setInfo({
                	model: this.model,
                	collection : mod.programsCollection
                }).open();



				//$("[href='#unit-tab']").tab('show');
			},
			getMappedModel : function() {
				var result = this.model.toJSON();

				var video = this.model.getVideo();

				if (video) {
					result.video = video.toJSON();	
				} else {
					result.video = false;
				}

				result.materials = this.model.getMaterials().toJSON();

				return result;
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
            	//app.module("utils.toastr").message("info", "Test execution not disponible yet!");
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
		unit : baseModel.extend({
			contents : {},
			getCourse : function(asJSON) {
				//if (this.get('course')) {
				//	return this.get('course');
				//}
				var course = mod.programsCollection.getCurrentCourses().findWhere({id : this.get("class_id")})
				if (asJSON) {
					this.set('course', course.toJSON());
				} else {
					this.set('course', course);	
				}

				return this.get('course');
			},
			getVideo : function() {
				if (this.get('video')) {
					return this.get('video');
				}
				
				var contents = new mod.collections.contents(this.get("contents"));

				var videos = contents.filter(function(model, index) {
					return model.isVideo();
				});

				this.set('video', contents.getMainVideo(_.first(videos)));

				return this.get('video');
			},
			getMaterials : function() {
				if (this.get('materials')) {
					return this.get('materials');
				}

				var filteredCollection = _.filter(this.get("contents"), function(model, index) {
					var object = new mod.models.content(model);
					return object.isMaterial();
				});

				this.set('materials', new mod.collections.contents(filteredCollection));

				return this.get('materials');
			},
			getExercises : function() {
				if (_.has(this.contents, 'exercises')) {
					return this.contents['exercises'];
				}

				console.warn(this.get("contents"), this.contents);

				var filteredCollection = _.filter(this.get("contents"), function(model, index) {
					var object = new mod.models.content(model);
					return object.isExercise();
				});

				if (!_.isNull(this.contents)) {
					//delete this.contents;
				}
				this.contents['exercises'] = new mod.collections.contents(filteredCollection);

				return this.contents['exercises'];
			},
		}),
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
			isExercise : function() {
				return this.get("content_type") == "exercise";
			},
			isMaterial : function() {
				return !this.isVideo() && !this.isSubtitle() && !this.isAudio() && !this.isImage() & !this.isExercise();
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
			},
			getMaterials : function() {

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
