$SC.module("portlet.content", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.on("start", function() {
		var parent = app.module("portlet");
		// TODO THINK ABOUT MOVE THIS CLASS INTO THE MAIN VIEW
		var baseChangeModelViewClass = app.module("views").baseChangeModelViewClass;
		var baseChildTabViewClass = app.module("views").baseChildTabViewClass;
		var baseChildTabViewItemClass = app.module("views").baseChildTabViewItemClass;

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

		var courseDropdownViewItemClass = baseChildTabViewItemClass.extend({
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
		})
		var courseDropdownViewClass = baseChildTabViewClass.extend({
			nofoundTemplate : _.template($("#tab_courses_child-nofound-template").html()),
			childViewClass : courseDropdownViewItemClass,
			initialize: function() {
				console.info('portlet.content/courseDropdownViewClass::initialize');

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



		/* COURSE TABS VIEW CLASSES */
		/*
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
		*/

		var programDescriptionTabViewClass = Backbone.View.extend({
			template : _.template($("#tab_program_description-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.content/programDescriptionTabViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));
				this.render();

				this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));
			},
			render : function(e) {
				console.info('portlet.content/programDescriptionTabViewClass::render');
				this.$el.empty().append(this.template(this.model.toJSON()));

				this.renderProgress();
			},
			renderProgress : function(collection, data, response) {
				console.info('portlet.content/programDescriptionTabViewClass::renderProgress');
				var totalUnits = mod.progressCollection.getTotalPendingPrograms(mod.programsCollection.getCurrentPrograms());

				if (totalUnits > 0) {
					$(".program-indicator span").html(totalUnits);
					$(".program-indicator").show();
				} else {
					$(".program-indicator").hide();
				}
			},
		});

		var programCoursesTabViewItemClass = baseChildTabViewItemClass.extend({
			events : {
				"click .class-change-action" : "setClassId"
			},
			template : _.template($("#tab_program_courses-item-template").html(), null, {variable: "model"}),
            initialize : function() {
				baseChildTabViewItemClass.prototype.initialize.apply(this, arguments);

				this.listenTo(app, "progress.courses-changed", function(info) {
					if (info.class_id == this.model.get("id")) {
						this.model.set("progress", info);
						this.render();
					}
				}.bind(this));
            },
			setClassId : function(e) {
				//app.userSettings.set("class_id", this.model.get("id"));
				mod.programsCollection.moveToCourse(this.model.get("id"));

				$("[href='#tab_course_units']").tab('show');
			},
			getMappedModel : function() {
				var units = this.model.getUnits();

				units.each(function(item, i) {
					var progress = mod.progressCollection.getUnitProgress(item.get("id"));
					item.set("progress", progress);
				});

				var totalUnits = mod.progressCollection.getTotalCompleteUnits(units);

				this.model.set("units_completed", totalUnits);
				var result = this.model.toJSON();

				result.units = units.toJSON();

				return result;
			},
			render : function(e) {
				console.info('portlet.content/courseUnitsTabViewItemClass::render');

				this.$el.html(
					this.template(this.getMappedModel())
				);
				return this;
			},
			/*
			checkProgress : function(model) {
				var progress = _.findWhere(model.get("courses"), {class_id : this.model.get("id")});
				if (!_.isUndefined(progress)) {
					this.model.set("progress", progress);
					this.render();
				}
			}
			*/
		})

		var programCoursesTabViewClass = baseChildTabViewClass.extend({
			nofoundTemplate : _.template($("#tab_program_courses-nofound-template").html()),
			childViewClass : programCoursesTabViewItemClass,
			initialize: function() {
				console.info('portlet.content/classInfoTabViewClass::initialize');

				baseChildTabViewClass.prototype.initialize.apply(this, arguments);

				console.warn(this.$(".navbar-program"));

				// TODO CREATE SUB VIEWS!!
				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-program"),
					collection : mod.programsCollection.getCurrentPrograms.bind(mod.programsCollection),
					pointer : mod.programsCollection.getProgramIndex.bind(mod.programsCollection)
				});
				this.navigationView.render();

				this.listenTo(mod.programsCollection, "program.changed", this.setModel.bind(this));
				this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));
			},
			setModel : function(model) {
				baseChildTabViewClass.prototype.setModel.apply(this, arguments);
				this.render();
			},
			render : function(model) {
				baseChildTabViewClass.prototype.render.apply(this, arguments);

				this.navigationView.render();
				this.renderProgress();
			},
			renderProgress : function(collection, data, response) {
				console.info('portlet.content/courseUnitsTabViewClass::renderProgress');
				//var totalUnits = mod.progressCollection.getTotalPendingCourses(mod.programsCollection.getCurrentCourses());
				var totalUnits = mod.programsCollection.getCurrentCourses().size();

				//if (totalUnits > 0) {
					$(".course-indicator span").html(totalUnits);
					$(".course-indicator").show();
				//} else {
				//	$(".course-indicator").hide();
				//}
			},
			makeCollection: function() {
				return mod.programsCollection.getCurrentCourses();
			}
		});

		/* CLASSES TABS VIEW CLASSES */
		var courseUnitsTabViewItemClass = baseChildTabViewItemClass.extend({
			events : {
				"click .watch-video-action" : "watchVideo",
				"click .list-materials-action" : "listMaterials",
				"click .view-test-action" : "openDialog",
				"click .open-test-action" : "doTest"
			},
			testInfoModule : app.module("dialogs.tests.info"),
			dialogContentUnit : app.module("dialogs.content.unit"),
			lessonTemplate : _.template($("#tab_courses_units-item-template").html(), null, {variable: "model"}),
            testTemplate : _.template($("#tab_courses_tests-item-template").html(), null, {variable: "model"}),
            initialize : function() {
				baseChildTabViewItemClass.prototype.initialize.apply(this, arguments);

				this.listenTo(app, "progress.units-changed", function(info) {
					if (info.lesson_id == this.model.get("id")) {
						this.model.set("progress", info);
						this.render();
					}
				}.bind(this));
            },
			watchVideo : function(e) {
				mod.programsCollection.moveToUnit(this.model.get("id"));

				this.parentView.trigger("watch:video", this.model);
			},
			listMaterials : function(e) {
				//mod.programsCollection.moveToUnit(this.model.get("id"));

				this.parentView.trigger("list:materials", this.model);
			},
			getMappedModel : function() {
				var video = this.model.getVideo();
				// UPDAT PROGRESS

				if (video) {
					var progress = mod.progressCollection.getContentProgress(video.get("id"));
					video.set("progress", progress);
					var videoInfo = video.toJSON();	
				} else {
					var videoInfo = false;
				}

				var materials = this.model.getMaterials();

				materials.each(function(item, i) {
					var progress = mod.progressCollection.getContentProgress(item.get("id"));
					item.set("progress", progress);
				});

				var result = this.model.toJSON();
				result.video = videoInfo;	
				result.materials = materials.toJSON();

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
            /*
			checkProgress : function(model) {
				var progress = _.findWhere(model.get("units"), {lesson_id : this.model.get("id")});
				if (!_.isUndefined(progress)) {
					this.model.set("progress", progress);
					this.render();
				}
			},
			*/
            doTest : function(model) {
            	//app.module("utils.toastr").message("info", "Test execution not disponible yet!");
            	//alert("Doing Test " + this.model.get("id"));
            	// START TEST EXECUTION this.model
            }
		});

		var courseUnitsTabViewClass = baseChildTabViewClass.extend({
			nofoundTemplate : _.template($("#tab_courses_child-nofound-template").html()),
			childViewClass : courseUnitsTabViewItemClass,
	        events : {
	            "click .close-content-sidebar" : "hideContentSidebar"
	        },
			initialize: function() {
				console.info('portlet.content/classInfoTabViewClass::initialize');

				baseChildTabViewClass.prototype.initialize.apply(this, arguments);

				// TODO CREATE SUB VIEWS!!
				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-course"),
					collection : mod.programsCollection.getCurrentCourses.bind(mod.programsCollection),
					pointer : mod.programsCollection.getCourseIndex.bind(mod.programsCollection)
				});
				this.navigationView.render();


				this.listenTo(mod.programsCollection, "course.changed", this.setModel.bind(this));
				this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));


			},
			setModel : function(model) {
				baseChildTabViewClass.prototype.setModel.apply(this, arguments);

				this.render();
			},
			render : function(model) {
				baseChildTabViewClass.prototype.render.apply(this, arguments);

				this.navigationView.render();
				this.renderProgress();

				app.module("ui").refresh(this.$el);
			},
			renderProgress : function(collection, data, response) {
				console.info('portlet.content/courseUnitsTabViewClass::renderProgress');
				//var totalUnits = mod.progressCollection.getTotalPendingUnits(mod.programsCollection.getCurrentUnits());
				var totalUnits = mod.programsCollection.getCurrentUnits().size();

				//if (totalUnits > 0) {
					$(".unit-indicator span").html(totalUnits);
					$(".unit-indicator").show();
				//} else {
				//	$(".unit-indicator").hide();
				//}
			},
			makeCollection: function() {
				return mod.programsCollection.getCurrentUnits();
			},
			showContentArea : function() {
				this.$(".content-container").removeClass("hidden");
			},
			hideContentArea : function() {
				this.$(".content-container").addClass("hidden");
			},
			showContentSidebar : function() {
				this.$(".content-container").removeClass("full-video");
			},
			hideContentSidebar : function() {
				this.$(".content-container").addClass("full-video");
			},
		});


	    var unitVideoTabViewClass = baseChangeModelViewClass.extend({
	        videoJS : null,
	        nofoundTemplate : _.template($("#tab_unit_video-nofound-template").html()),
	        template : _.template($("#tab_unit_video-item-template").html(), null, {variable: "model"}).bind(this),
	        events : {
	        	"click .close-video" : "stopAndClose"
	        },
	        onScreenStatus : true,
	        onScreenTime : null,
	        onScreelThreshold : 0.5 * 1000, // 5 seconds
	        scrollEvent : null,
	        viewType : "normal", // normal or float
	        initialize: function(opt) {
	            console.info('portlet.content/unitVideosTabViewClass::initialize');

	            //this.listenTo(mod.programsCollection, "unit.changed", this.setModel.bind(this));

	            // CREATE DRAGGABLE E RESIZABLE BEHAVIOUR
	        },
	        render : function(e) {
	            console.info('portlet.content/unitVideosTabViewClass::render');
	            var self = this;

	            if (!this.model.get("video")) {
	                // THERE'S NO VIDEO LESSON... DISABLE THE VIEW
	                this.disableView();

	            } else {
	                this.enableView();

	                this.videoModel = this.model.get("video");

					var progress = mod.progressCollection.getContentProgress(this.videoModel.get("id"));
					this.videoModel.set("progress", progress);


	                if (!_.isNull(this.videoJS)) {
	                    this.videoJS.dispose();
	                }

	                if (this.videoModel) {
	                    var videoDomID = "unit-video-" + this.videoModel.get("id");

	                    if (this.$("#" + videoDomID).size() === 0) {
	                        this.$el.empty().append(
	                            this.template(this.videoModel.toJSON())
	                        );

	                        this.$el.addClass("pop-out");

							this.$(".videocontent").draggable({
							    start: function( event, ui ) {
							        $(this).css({
							            //top: $(this).position().top,
							            bottom: "auto",
							            //left: $(this).position().left,
							            right: "auto"
							        });
							    },
							    //cursor: "crosshair",
							    handle: ".videocontent-header"
							});

	                        //var videoData = _.pick(entityData["data"], "controls", "preload", "autoplay", "poster", "techOrder", "width", "height", "ytcontrols");
	                        videojs(videoDomID, {
	                            "controls": true,
	                            "autoplay": false,
	                            "preload": "auto",
	                            "width" : "auto",
	                            "height" : "617",
	                            "techOrder" : [
	                                'html5', 'flash'
	                            ]
	                        }, function() {
	                        });
	                    }

	                    this.videoJS = videojs(videoDomID);

	                    this.videoJS.ready(this.bindStartVideoEvents.bind(this));

	                    mod.videoJS = this.videoJS;
   
	                }

	                app.module("ui").refresh(this.$el);


	                this.onScreenStatus = this.$el.isOnScreen(1, 0);
	                this.onScreenTime = Date.now();

	                //this.onScreenInterval = window.setInterval(this.checkViewType.bind(this), this.onScreelThreshold);
	            }
	        },
	        checkViewType : function(evt) {
	        	/*
	        	console.info('portlet.content/unitVideosTabViewClass::checkViewType');
	            var currentScreenStatus = this.$el.isOnScreen(1, 0);
	            var currentScreenTime = Date.now();

	            if (currentScreenStatus != this.onScreenStatus) {
	            	// RESET THE COUNTER AND STATUS
	            	this.onScreenTime = Date.now();
	                this.onScreenStatus = currentScreenStatus;

	                this.onScreenInterval = window.setTimeout(this.changeViewType.bind(this), this.onScreelThreshold + 150);
	            }
	            */
	        },
	        changeViewType : function(evt) {
	        	/*
	        	console.info('portlet.content/unitVideosTabViewClass::changeViewType');
	            var currentScreenStatus = this.$el.isOnScreen(1, 0);
	            var currentScreenTime = Date.now();

	            if ((currentScreenStatus == this.onScreenStatus) && (currentScreenTime - this.onScreenTime >= this.onScreelThreshold)) {
	            	// THE SAME STATUS MORE THAN 5 SECONDS, IF THE VIEW  
	            	if (currentScreenStatus && this.viewType == "float") {
	            		// SWITCH TO NORMAL
						this.$el.removeClass("pop-out");

	                	// RESET THE COUNTER AND STATUS
	                	this.viewType = "normal";
	                	this.onScreenStatus = currentScreenStatus;
	                	this.onScreenTime = Date.now();
	            	} else if (!currentScreenStatus && this.viewType == "normal") {
	            		// SWITCH TO FLOAT
	            		this.$el.css({
	            			height : this.$el.height() + "px",
	            		});

	            		this.$el.addClass("pop-out-start");
	            		window.setTimeout(function() {
	            			this.$el.removeClass("pop-out-start");
							this.$el.addClass("pop-out");
	            		}.bind(this), 550)
	                	
	                	// RESET THE COUNTER AND STATUS
	                	this.viewType = "float";
	                	this.onScreenStatus = currentScreenStatus;
	                	this.onScreenTime = Date.now();
	            	} else {
	            	}
	            } else {
					// RESET THE COUNTER AND STATUS
					if (currentScreenStatus != this.onScreenStatus) {
						this.onScreenTime = Date.now();
					}
	                this.onScreenStatus = currentScreenStatus;
	            }
	            */
	        },
	        bindStartVideoEvents : function() {
	        	// CALCULATE HEIGHT
                //var width = this.$(".videocontent").outerWidth();
                //var height = width * 9 / 16;
                //this.$(".videocontent .vjs-auto-height").height(height);
                /*
				this.$(".videocontent").resizable({
					stop: function() {
						this.$(".videocontent .vjs-auto-height").css({
							"height" : "auto"
						});

		                var width = this.$(".videocontent").outerWidth();
		                console.warn(width);

		                var height = width * 9 / 16;

		                this.$(".videocontent .vjs-auto-height").height(height);

					}.bind(this),
					handles: "nw, ne, se, sw, n, e, s, w",
					aspectRatio: true,
					zIndex: 110,
					animate: true,
					helper : "ui-resizable-helper"
				});
				*/



	            var self = this;
	            //this.videoJS.play();
	            this.currentProgress = parseFloat(this.videoModel.get("progress.factor"));

	            if (_.isNaN(this.currentProgress)) {
	                this.currentProgress = 0;
	            }

	            // @todo CALCULATE THE CURRENT VIDEO TIMELINE, IF PREVIOUSLY STARTED

	            //if (this.currentProgress >= 1) {
	            //    this.trigger("video:viewed");
	            //} else {
	            	var progress = this.model.get("progress");
					this.videoJS.on("loadedmetadata", function() {
                        if (progress.factor < 1) {
                        	var start = this.duration() * progress.factor;
                        	//console.warn(progress.factor, this, start, this.duration());
                        	this.currentTime(start - 5); 
                        }

					});

	                this.videoJS.on("timeupdate", this.updateProgress.bind(this));

	                this.videoJS.on("ended", function() {
	                    this.currentProgress = 1;
	                    var progressModel = new mod.models.content_progress(this.videoModel.get("progress"));
	                    progressModel.setAsViewed(this.videoModel, this.currentProgress);

	                    this.trigger("video:viewed");
	                }.bind(this));


	                this.videoJS.play();
	            //}

	            //this.onScreenEvent = $(document).on("scroll."+this.cid + " resize."+this.cid, this.checkViewType.bind(this));

                //if (_.isNull(this.onScreenInterval)) {
                	//this.onScreenInterval = window.setInterval(this.checkViewType.bind(this, [true]), this.onScreelThreshold / 2);
                //}
	        },
	        updateProgress : function() {
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
    		},
	        stopAndClose : function() {
				if (!_.isNull(this.videoJS)) {
					this.updateProgress()
	                this.videoJS.dispose();
	                this.$el.hide();
	            }
	        },
	        disableView : function() {
	            //$("[href='#tab_unit_video'").hide();
	            //$("[href='#tab_unit_materials']").tab('show');
	            this.$el.hide();
	        },
	        enableView : function() {
	            //$("[href='#tab_unit_video'").show().tab('show');
	            this.$el.show();
	        }
	    });


	    var unitMaterialsTabViewItemClass = baseChildTabViewItemClass.extend({
	        events : {
	            "click .view-content-action" : "viewContentAction"
	        },
	        template : _.template($("#tab_unit_materials-item-template").html(), null, {variable: "model"}),
            initialize : function() {
				baseChildTabViewItemClass.prototype.initialize.apply(this, arguments);

				this.listenTo(app, "progress.contents-changed", function(info) {
					if (info.content_id == this.model.get("id")) {
						this.model.set("progress", info);
						this.render();
					}
				}.bind(this));
            },
	        viewContentAction : function(e) {
	            // TRACK PROGRESS
				
	            var progressModel = new mod.models.content_progress();
	            progressModel.setAsViewed(this.model);

	            this.model.set("progress", progressModel.toJSON());

	            //this.render();
	        },
	        /*
	        checkProgress : function(model) {
	            var progress = _.findWhere(model.get("contents"), {content_id : this.model.get("id")});
	            if (!_.isUndefined(progress)) {
	                this.model.set("progress", progress);
	                this.render();
	            }
	        },
	        */
			render : function(e) {
				var progress = mod.progressCollection.getContentProgress(this.model.get("id"));
				this.model.set("progress", progress);

				this.$el.html(
					this.template(this.model.toJSON())
				);
				return this;
			}
	    });

	    var unitMaterialsTabViewClass = baseChildTabViewClass.extend({
	        nofoundTemplate : _.template($("#tab_unit_materials-nofound-template").html()),
	        childViewClass : unitMaterialsTabViewItemClass,
	        makeCollection: function() {
	            var materials = this.model.get("materials");
	            return materials;
	        },
	        disableView : function() {
	            $("[href='#tab_unit_materials'],#tab_unit_materials").addClass("hidden");
	        },
	        enableView : function() {
	            $("[href='#tab_unit_materials'],#tab_unit_materials").removeClass("hidden");
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
				//this.listenTo(this.collection, "unit.changed", this.renderUnit.bind(this));

				this.renderProgram();
				this.renderCourse();
				//this.renderUnit();

				//this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));
			},
			
			renderProgram : function() {
				console.info('portlet.content/widgetViewClass::render');
				this.$(".program-title").html(this.collection.getCurrentProgram().get("name"));
				this.$(".program-count").html(this.collection.getCurrentPrograms().size());

				this.courseDropdownView.setCollection(this.collection.getCurrentCourses());
			},
			renderCourse : function() {
				this.$(".course-title").html(this.collection.getCurrentCourse().get("name"));
				this.$(".course-count").html(this.collection.getCurrentCourses().size());
			},
			/*
			renderUnit : function() {
				this.$(".unit-title").html(this.collection.getCurrentUnit().get("name"));
				this.$(".unit-count").html(this.collection.getCurrentUnits().size());
			},
			*/
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

					this.courseDropdownView = new courseDropdownViewClass({
						el : this.$(".course-dropdown")
					});

					this.programDescriptionTabView 	= new programDescriptionTabViewClass({
						el : $("#tab_program_description"),
						model : this.collection.getCurrentProgram()
					});

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
		                // model : this.model,
		            });
		            // MATERIALS
		            this.unitMaterialsTabView   = new unitMaterialsTabViewClass({
		                el : this.$("#unit-material-container table.unit-material-table tbody"),
		                // model : this.model,
		            });

					this.programDescriptionTabView.render();
					this.programCoursesTabView.render();
					this.courseUnitsTabView.render();


					this.listenTo(this.courseUnitsTabView, "watch:video", function(unitModel) {
						this.unitVideoTabView.setModel(unitModel);
						this.unitVideoTabView.render();

						this.courseUnitsTabView.showContentArea();
					}.bind(this));

					this.listenTo(this.courseUnitsTabView, "list:materials", function(unitModel) {
						this.unitMaterialsTabView.setModel(unitModel);
						this.unitMaterialsTabView.render();

						this.courseUnitsTabView.showContentArea();
						this.courseUnitsTabView.showContentSidebar();
					}.bind(this));

					this.listenTo(this.courseDropdownView, "dropdown-item.selected", function(courseModel) {
						mod.programsCollection.moveToCourse(courseModel.get("id"));
					}.bind(this));


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
		course : baseModel.extend({
			getUnits : function() {
				if (this.get('units') && (this.get('units') instanceof mod.collections.units)) {
					return this.get('units');
				}

				this.set('units', new mod.collections.units(this.get("units")));

				return this.get('units');
			}
		}),
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
				/*
				this.once("sync", function(model) {
					var updateInfo = model.get("info");

					for(var type in updateInfo) {
						app.trigger("progress." + type + "-changed", updateInfo[type]);
					}
					//app.trigger("progress.content-changed", model, this);
				}.bind(this));
				*/

				this.save();

				//if (factor == 1) {
					mod.progressCollection.fetch();
				//}

				model.set("progress", this.toJSON());
			}
		}),
		progress : baseModel.extend({
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
			getTotalPendingPrograms : function(programs) {
				var progressPrograms = this.get("programs");

				var total = programs.reduce(function(count, program) {
				  var progress = _.findWhere(progressPrograms, {course_id : program.get("id")});
				  
				  if (parseFloat(progress.factor) == 1) {
				    return count;
				  }
				  return count + 1;
				}, 0);

				return total;
			},
			getTotalPendingCourses : function(courses) {
				var progressCourses = this.get("courses");

				var total = courses.reduce(function(count, course) {
				  var progress = _.findWhere(progressCourses, {class_id : course.get("id")});
				  
				  if (parseFloat(progress.factor) == 1) {
				    return count;
				  }
				  return count + 1;
				}, 0);

				return total;
			},
			getTotalCompleteUnits : function(units) {

				var progressUnits = this.get("units");

				var total = units.reduce(function(count, unit) {
				  var progress = _.findWhere(progressUnits, {lesson_id : unit.get("id")});
				  
				  if (parseFloat(progress.factor) == 1) {
				    return count + 1;
				  }
				  return count;
				}, 0);

				return total;
			},
			getTotalPendingUnits : function(units) {

				var progressUnits = this.get("units");

				var total = units.reduce(function(count, unit) {
				  var progress = _.findWhere(progressUnits, {lesson_id : unit.get("id")});
				  
				  if (parseFloat(progress.factor) == 1) {
				    return count;
				  }
				  return count + 1;
				}, 0);

				return total;
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
					if (course.get("units") instanceof mod.collections.units) {
						this.units = course.get("units");
					} else {
						this.units = new mod.collections.units(course.get("units"));
					}

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

	mod.on("start", function() {
		//var userSettingsModel = new userSettingsModelClass();
		//
		var contentInfo = $SC.getResource("content_widget_data");

		mod.programsCollection = new this.collections.programs({
			current : contentInfo.current
		});

		mod.programsCollection.reset(contentInfo.tree);

		mod.progressCollection = new this.models.progress(contentInfo.progress);

		app.trigger("progress.started");

		this.widgetView = new this.widgetViewClass({
			model : app.userSettings,
			collection : mod.programsCollection,
			el: '#content-widget'
		});

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
