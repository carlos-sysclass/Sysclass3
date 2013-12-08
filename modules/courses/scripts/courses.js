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
				this.on("change:course_id", function() {
					this.set({lesson_id : 0}, { silent: true });
				}, this);

				this.on("change:lesson_id", function() {
					console.log("FETCHING CONTENT");
					this.fetch();
				}, this);
		    },
		    defaults : {
			    course_id : 0,
			    lesson_id : 0
		    },
		    urlRoot : function() {
		    	return "/module/courses/content/" + this.get("course_id") + "/" + this.get("lesson_id");
		    }
			// 31/106
		});

		mod.contentModel = new contentModelClass();
		//alert(mod.contentModel.url());

	  	// VIEWS
	  	var filterActionViewClass = Backbone.View.extend({
		    el: $('#courses-list'),
		    portlet: $('#courses-widget'),
		    viewMode : "course",

		    itemTemplate: _.template($('#courses-list-item-template').html()),
		    //noDataFoundTemplate: _.template($('#news-nofound-template').html()),

		    events: {
		      "click a.list-group-item": "select"
		    },
		    initialize: function() {
				this.listenTo(this.collection, 'sync', this.render.bind(this));
				this.listenTo(this.collection, 'add', this.addOne.bind(this));
                this.collection.fetch();

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
		    			// DO NOT SET 
		    			mod.contentModel.set({
		    				"course_id" : model.get("id"),
		    				"lesson_id" : 0
		    			}, {silent : true});
		    			this.openLessonViewMode();
		    		}
		    	}
		    	this.$el.slideDown(500);
		    },
		    openLessonViewMode : function() {
				var model = this.collection.get(mod.contentModel.get("course_id"));
				var lessonCollection = model.get("lessons");

				this.portlet.find(".portlet-title > .caption #courses-title").html(model.get("name"));
				this.portlet.find(".portlet-title > .caption #lessons-title").html("Choose...");
				
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

					mod.contentModel.set("course_id", $(e.currentTarget).data("entity-id"));
					this.openLessonViewMode();
				} else if (this.viewMode == 'lesson') {
					var model = this.collection.get(mod.contentModel.get("course_id"));
					var lessonCollection = model.get("lessons");

					mod.contentModel.set("lesson_id", $(e.currentTarget).data("entity-id"));
					var lessonModel = lessonCollection.get(mod.contentModel.get("lesson_id"));

					this.portlet.find("#lessons-title").html(lessonModel.get("name"));
					
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

		var contentViewClass = Backbone.View.extend({
			el: $('#courses-content'),
		    portlet: $('#courses-widget'),
		    template: _.template($('#courses-content-template').html()),
		    initialize: function() {
				this.listenTo(this.model, 'sync', this.render.bind(this));

				this.$el.hide();
		    },
		    render : function() {
		    	console.log(this.model.toJSON());
		    	console.log(this.template(this.model.toJSON()));
				this.$el.empty().show().append(
		    		this.template(this.model.toJSON())
		    	);
		    }
		});
		var userProgressViewClass = Backbone.View.extend({
			el: $('#progress-content'),
		    portlet: $('#courses-widget'),
		    initialize: function() {
				this.listenTo(this.model, 'change:course_id', this.renderCourse.bind(this));
				this.listenTo(this.model, 'change:lesson_id', this.renderLesson.bind(this));
				this.render();
		    },
		    render : function() {
		    	console.log(this.$(".courses,.lessons,.topics"));
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
					var courseStats = courseModel.get("stats");
			    	var percent = courseStats.completed * 100 / courseStats.total_lessons;
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
		    }
		});

		this.onFilter = function(e, portlet) {
			// INJECT
			//this.contentView.$el.hide();
			if ($(e.currentTarget).attr("id") == "lessons-title") {
				this.filterActionView.toggle("lesson");
			} else {
				this.filterActionView.toggle();
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

		this.contentView = new contentViewClass({model : mod.contentModel});
		this.userProgressView = new userProgressViewClass({model : mod.contentModel, collection : mod.coursesCollection});
		this.filterActionView = new filterActionViewClass({collection : mod.coursesCollection});
	});

});