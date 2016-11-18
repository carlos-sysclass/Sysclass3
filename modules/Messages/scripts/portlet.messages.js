$SC.module("portlet.messages", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	var baseModel = app.module("models").getBaseModel();

	mod.models = {
		messages : {
			message : baseModel.extend({
				response_type : "object",
				//idAttribute : "user_id",
				urlRoot : "/module/enroll/messages/me"
				/*
				urlRoot : function() {
					return "/module/enroll/item/users/" + this.get("role_id")
				} 
				*/
			})
		}
	};

	mod.on("start", function() {
		var parent = app.module("portlet");
		// TODO THINK ABOUT MOVE THIS CLASS INTO THE MAIN VIEW
		var baseClass = app.module("views").baseClass;
		var baseChildTabViewClass = app.module("views").baseChildTabViewClass;
		//var baseChildTabViewItemClass = app.module("views").baseChildTabViewItemClass;

		var tableViewClass = $SC.module("utils.datatables").tableViewClass;

		var messagesBodyViewClass = baseClass.extend({
			events : {
				"click button.reply-action" : "replyTo",
				"click button.close-action" : "close"
			},
			sendDialog : app.module("dialogs.messages.send"),
			renderType : "byView",
			show : function() {
				this.$el.show();
			},
			close : function() {
				this.$el.hide();
			},
			replyTo : function() {
				var model = new this.sendDialog.models.message();
				model.set("user_id.0.id", this.model.get("user_id"));
				model.set("reply_to", this.model.get("id"));
				model.set("subject", "fwd:" + this.model.get("subject"));
				//this.sendDialog
				this.sendDialog.dialogView.setMode("user");
				this.sendDialog.dialogView.setModel(model);
				this.sendDialog.dialogView.open();
			}
		});

		var messageTableViewClass = tableViewClass.extend({
			initialize : function() {
				console.info('portlet.messages/messageTableViewClass::initialize');

				tableViewClass.prototype.initialize.apply(this, arguments);

				var self = this;

				this.messagesBodyView = new messagesBodyViewClass({
					el: "#message-body-container"
				});
				/*
				this.listenTo(this, "cellclick.datatable", function(model data, el) {
					console.warn(model data, el);
				})
				*/
				/*
				this.$("tbody").on('click', 'td', function () {
					var tr = $(this).closest('tr');
        			var row = self.getApi().row( tr );

        			console.warn(tr, row, row.data());
        		});
        		*/

			},
        	getTableItemModel : function(data) {
				return new mod.models.messages.message(data);
        	},
        	onCellClick : function(model, data, el) {
        		this.messagesBodyView.setModel(model);
        		this.messagesBodyView.show();
        	}
		});


		/*
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

		var entityDropdownViewItemClass = baseChildTabViewItemClass.extend({
			tagName : "li",
			events : {
				"click a.select-item" : "selectItem"
			},
			template : _.template($("#dropdown_child-item-template").html(), null, {variable: "model"}),
			selectItem : function(e) {
				this.parentView.trigger("dropdown-item.selected", this.model);
				//app.userSettings.set("class_id", this.model.get("id"));
				//mod.programsCollection.moveToCourse(this.model.get("id"));

				//$("[href='#tab_course_units']").tab('show');
			},
		});
		var entityDropdownViewClass = baseChildTabViewClass.extend({
			nofoundTemplate : _.template($("#tab_courses_child-nofound-template").html()),
			childViewClass : entityDropdownViewItemClass,
			initialize: function(opt) {
				console.info('portlet.content/courseDropdownViewClass::initialize');

				if (_.has(opt, 'childViewClass')) {
					this.childViewClass = opt.childViewClass;
				}

				baseChildTabViewClass.prototype.initialize.apply(this, arguments);
			},
			setCollection : function(model) {
				baseChildTabViewClass.prototype.setCollection.apply(this, arguments);

				this.render();
			},
			makeCollection: function() {
				return this.collection;
			},
		});
		*/
		var messagesTabViewClass = baseChildTabViewClass.extend({
			//template : _.template($("#tab_program_description-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.messages/messagesTabViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));

				var message_context = $SC.getResource("messages_context");

    			// START DATATABLE HERE
    		    this.tableView =  new messageTableViewClass({
			        el : "#messages-table-messages",
			        datatable : {
			            "sAjaxSource": message_context.ajax_source,
			            "aoColumns": message_context.datatable_fields,
			            dom : "<'row'<'col-lg-12 col-md-12 col-sm-12'f>r><t><'row'<'col-md-7 col-sm-12'p>>",
			            bScrollInfinite: true,
			            bScrollCollapse: true,
			            sScrollY: "330px",
			            paging: false
			        }
			    });
    			$SC.addTable("messages-table-messages", this.tableView);



    			// LISTEN TO DATATABLE EVENTS
    			this.listenTo(this.tableView, "action.datatable", function(data, item, model) {
    				/*
                    if ($(item).hasClass("datatable-option-approve")) {
						item
							.tooltip('disable')
							.find("i.fa")
							.addClass("fa-refresh fa-spin");

                    	console.warn(data, item, model);
                    	var model = new mod.models.enroll.user(data);
                    	model.set('approved', 1);
                    	model.save();

                    	window.setTimeout(function() {
                    		item.hide(500);
                    	}, 1500);
                    }
                    */
                }.bind(this));

				this.render();

				//this.listenTo(mod.programsCollection, "program.changed", this.setModel.bind(this));
				//this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));
			},
			render : function(e) {
				console.info('portlet.messages/messagesTabViewClass::render');
				//this.$(".program-description-content").empty().append(this.template(this.model.toJSON()));

				//this.navigationView.render();
				this.renderProgress();
			},
			renderProgress : function() {
				console.info('portlet.messages/messagesTabViewClass::renderProgress');
			  	
				var totalMessages = this.tableView.oTable.api().data().count();

				if (totalMessages > 0) {
					$(".messages-indicator span.counter").html(totalMessages).show();
				} else {
					$(".messages-indicator span.counter").hide();
				}

				if (totalMessages > 1) {
					$(".messages-indicator span.singular").hide();
					$(".messages-indicator span.plural").show();
				} else {
					$(".messages-indicator span.singular").show();
					$(".messages-indicator span.plural").hide();
				}
			},
		});


		/* CLASSES TABS VIEW CLASSES */
		this.widgetViewClass = parent.widgetViewClass.extend({
			messagesTabView : null,
			forumTabView : null,
			faqTabView : null,
			start : function() {
				console.info('portlet.messages/widgetViewClass::start');
				Marionette.triggerMethodOn(this, "beforeStart");
				// CREATE SUB-VIEWS AND FETCH MODELS AND COLLECTIONS

				this.$(".portlet-title").height(26);

				this.startViews();
				//this.startCourseView();
				//this.startUnitView();

				Marionette.triggerMethodOn(this, "start");

				//this.listenTo(this.collection, "program.changed", this.renderProgram.bind(this));
				//this.listenTo(this.collection, "course.changed", this.renderCourse.bind(this));
				//this.listenTo(this.collection, "unit.changed", this.renderUnit.bind(this));

				//this.renderProgram();
				//this.renderCourse();
				//this.renderUnit();

				//this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));
			},
			/*
			renderProgram : function() {
				console.info('portlet.content/widgetViewClass::render');
				this.$(".program-title").html(this.collection.getCurrentProgram().get("name"));
				this.$(".program-count").html(this.collection.getCurrentPrograms().size());

				this.programDropdownView.setCollection(this.collection.getCurrentPrograms());

				this.courseDropdownView.setCollection(this.collection.getCurrentCourses());
			},
			renderCourse : function() {
				var course = this.collection.getCurrentCourse();
				if (course) {
					this.$(".course-title").html(course.get("name"));
				} else {
					this.$(".course-title").html();
				}
				this.$(".course-count").html(this.collection.getCurrentCourses().size());

				this.unitDropdownView.setCollection(this.collection.getCurrentUnits());
			},
			renderUnit : function() {
				var unit = this.collection.getCurrentUnit();
				if (unit) {
					this.$(".unit-title").html(unit.get("name"));
				} else {
					this.$(".unit-title").html();
				}
				//this.$(".unit-title").html(this.collection.getCurrentUnit().get("name"));
				this.$(".unit-count").html(this.collection.getCurrentUnits().size());
			},
			*/
			startViews : function() {
				console.info('portlet.content/widgetViewClass::startProgramView');

				if (_.isNull(this.messagesTabView)) {
					/*
					this.programTabView = new programTabViewClass({
						model : this.collection.getCurrentProgram(),
						//collection : this.collection,
						el : this.$("#program-tab"),
            			portlet : this.$el
					});
					*/
					/*
					this.programDropdownView = new entityDropdownViewClass({
						el : this.$(".program-dropdown")
					});

					this.courseDropdownView = new entityDropdownViewClass({
						el : this.$(".course-dropdown")
					});
					this.unitDropdownView = new entityDropdownViewClass({
						el : this.$(".unit-dropdown")
					});
					*/
					this.messagesTabView 	= new messagesTabViewClass({
						el : $("#tab_messages_messages")
						//model : this.collection.getCurrentProgram()
					});
					/*
					this.programCoursesTabView = new programCoursesTabViewClass({
						el : $("#tab_program_courses"),
						childContainer : "table.course-table tbody",
						model : this.collection.getCurrentProgram()
					});

					this.courseUnitsTabView = new courseUnitsTabViewClass({
						el : this.$("#tab_course_units"),
						childContainer : "table.unit-table tbody",
						model : this.model
					});

		            this.unitVideoTabView   = new unitVideoTabViewClass({
		                el : this.$("#unit-video-container"),
		                childContainer : ".popupcontent-body",
		                // model : this.model,
		            });
		            */
		            // MATERIALS
		            /*
		            this.unitMaterialsTabView   = new unitMaterialsTabViewClass({
		                el : this.$("#unit-material-container"),
		                childContainer : "table.unit-material-table tbody",
		                // model : this.model,
		            });
		            */

					this.messagesTabView.render();
					//this.programCoursesTabView.render();
					//this.courseUnitsTabView.render();

					/*
					this.listenTo(this.courseUnitsTabView, "watch:video", function(unitModel) {
						this.unitVideoTabView.setModel(unitModel);
						this.unitVideoTabView.render();

						//this.courseUnitsTabView.showContentArea();
					}.bind(this));

					this.listenTo(this.courseUnitsTabView, "list:materials", function(unitModel) {
						this.unitMaterialsTabView.setModel(unitModel);
						this.unitMaterialsTabView.render();

						//this.courseUnitsTabView.showContentArea();
						//this.courseUnitsTabView.showContentSidebar();
					}.bind(this));

					this.listenTo(this.programDropdownView, "dropdown-item.selected", function(programModel) {
						mod.programsCollection.moveToProgram(programModel.get("id"));
					}.bind(this));

					this.listenTo(this.courseDropdownView, "dropdown-item.selected", function(courseModel) {
						mod.programsCollection.moveToCourse(courseModel.get("id"));
					}.bind(this));

					this.listenTo(this.unitDropdownView, "dropdown-item.selected", function(unitModel) {
						mod.programsCollection.moveToUnit(unitModel.get("id"));
					}.bind(this));
					*/

					//this.programTabView.render();
				}
			},
		});
	});
	/*
	var baseModel = app.module("models").getBaseModel();

	var contentBaseModel = baseModel.extend({
		isCourse : function() {
			return false;
		}
	});

	this.models = {
		program : contentBaseModel.extend({}),
		course : contentBaseModel.extend({
			getUnits : function() {
				if (this.get('units') && (this.get('units') instanceof mod.collections.units)) {
					return this.get('units');
				}

				this.set('units', new mod.collections.units(this.get("units")));

				return this.get('units');
			},
			isCourse : function() {
				return true;
			}
		}),
		unit : contentBaseModel.extend({
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
		content : contentBaseModel.extend({
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
		content_progress : contentBaseModel.extend({
			response_type : "silent",
			urlRoot : "/module/roadmap/item/content-progress",
			setAsViewed : function(model, factor) {
				if (_.isUndefined(factor)) {
					factor = 1;
				}
				this.set("content_id", model.get("id"));
				this.set("factor", factor);

				this.save();

				//if (factor == 1) {
					mod.progressCollection.fetch();
				//}

				model.set("progress", this.toJSON());
			}
		}),
		progress : contentBaseModel.extend({
			url : "/module/content/datasource/progress",
			initialize : function() {
				this.on("sync", function(model, data, response) {
					for (var type in model.changed) {
						for(var i in model.changed[type]) {
							if (!_.isEmpty(model.changed[type][i])) {
								app.trigger("progress." + type + "-changed", model.attributes[type][i]);
							}
						}
					}
				}.bind(this));
			},
			getUnitProgress : function(id) {
				return _.findWhere(this.get("units"), {"lesson_id" : id});
			},
			getContentProgress : function(id) {
				return _.findWhere(this.get("contents"), {"content_id" : id});
			},
			getTotalPrograms : function(programs) {
				if (_.isUndefined(programs)) {
					var progressUnits = this.get("programs");
					return _.size(progressUnits);
				} else {
					return programs.size();
				}
			},
			getTotalPendingPrograms : function(programs) {
				var progressPrograms = this.get("programs");

				//console.warn(progressPrograms);

				var total = programs.reduce(function(count, program) {
				  var progress = _.findWhere(progressPrograms, {course_id : program.get("id")});
				  
				  if (!_.isUndefined(progress) && parseFloat(progress.factor) == 1) {
				    return count;
				  }
				  return count + 1;
				}, 0);

				return total;
			},
			getTotalCourses : function(courses) {
				if (_.isUndefined(courses)) {
					var progressUnits = this.get("courses");
					return _.size(progressUnits);
				} else {
					return courses.size();
				}
			},
			getTotalPendingCourses : function(courses) {
				var progressCourses = this.get("courses");

				var total = courses.reduce(function(count, course) {
				  var progress = _.findWhere(progressCourses, {class_id : course.get("id")});
				  
				  if (!_.isUndefined(progress) && parseFloat(progress.factor) == 1) {
				    return count;
				  }
				  return count + 1;
				}, 0);

				return total;
			},
			getTotalUnits : function(units) {
				if (_.isUndefined(units)) {
					var progressUnits = this.get("units");
					return _.size(progressUnits);
				} else {
					return units.size();
				}
			},
			getTotalCoursesUnits : function(courses) {
				var total = courses.reduce(function(count, item, i) {
					var units = item.getUnits();
					if (units) {
						return count + units.size();
					}
					return count;
				}, 0);

				return total;
				//var progressCourses = this.get("units");				
			},
			getTotalCompleteUnits : function(units) {

				var progressUnits = this.get("units");

				if (_.isUndefined(units)) {
					var total = progressUnits.reduce(function(count, progress) {
					  if (!_.isUndefined(progress) && parseFloat(progress.factor) == 1) {
					    return count + 1;
					  }
					  return count;
					}, 0);

					return total;
				} else {
					var total = units.reduce(function(count, unit) {
					  var progress = _.findWhere(progressUnits, {lesson_id : unit.get("id")});
					  
					  if (!_.isUndefined(progress) && parseFloat(progress.factor) == 1) {
					    return count + 1;
					  }
					  return count;
					}, 0);

					return total;
				}
			},
			getTotalPendingUnits : function(units) {

				var progressUnits = this.get("units");

				if (_.isUndefined(units)) {
					var total = progressUnits.reduce(function(count, progress) {
					  if (!_.isUndefined(progress) && parseFloat(progress.factor) == 1) {
					    return count;
					  }
					  return count + 1;
					}, 0);

					return total;
				} else {
					var total = units.reduce(function(count, unit) {
					  var progress = _.findWhere(progressUnits, {lesson_id : unit.get("id")});
					  
					  if (!_.isUndefined(progress) && parseFloat(progress.factor) == 1) {
					    return count;
					  }
					  return count + 1;
					}, 0);

					return total;
				}
			}
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
			updateProgramIndex : function() {
				// DESTROY DEPENDENT COLLECTIONS
				this.courses = null;
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
							scope : 'program',
							entity_id : this.current.program_id
						},
						dataType : 'json',
						success : function(data, textStatus, jqXHR) {
							this.current = data;

							this.trigger("course.changed", this.getCurrentCourse());
						}.bind(this)
					}
				);
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
			toPreviousProgramIndex : function() {
				var programIndex = this.getProgramIndex();
				if (programIndex <= 0) {
					return false;
				}
				programIndex--;
				var model = this.at(programIndex);
				if (!_.isUndefined(model)) {
					this.moveToProgram(model.get("id"));
				}
			},
			toNextProgramIndex : function() {
				var programIndex = this.getProgramIndex();
				if (programIndex >= this.size()) {
					return false;
				}
				programIndex++;
				var model = this.at(programIndex);
				if (!_.isUndefined(model)) {
					this.moveToProgram(model.get("id"));
				}
			},
			moveToProgram : function(program_id) {
				var model = this.findWhere({id : program_id});
				//var model = this.courses.findWhere({id : course_id});
				//var courseIndex = this.getCurrentCourses().indexOf(model);
				var programIndex = this.indexOf(model);
				if (programIndex >= 0) {
					// CALCULATE NEW POINTERS
					this.current.program_id = program_id;
					this.updateProgramIndex();

					this.trigger("program.changed", model, programIndex);

				}
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
				if (_.isNull(this.programs)) {
					this.programs = this;

					this.listenTo(this.programs, "previous", this.toPreviousProgramIndex.bind(this));
					this.listenTo(this.programs, "next", this.toNextProgramIndex.bind(this));
				}
				return this.programs;
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
					if (!_.isUndefined(course)) {
						if (course.get("units") instanceof mod.collections.units) {
							this.units = course.get("units");
						} else {
							this.units = new mod.collections.units(course.get("units"));
						}
						this.listenTo(this.units, "previous", this.toPreviousUnitIndex.bind(this));
						this.listenTo(this.units, "next", this.toNextUnitIndex.bind(this));
					} else {
						this.units = new mod.collections.units();
					}

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
			},
			updateProgress : function(progressModel) {

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
	};
	mod.programsCollection = null;
	mod.progressCollection = null;
	*/

	mod.on("start", function() {
		//var userSettingsModel = new userSettingsModelClass();
		//
		/*
		var contentInfo = $SC.getResource("content_widget_data");

		mod.programsCollection = new this.collections.programs({
			current : contentInfo.current
		});

		mod.programsCollection.reset(contentInfo.tree);

		mod.progressCollection = new this.models.progress(contentInfo.progress);

		//app.trigger("progress.started");
		*/
		this.widgetView = new this.widgetViewClass({
			model : app.userSettings,
			//collection : mod.programsCollection,
			el: '#messages-widget'
		});

		console.warn(this.widgetView);
	});
});
