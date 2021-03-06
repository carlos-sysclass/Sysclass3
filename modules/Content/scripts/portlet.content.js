$SC.module("portlet.content", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.on("start", function() {
		var parent = app.module("portlet");
		// TODO THINK ABOUT MOVE THIS CLASS INTO THE MAIN VIEW
		var baseChangeModelViewClass = app.module("views").baseChangeModelViewClass;
		var baseChildTabViewClass = app.module("views").baseChildTabViewClass;
		var baseChildTabViewItemClass = app.module("views").baseChildTabViewItemClass;
		var tableViewClass = $SC.module("utils.datatables").tableViewClass;

		var navigationViewClass = Backbone.View.extend({
			events : {
				//"click .class-change-action"		: "goToClass",
				//"click .class-next-action"		: "nextClass",
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
				if (!_.isEmpty(this.collection())) {
					this.$(".entity-count")
						.html(this.collection().size());
				}
				if (_.isNumber(this.pointer())) {
					//cnsole.warn(this.$(".entity-current"), this.pointer);
					this.$(".entity-current")
						.html(this.pointer() + 1);

					if (this.pointer() <= 0) {
						//this.pointer = 0;
						this.$(".nav-prev-action").addClass("disabled");
					} else {
						this.$(".nav-prev-action").removeClass("disabled");
					}

					if (this.pointer() + 1 >= this.collection().size()) {
						this.$(".nav-next-action").addClass("disabled");
					} else {
						this.$(".nav-next-action").removeClass("disabled");
					}
				}



			},
			prevItem : function(e) {
				console.info('portlet.content/navigationViewClass::prevItem');
				//this.pointer--;

				e.preventDefault();

				this.collection().prev();

				if (this.pointer() <= 0) {
					//this.pointer = 0;
					this.$(".nav-prev-action").addClass("disabled");
				}
				this.render();
			},
			nextItem : function(e) {
				console.info('portlet.content/navigationViewClass::nextItem');
				//this.pointer++;

				e.preventDefault();

				this.collection().next();

				if (this.pointer() + 1 >= this.collection().size()) {
					//this.pointer = this.collection.size() - 1;
					this.$(".nav-next-action").addClass("disabled");
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

		var programDescriptionTabViewClass = baseChildTabViewClass.extend({
			template : _.template($("#tab_program_description-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.content/programDescriptionTabViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));

				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-program"),
					collection : mod.programsCollection.getCurrentPrograms.bind(mod.programsCollection),
					pointer : mod.programsCollection.getProgramIndex.bind(mod.programsCollection)
				});
				this.render();

				this.listenTo(mod.programsCollection, "program.changed", this.setModel.bind(this));
				this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));


    			var scrollOptions = {
				    size: '7px',
				    wrapperClass: "slimScrollDiv",
				    color: '#a1b2bd',
				    railColor: '#333',
				    position: 'right',
				    alwaysVisible: false,
				    railVisible: false,
				    disableFadeOut: true,
				    allowPageScroll : true,
				    wheelStep : 2,
				    height: '349px',
				};
       			this.$(".program-description-content-scroller").slimScroll(scrollOptions);

			},
			setModel : function(model) {
				baseChildTabViewClass.prototype.setModel.apply(this, arguments);

				this.render();
			},
			render : function(e) {
				console.info('portlet.content/programDescriptionTabViewClass::render');
				if (this.model) {
					this.$(".program-description-content").empty().append(this.template(this.model.toJSON()));

					this.navigationView.render();
					this.renderProgress();
				}
			},
			renderProgress : function(collection, data, response) {
				console.info('portlet.content/programDescriptionTabViewClass::renderProgress');
				var totalUnits = mod.progressCollection.getTotalPrograms(mod.programsCollection.getCurrentPrograms());

				if (totalUnits > 0) {
					$(".program-indicator span.counter").html(totalUnits).show();
				} else {
					$(".program-indicator span.counter").hide();
				}


				if (totalUnits > 1) {
					$(".program-indicator span.singular").attr(
						"style",
						"display: none !important"
					);
					$(".program-indicator span.plural").attr(
						"style",
						"display: inline !important"
					);
				} else {
					$(".program-indicator span.singular").attr(
						"style",
						"display: inline !important"
					);

					$(".program-indicator span.plural").attr(
						"style",
						"display: none !important"
					);
				}
			},
		});

		var programCoursesTabViewItemClass = baseChildTabViewItemClass.extend({
			events : {
				"click .course-change-action" : "setClassId",
				"click .course-info-action" : "viewCourseInfo"
			},
			template : _.template($("#tab_program_courses-item-template").html(), null, {variable: "model"}),
			courseInfoModule : app.module("dialogs.content.info"),
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
			viewCourseInfo : function(e) {
				e.preventDefault();

                if (!this.courseInfoModule.started) {
                    this.courseInfoModule.start();

                    //this.listenTo(this.courseInfoModule, "action:do-test", this.doTest.bind(this));
                }

                this.courseInfoModule.setInfo({
                	model : this.model
                });

                this.courseInfoModule.open();
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
			//nofoundTemplate : _.template($("#tab_program_courses-nofound-template").html()),
			childViewClass : programCoursesTabViewItemClass,
			initialize: function() {
				console.info('portlet.content/classInfoTabViewClass::initialize');

				baseChildTabViewClass.prototype.initialize.apply(this, arguments);

				// TODO CREATE SUB VIEWS!!
				this.navigationView 	= new navigationViewClass({
					el : this.$(".navbar-program"),
					collection : mod.programsCollection.getCurrentPrograms.bind(mod.programsCollection),
					pointer : mod.programsCollection.getProgramIndex.bind(mod.programsCollection)
				});
				this.navigationView.render();

				this.listenTo(mod.programsCollection, "program.changed", this.setModel.bind(this));
				this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));

    			// START DATATABLE HERE
    		    this.tableView =  new tableViewClass({
			        el : this.$("#course-table"),
			        datatable : {
			        	bAutoWidth: true,
			        	serverSide : false,
			        	//searching : false,
			        	ordering: false,
			            //"sAjaxSource": message_context.ajax_source,
			            //"aoColumns": message_context.datatable_fields,
			            dom : "<t>",
			            //bScrollInfinite: true,
			            //bScrollCollapse: false,
			            //sScrollY: "274px",
			            sScrollY: true,
			            paging: false,
			            fixedHeader: false
			        },
			        scrollY : true,
			        slimScroll : {
					    height: '312px',
					}
			    });
			},
			setModel : function(model) {
				baseChildTabViewClass.prototype.setModel.apply(this, arguments);
				this.render();
			},
			render : function(model) {
				//if (this.collection) {
					this.tableView.getApi().destroy(false);

					baseChildTabViewClass.prototype.render.apply(this, arguments);

					this.navigationView.render();
					this.renderProgress();

					this.onVisible();
				//}
			},
			onVisible : function() {
				this.tableView.recreate();
			},
			doSearch : function(e, text) {
				this.tableView.doSearch(e, text);
			},
			renderProgress : function(collection, data, response) {
				console.info('portlet.content/programCoursesTabViewClass::renderProgress');
				//var totalUnits = mod.progressCollection.getTotalCourses();
				var totalUnits = mod.programsCollection.getCurrentCourses().size();

				if (totalUnits > 0) {
					$(".course-indicator span.counter")
						.html(totalUnits)
						.show();
				} else {
					$(".course-indicator span.counter").hide();
				}

				if (totalUnits > 1) {
					$(".course-indicator span.singular").attr(
						"style",
						"display: none !important"
					);
					$(".course-indicator span.plural").attr(
						"style",
						"display: inline !important"
					);
				} else {
					$(".course-indicator span.singular").attr(
						"style",
						"display: inline !important"
					);

					$(".course-indicator span.plural").attr(
						"style",
						"display: none !important"
					);
				}

			},
			makeCollection: function() {
				return mod.programsCollection.getCurrentCourses();
			}
		});

		/* CLASSES TABS VIEW CLASSES */

		var unitMaterialDropdownViewItemClass = entityDropdownViewItemClass.extend({
			template : _.template($("#dropdown_child-unit-material_item-template").html(), null, {variable: "model"}),
		});

		var courseUnitsTabViewItemClass = baseChildTabViewItemClass.extend({
			events : {
				"click .watch-video-action" : "watchVideo",
				"click .list-materials-action" : "listMaterials",
				"click .view-test-action" : "openDialog",
				"click .open-test-action" : "doTest",
				"click .open-pdf-viewer" : "doTest",
			},
			testInfoModule : app.module("dialogs.tests.info"),
			dialogContentUnit : app.module("dialogs.content.unit"),
			lessonTemplate : _.template($("#tab_courses_units-item-template").html(), null, {variable: "model"}),
            testTemplate : _.template($("#tab_courses_tests-item-template").html(), null, {variable: "model"}),
            materialDropdownView : null,
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
				var videos = this.model.getVideos();
				// UPDAT PROGRESS

				if (videos.size() > 0) {
					var video = videos.first();
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

				/*
				materials.each(function(item, i) {
					var progress = mod.progressCollection.getContentProgress(item.get("id"));
					item.set("progress", progress);
				});
				*/

				var materialProgressFactor = materials.reduce(function(factor, item) {
					return factor + parseFloat(item.get("progress.factor"));
				}, 0);

				var result = this.model.toJSON();

				result.video = videoInfo;	
				//result.materials = materials.toJSON();

				result.materials = materials.toJSON();

				result.materialProgress = materialProgressFactor / _.size(materials);

				var executions = this.model.getTestExecutions();
				result.executions = executions.toJSON();

				var testExecution = this.model.getTestExecution();

				if (testExecution) {
					result.testExecution = testExecution.toJSON();	
				}

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

				this.renderSubViews();

				return this;
			},
			renderSubViews : function() {
				if (!_.isNull(this.materialDropdownView)) {
					this.materialDropdownView.remove();
				}
				this.materialDropdownView = new entityDropdownViewClass({
					el : this.$(".unit-material-dropdown"),
					childViewClass : unitMaterialDropdownViewItemClass
				});

				this.materialDropdownView.setCollection(this.model.getMaterials());

				this.listenTo(this.materialDropdownView, "dropdown-item.selected", function(materialModel) {
					// OPEN MATERIAL
					//this.parentView.trigger("list:materials", this.model);
					//alert(1);
					var progressModel = new mod.models.content_progress();
		            progressModel.setAsViewed(materialModel);

		            materialModel.set("progress", progressModel.toJSON());

				}.bind(this));
			},

            openDialog : function() {
                if (!this.testInfoModule.started) {
                    this.testInfoModule.start();

                    this.listenTo(this.testInfoModule, "action:do-test", this.doTest.bind(this));
                }

                app.module("dialogs.tests.info").setInfo({
                	model : this.model,
                	autoStart : true
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
            	//app.module("utils.toastr").message("info", "Test not available yet!");
            	//alert("Doing Test " + this.model.get("id"));
            	// START TEST EXECUTION this.model
            }
		});

		var courseUnitsTabViewClass = baseChildTabViewClass.extend({
			//nofoundTemplate : _.template($("#tab_courses_child-nofound-template").html()),
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

    			// START DATATABLE HERE
    		    this.tableView =  new tableViewClass({
			        el : this.$("#unit-table"),
			        datatable : {
			        	bAutoWidth: true,
			        	serverSide : false,
			        	//searching : false,
			        	ordering: false,
			            //"sAjaxSource": message_context.ajax_source,
			            //"aoColumns": message_context.datatable_fields,
			            dom : "<t>",
			            //bScrollInfinite: true,
			            //bScrollCollapse: false,
			            //sScrollY: "274px",
			            sScrollY: true,
			            paging: false,
			            fixedHeader: false
			        },
			        scrollY : true,
			        slimScroll : {
					    height: '312px',
					}
			    });
    			//$SC.addTable("messages-table-messages", this.tableView);

			},
			setModel : function(model) {
				baseChildTabViewClass.prototype.setModel.apply(this, arguments);

				this.render();
			},
			render : function(model) {
				this.tableView.getApi().destroy(false);

				baseChildTabViewClass.prototype.render.apply(this, arguments);

				//this.tableView.recreate();

				this.navigationView.render();
				this.renderProgress();

				this.onVisible();

				//app.module("ui").refresh(this.$el);
			},
			onVisible : function() {
				this.tableView.recreate();
			},
			doSearch : function(e, text) {
				this.tableView.doSearch(e, text);
			},
			renderProgress : function(collection, data, response) {
				console.info('portlet.content/courseUnitsTabViewClass::renderProgress');
				var totalUnits = mod.progressCollection.getTotalCoursesUnits(mod.programsCollection.getCurrentCourses());
				

				if (totalUnits > 0) {
					$(".unit-indicator span.counter").html(totalUnits).show();
				} else {
					$(".unit-indicator span.counter").hide();
				}

				if (totalUnits > 1) {
					$(".unit-indicator span.singular").attr(
						"style",
						"display: none !important"
					);
					$(".unit-indicator span.plural").attr(
						"style",
						"display: inline !important"
					);
				} else {
					$(".unit-indicator span.singular").attr(
						"style",
						"display: inline !important"
					);

					$(".unit-indicator span.plural").attr(
						"style",
						"display: none !important"
					);
				}

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
	        events : {
	        	"click .popupcontent-header a.minimize-action" : "minimize",
	        	"click .popupcontent-header a.fullscreen-action" : "fullscreen",
	        	"click .popupcontent-header a.close-action" : "stopAndClose",
	        	"click .popupcontent-header a.change-view-type" : "changeViewType"
	        },
	        videoJS : [],
	        videoJSIds : [],
	        videoJSReady : [],
	        ratingShown : false,
	        mainVideoIndex : 0,
	        nofoundTemplate : _.template($("#tab_unit_video-nofound-template").html()),
	        template : _.template($("#tab_unit_video-item-template").html(), null, {variable: "model"}).bind(this),
	        menuDropdownItemTemplate : _.template($("#tab_unit_video-multi-video-dropdown-item-template").html(), null, {variable: "model"}).bind(this),
	        onScreenStatus : true,
	        onScreenTime : null,
	        onScreelThreshold : 0.5 * 1000, // 5 seconds
	        scrollEvent : null,
	        viewType : "normal", // normal or float
	        childContainerSelector : false,
	        childContainer : false,
	        initialize: function(opt) {
	            console.info('portlet.content/unitVideosTabViewClass::initialize');

				if (_.has(opt, 'childContainer')) {
					this.childContainerSelector = opt.childContainer;
				} else {
					this.childContainerSelector = false;
				}
	        },
	        makeDraggable : function() {
	        	this.$el.removeClass("hidden");
	            this.$el.addClass("pop-out");

				this.$(".popupcontent").draggable({
					containment: "body",
					scroll : false,
				    start: function( event, ui ) {
				        $(this).css({
				            top: "auto",
				            bottom: "auto",
				            left: "auto",
				            right: "auto"
				        });
				    },
				    //cursor: "crosshair",
				    handle: ".popupcontent-header"
				});
	        },
	        addSecVideoDraggable : function() {
				this.$(".sec-video").draggable({
					containment: "parent",
					scroll : false,
				    start: function( event, ui ) {
				        $(this).css({
				            top: "40px",
				            bottom: "auto",
				            left: "auto",
				            right: "auto",
				            cursor: "move"
				        });
				    },
				    cursor: "move",
				    //handle: ".popupcontent-header"
				});
	        },
	        removeSecVideoDraggable : function() {
	        	this.$(".sec-video").removeAttr('style');
	        	if (this.$(".sec-video").data('draggable')) {
	        		this.$(".sec-video").draggable('destroy');
	        	}
	        },
	        render : function(e) {
	            console.info('portlet.content/unitVideosTabViewClass::render');
	            var self = this;

	            if (!this.model.get("videos")) {
	                // THERE'S NO VIDEO LESSON... DISABLE THE VIEW
	                this.disableView();
	            } else {
	                this.enableView();


	                var videosCollection = this.model.get("videos");

	                if (!_.isEmpty(this.videoJS)) {
	                	_.each(this.videoJS, function(video) {
	                    	video.dispose();
	                	});

	                	this.videoJSIds = [];
	                }

	                if (videosCollection.size() > 0) {
						if (this.childContainerSelector) {
							this.childContainer = this.$(this.childContainerSelector);
						} else {
							this.childContainer = this.$el;
						}

						this.childContainer.empty();

						this.mainVideoIndex = 0;

						var videoIndex = 0;

	                	videosCollection.each(function(videoModel) {
			        		var subtitles = [];
			        		var poster = null;

	                		// ADD POSTER TO ALL INSTANCES
	                		// ADD SUBTITLE TO ALL INSTANCES
							var progress = mod.progressCollection.getContentProgress(videoModel.get("id"));
							this.model.set("progress", progress);

	                		
			        		videoModel.getFiles().each(function(fileModel) {
			        			if (fileModel.isSubtitle()) {
			        				//this.videoJS[index].addTextTrack("subtitles", )
			        				subtitles.push(fileModel.toJSON());
			        			} else if (fileModel.isPoster()) {
			        				poster = fileModel.toJSON();
			        			}
			        		});

							videoModel.getFiles().each(function(fileModel) {

								if (fileModel.isVideo()) {

				                    var videoDomID = "unit-video-" + fileModel.get("id");

				                    if (this.$("#" + videoDomID).size() === 0) {
				                        var html = this.childContainer.append(
				                            this.template(_.extend(
				                            	fileModel.toJSON(),
				                            	{
				                            		subtitles : subtitles,
				                            		poster : poster,
				                            		is_main : (videoIndex == this.mainVideoIndex),
				                            		video_index : videoIndex
				                            	}
			                            	))
				                        );

				                        this.makeDraggable();

				                        //var videoData = _.pick(entityData["data"], "controls", "preload", "autoplay", "poster", "techOrder", "width", "height", "ytcontrols");
				                        videojs(videoDomID, {
				                            "controls": (videoIndex == this.mainVideoIndex),
				                            "autoplay": true,
				                            "preload": "auto",
				                            "width" : "auto",
				                            "height" : "617",
				                            "loadingSpinner" : (videoIndex == this.mainVideoIndex),
				                            /*
				                            "controlBar" : {
				                            	muteToggle : (videoIndex == this.mainVideoIndex),
				                            },
				                            */
				                            "controlBar" : {
				                            	"fullscreenToggle" : false
				                            },
				                            "bigPlayButton" : (videoIndex == this.mainVideoIndex),
				                            "techOrder" : [
				                                'html5', 'flash'
				                            ],
				                            html5: {
				                            	nativeTextTracks: false
				                            }
			                            }, function() {
				                        });
				                    }
				                    videoIndex++;
				                    this.videoJSIds.push(videoDomID);
			                    }
							}.bind(this));
	                	}.bind(this));


	                	if (_.size(this.videoJSIds) > 1) { 
	                		this.manualChangeViewType("pip");
	                		//this.childContainer.addClass("popupcontent-multiple");
	                	} else {
	                		this.childContainer.removeClass("popupcontent-multiple");
	                	}

	                	_.each(this.videoJSIds, function(videoDomID, index) {
							this.videoJS[index] = videojs(videoDomID);
							this.videoJSReady[index] = false;
	                    	this.videoJS[index].ready(this.bindStartVideoEvents.bind(this, videoDomID, index));
	                	}.bind(this));

	                	this.renderMultiVideoMenu();

	                    mod.videoJS = this.videoJS;
	                }

	                app.module("ui").refresh(this.$el);

	                this.onScreenStatus = this.$el.isOnScreen(1, 0);
	                this.onScreenTime = Date.now();

	                //this.onScreenInterval = window.setInterval(this.checkViewType.bind(this), this.onScreelThreshold);
	            }
	        },
	        renderMultiVideoMenu : function() {
	        	if (_.size(this.videoJS) > 1) {
	        		var menuDropdown = this.$(".change-view-type-dropdown");
	        		menuDropdown.removeClass("hidden");
	        		menuDropdown.find(".dynamic-view-item").remove();

	        		_.each(this.videoJS, function(video, index) {
	        			menuDropdown
	        				.find(".dropdown-menu")
	        				.append(
	        					this.menuDropdownItemTemplate({
		        					index : index
		        				})
		        			);
	        		}.bind(this));
	        	} else {
	        		this.$(".change-view-type-dropdown").addClass("hidden");
	        	}
	        },
	        changeViewType : function(evt) {
	        	var target = $(evt.currentTarget);
	        	var type = target.data("view-type");

	        	this.childContainer.removeClass("popupcontent-sbs");
	        	this.childContainer.removeClass("popupcontent-pip");
	        	this.childContainer.removeClass("popupcontent-only");

	        	this.childContainer.addClass("popupcontent-" + type);

	        	if (type == "pip") {
	        		this.addSecVideoDraggable();
	        	} else {
	        		this.removeSecVideoDraggable();
	        	}

	        	if (type == "only") {
	        		var videoIndex = target.data("view-index");
	        		_.each(this.videoJS, function(video, index) {
	        			if (videoIndex == index) {
	        				$(video.el()).removeClass("hidden");
	        				// ADD CONTROLS
	        				video.controls(true);
	        			} else {
	        				$(video.el()).addClass("hidden");
	        			}
	        		});
	        	} else {
	        		_.each(this.videoJS, function(video, index) {
	        			$(video.el()).removeClass("hidden");
	        			// REMOVE CONTROLS FROM SEC VIDEOS
	        			if (index != this.mainVideoIndex) {
	        				video.controls(false);
	        			}
	        		}.bind(this));
	        	}



	        	this.$(".view-type").html(target.html());
	        },
	        manualChangeViewType : function(type) {
	        	this.$("a.change-view-type[data-view-type='" + type + "']").click();
	        },
	        bindStartVideoEvents : function(videoDomID, index) {
	            var self = this;

	            this.currentProgress = parseFloat(this.model.get("progress.factor"));

	            if (_.isNaN(this.currentProgress)) {
	                this.currentProgress = 0;
	            }

            	var progress = this.model.get("progress");
				this.videoJS[index].on("loadedmetadata", function() {
                    if (progress && progress.factor < 1) {
                    	var start = this.duration() * progress.factor;

                    	this.currentTime(start - 5); 
                    }
				});

				this.videoJS[index].on("loadeddata", function(index) {
		            this.videoJSReady[index] = true;

		            if (_.every(this.videoJSReady)) {
		            	_.each(this.videoJS, function(video) {
		            		
							video.textTrackSettings.setValues({
  								backgroundOpacity : "0.5"
							});

		            		video.play();
		            	});
		            	
		            }
				}.bind(this, index));


				this.videoJS[index].volume(0.5);

				if (index == this.mainVideoIndex) {

					this.videoJS[index].on("pause", function(videoIndex) {
			        	_.each(this.videoJS, function(video, index) {
			        		if (index == videoIndex) {
			        			return;
			        		}
			        		video.pause();
			        	}.bind(this));

					}.bind(this, index));

					this.videoJS[index].on("play", function(videoIndex) {
			        	_.each(this.videoJS, function(video, index) {
			        		if (index == videoIndex) {
			        			return;
			        		}
			        		video.play();
			        	}.bind(this));

						this.syncVideos();
					}.bind(this, index));

                	this.videoJS[index].on("timeupdate", this.updateProgress.bind(this));

	                this.videoJS[index].on("ended", function() {
	                    this.currentProgress = 1;
	                    var model = this.model.get("videos").first();
	                    var progressModel = new mod.models.content_progress(model.get("progress"));
	                    progressModel.setAsViewed(model, this.currentProgress);

	                    this.trigger("video:viewed");

	                    this.startRatingView();
	                }.bind(this));
	            } else {
	            	this.videoJS[index].muted(true); // mute the volume
	            }
	            /*
	            if (screenfull.enabled) {
					$(document).on(screenfull.raw.fullscreenchange, function() {
						var popup = this.$('.popupcontent');
						if (_.isNull(screenfull.element)) {
    						popup.removeClass("popupcontent-fullscreen");
  						} else {
    						popup.addClass("popupcontent-fullscreen");
  						}
  					}.bind(this));
  				}
  				*/

				//this.videoJS[index].play();
                // SETTING VOLUME
	        },
	        startRatingView : function() {

	        	if (this.$(".popupcontent").hasClass("popup-minimized")) {
	        		this.minimize(); // "UNMINIMIZE WINDOW"
	        		this.$(".minimize-action").addClass("hidden");
	        	}
	        	this.manualChangeViewType("pip");

	        	var videosCollection = this.model.get("videos");

	        	videoModel = videosCollection.first();
	        	
				var ratingView = new unitVideoTabItemRatingViewClass({
					//el : this.$(".popupcontent-body"),
					model : videoModel
				});
				// HIDE ALL VIDEOS
				/*
        		_.each(this.videoJS, function(video, index) {
       				$(video.el()).addClass("hidden");
       			});
       			*/
       			this.listenToOnce(ratingView, "rating:updated", function() {
       				setTimeout(
       					this.stopAndClose.bind(this),
       					1500
       				);
       			}.bind(this));

				this.$(".popupcontent-body").block({
					message: ratingView.render().$el.html(),
					//baseZ: options.zIndex ? options.zIndex : 1000,
					centerY: true,
					css: {
						width: '50%', 
						top: '10%',
						border: '0',
						padding: '0',
						backgroundColor: '#fff'
					},
					overlayCSS: {
						backgroundColor: '#555',
						opacity: 0.8,
						cursor: 'wait'
					}
				});

				this.ratingShown = true;

				ratingView.bindEvents();

	        },
	        syncVideos : function() {
	        	if (!this.videoJS[this.mainVideoIndex].paused()) {
	        		var currentTime = this.videoJS[this.mainVideoIndex].currentTime();

		        	_.each(this.videoJS, function(video, index) {
		        		if (index == this.mainVideoIndex) {
		        			return;
		        		}

		        		if ((video.currentTime() - currentTime) > 1 || (video.currentTime() - currentTime) < -1) {
		        			video.currentTime(currentTime);
		        		}
		        	}.bind(this));

					requestAnimationFrame(this.syncVideos.bind(this));
				}
	        },
	        updateProgress : function() {
                var currentProgress = this.videoJS[this.mainVideoIndex].currentTime() / this.videoJS[this.mainVideoIndex].duration();

                if (currentProgress > this.currentProgress) {
                    var progressDiff =  currentProgress - this.currentProgress;
                    if (progressDiff > 0.03 ) {
                        this.currentProgress = currentProgress;
                        //this.videoModel.set("progress", this.currentProgress);
                        var model = this.model.get("videos").first();
                        var progressModel = new mod.models.content_progress(model.get("progress"));
                        progressModel.setAsViewed(model, this.currentProgress);
                    }
                }
    		},
	        minimize : function() {
	        	// RESIZE TO MINIMUM VALUE
				this.$(".popupcontent").toggleClass("popup-minimized");
				if (this.$(".popupcontent").hasClass("popup-minimized")) {
					this.$(".popupcontent .minimize-action .fa")
						.removeClass("fa-compress")
						.addClass("fa-expand");

					this.$(".popupcontent").draggable("destroy");
				} else {
					this.$(".popupcontent .minimize-action .fa")
						.removeClass("fa-expand")
						.addClass("fa-compress");

					this.$(".popupcontent").removeAttr("style");
					this.makeDraggable();
				}
	        },
    		fullscreen : function() {
    			/*
				if (!_.isNull(this.videoJS[this.mainVideoIndex])) {
					this.videoJS[this.mainVideoIndex].requestFullscreen();	
				}
				*/
				var popup = $('.popupcontent');
				//popup.addClass("popupcontent-fullscreen");
				//screenfull.request(popup[0]);

				if (popup.hasClass("popupcontent-fullscreen")) {
					popup.removeClass("popupcontent-fullscreen");
					$('body').removeClass("hide-overflow");
				} else {
					popup.addClass("popupcontent-fullscreen");
					$('body').addClass("hide-overflow");
				}
				
    		},
	        stopAndClose : function() {
                if (!_.isEmpty(this.videoJS)) {
                	this.updateProgress();

                	_.each(this.videoJS, function(video) {
                    	video.dispose();
                	});

                	this.videoJS = [];
                	this.videoJSIds = [];
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

	    var unitVideoTabItemRatingViewClass = baseChangeModelViewClass.extend({
	        className : 'rating-view',
	        template : _.template($("#tab_unit_video-rating-view").html(), null, {variable: "model"}).bind(this),
	        childContainerSelector : false,
	        childContainer : false,
	        initialize: function(opt) {
	            console.info('portlet.content/unitVideoTabItemRatingViewClass::initialize');

				if (_.has(opt, 'childContainer')) {
					this.childContainerSelector = opt.childContainer;
				} else {
					this.childContainerSelector = false;
				}
	        },
	        render : function(e) {
	            console.info('portlet.content/unitVideoTabItemRatingViewClass::render');
	            var self = this;

				if (this.childContainerSelector) {
					this.childContainer = this.$(this.childContainerSelector);
				} else {
					this.childContainer = this.$el;
				}

				this.childContainer.empty();

                this.childContainer.append(
                    this.template(this.model.toJSON())
                );

                return this;
	        },
	        unbindEvents : function() {
	        	$("body").off("change", ":input[name='content-rating']");
	        	$("body").off("mouseenter", ".rating-view label.full");
	        },
	        bindEvents : function() {
	        	$("body").on("change", ":input[name='content-rating']", this.updateRating.bind(this));

	        	$("body").on("mouseenter", ".rating-view label.full", this.updateText.bind(this));
	        },
	        updateText : function(e) {
				var label = $(e.currentTarget);

				$(".rating-view .rating-text").html(label.attr("title"));
	        },
	        updateRating : function(e) {
	        	this.unbindEvents();

	        	$('.rating-view .rating-stars-container').addClass("hidden");
	        	$('.rating-view .rating-stars-text-container').addClass("hidden");
	        	$('.rating-view .rating-stars-loader').removeClass("hidden");


	        	var rating = $(e.currentTarget).val();

				var progressModel = new mod.models.content_progress(this.model.get("progress"));
                progressModel.setRating(this.model, rating);

                this.listenToOnce(progressModel, "sync", function() {
                	// SHOW SUCCESS MESSAGE, AND TRIGGERS WINDOW CLOSE
                	$(".rating-stars-loader").addClass("hidden");
                	$(".rating-stars-message").removeClass("hidden");
	                this.trigger("rating:updated");
                });

	        }
	    });
		/*
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
			render : function(e) {
				var progress = mod.progressCollection.getContentProgress(this.model.get("id"));
				this.model.set("progress", progress);

				this.$el.html(
					this.template(this.model.toJSON())
				);
				return this;
			}
	    });
	    */
	    /*
	    var unitMaterialsTabViewClass = baseChildTabViewClass.extend({
	        nofoundTemplate : _.template($("#tab_unit_materials-nofound-template").html()),
	        childViewClass : unitMaterialsTabViewItemClass,
	        events : {
	        	"click .popupcontent-header a.minimize-action" : "minimize",
	        	"click .popupcontent-header a.close-action" : "stopAndClose"
	        },
	        initialize : function() { 
	        	baseChildTabViewClass.prototype.initialize.apply(this, arguments);

				this.unitDropdownView = new entityDropdownViewClass({
					el : this.$(".unit-ex-dropdown")
				});

				this.unitDropdownView.setCollection(mod.programsCollection.getCurrentUnits());

				this.listenTo(this.unitDropdownView, "dropdown-item.selected", function(unitModel) {
					this.setModel(unitModel);
					this.render();
				}.bind(this));

				this.listenTo(mod.programsCollection, "course.changed", function() {
					this.unitDropdownView.setCollection(mod.programsCollection.getCurrentUnits());
				}.bind(this));

	        },
	        makeCollection: function() {
	            var materials = this.model.get("materials");
	            return materials;
	        },
	        render : function() {
	        	baseChildTabViewClass.prototype.render.apply(this, arguments);
	        	contentPopoutViewClass.prototype.makeDraggable.apply(this, arguments);
	        	contentPopoutViewClass.prototype.enableView.apply(this, arguments);
	        },
	        minimize : function() {
	        	// RESIZE TO MINIMUM VALUE
				//this.$(".popupcontent").toggleClass("popup-minimized");
				contentPopoutViewClass.prototype.minimize.apply(this, arguments);
	        },
	        stopAndClose : function() {
                //this.$el.hide();
                contentPopoutViewClass.prototype.disableView.apply(this, arguments);
	        },
	    });
	    */
	
		this.widgetViewClass = parent.widgetViewClass.extend({
			activeTab : null,
			activeView : null,
	    	events : function() {
	    		return {
		    		"click .content-widget-search-action" : "showSearch",
		    		"blur .search-container input" : "hideSearch",
		    		"keyup .search-container input" : "doSearch",
		    	};
    		},
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

				this.renderTabs();

				//this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));
			},
			showSearch : function() {
				// GET THE CURRENT ACTIVE TAB
				//content-widget
				if (!_.isNull(this.activeView)) {
					// OPEN SEARCH INPUT
					this.$(".content-widget-search-action").fadeOut(500, function() {
						this.$(".search-container").fadeIn(500);
						this.$(".search-container input").focus();
					}.bind(this));
				}
			},
			hideSearch : function() {
				this.$(".search-container").fadeOut(500, function() {
					this.$(".content-widget-search-action").fadeIn(500);
				}.bind(this));
			},
			doSearch : function(e) {
				if (!_.isNull(this.activeView)) {
					this.activeView.doSearch(e, $(e.currentTarget).val());
				}
			},
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
			renderTabs : function() {
				this.$(".widget-tabs-container a[data-toggle='tab']")
					.on('shown.bs.tab', function (e) {
						var current_tab = $(e.target).data('settingUpdate');

						this.model.set("content_current_tab", current_tab);

						if (current_tab == "course") {
							this.programCoursesTabView.onVisible();
						} else if (current_tab == "unit") {
							this.courseUnitsTabView.onVisible();
						}

						this.activeTab = current_tab;
						this.onTabChange();
						

						//this.model.save();
  						//e.target // newly activated tab
  						//e.relatedTarget // previous active tab
					}.bind(this));

				var current_tab = this.model.get("content_current_tab");
				this.$(".widget-tabs-container a[data-toggle='tab'][data-setting-update='" + current_tab + "']")
					.tab('show');

				this.activeTab = current_tab;

				this.onTabChange();
			},
			onTabChange : function() {
				if (this.activeTab == "program") {
					this.activeView = null;
					this.$(".content-widget-search-action-container").hide();

				} else if (this.activeTab == "course") {
					this.activeView = this.programCoursesTabView;
					this.$(".content-widget-search-action-container").show();
				} else if (this.activeTab == "unit") {
					// ACTIVE TAB IS MESSAGE, SEARCH ON HIS DATATABLE
					this.activeView = this.courseUnitsTabView;
					this.$(".content-widget-search-action-container").show();
				} else {
					this.activeView = null;
				}
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

					this.programDropdownView = new entityDropdownViewClass({
						el : this.$(".program-dropdown")
					});

					this.courseDropdownView = new entityDropdownViewClass({
						el : this.$(".course-dropdown")
					});
					this.unitDropdownView = new entityDropdownViewClass({
						el : this.$(".unit-dropdown")
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
		                childContainer : ".popupcontent-body",
		                // model : this.model,
		            });
		            // MATERIALS
		            /*
		            this.unitMaterialsTabView   = new unitMaterialsTabViewClass({
		                el : this.$("#unit-material-container"),
		                childContainer : "table.unit-material-table tbody",
		                // model : this.model,
		            });
		            */

					this.programDescriptionTabView.render();
					this.programCoursesTabView.render();
					this.courseUnitsTabView.render();


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
		/*
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
		*/
	});
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
			getVideos : function() {
				if (this.get('videos')) {
					return this.get('videos');
				}
				
				var contents = new mod.collections.contents(this.get("contents"));

				this.set('videos', new mod.collections.contents(contents.getVideos()));
				/*
				var videos = contents.filter(function(model, index) {
					return model.isVideo();
				});

				*/
				//this.set('video', contents.getMainVideo(_.first(videos)));

				return this.get('videos');
			},
			getMaterials : function() {
				if (this.get('materials')) {
					return this.get('materials');
				}

				var contents = new mod.collections.contents(this.get("contents"));

				this.set('materials', new mod.collections.contents(contents.getMaterials()));
				/*
				var filteredCollection = _.filter(this.get("contents"), function(model, index) {
					var object = new mod.models.content(model);
					return object.isMaterial();
				});

				this.set('materials', new mod.collections.contents(filteredCollection));
				*/
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
			getTestExecutions : function() {
				if (_.has(this.contents, 'executions')) {
					return this.contents['executions'];
				}

				if (this.get("type") == "test") {
					var executions = this.get("test.executions");
					return new mod.collections.executions(executions);
				} else {
					return new mod.collections.executions();
				}
			},
			getTestExecution : function() {
				var executions = this.getTestExecutions();

				return executions.at(-1);
			}
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
			},
			setRating : function(model, factor) {
				if (_.isUndefined(factor)) {
					factor = -1;
				}
				this.set("content_id", model.get("id"));
				this.set("rating", factor);
				this.save();

				mod.progressCollection.fetch();

				model.set("progress", this.toJSON());
			},
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
				if (courses) {
					var total = courses.reduce(function(count, item, i) {
						var units = item.getUnits();
						if (units) {
							return count + units.size();
						}
						return count;
					}, 0);

					return total;
				}
				return 0;
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
		}),
		execution : contentBaseModel.extend({})
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
				if (this.courses) {
					var course = this.courses.findWhere({id : this.current.course_id});
					return course;
				}
				return null;
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
				if (this.getCurrentCourses()) {
					return this.getCurrentCourses().indexOf(
  						this.getCurrentCourse()
					);
				}
				return -1;
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
					if (program) {
						this.courses = new mod.collections.courses(program.get("courses"));

						this.listenTo(this.courses, "previous", this.toPreviousCourseIndex.bind(this));
						this.listenTo(this.courses, "next", this.toNextCourseIndex.bind(this));
					}
				}
				
				return this.courses;
			},
			getCurrentUnits : function() {
				if (_.isNull(this.units)) {
					var course = this.getCurrentCourse();
					if (course) {
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
		contents : app.module("models").content().collection,
		executions : navigableCollection.extend({
			model : this.models.execution,
		}),
	};
	mod.programsCollection = null;
	mod.progressCollection = null;

	this.listenTo(app, "settings.sysclass", function() {
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
	var fullUnitModelClass = Backbone.DeepModel.extend({
		urlRoot : "/module/roadmap/item/units"
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
