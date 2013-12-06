$SC.module("portlet.courses", function(mod, MyApp, Backbone, Marionette, $, _) {
	// MODELS
	this.courseID = null;
	this.lessonID = null;

	mod.addInitializer(function() {
		var coursesCollectionClass = Backbone.Collection.extend({
			url : "/module/courses/list",
			parse : function(response) {
				console.log(response);
				for (i in response) {
					response[i].lessons = new Backbone.Collection(response[i].lessons);
				}
				return response;
			}
		});

		mod.coursesCollection = new coursesCollectionClass;
/*
		mod.coursesCollection.add({
			id: 1, 
			name: "Curso 1",
			lessons: new Backbone.Collection([
				{id: 1, name: "Lição 1"},
				{id: 2, name: "Lição 2"},
				{id: 3, name: "Lição 3"},
				{id: 4, name: "Lição 4"}
			])
		});
		mod.coursesCollection.add({
			id: 2, 
			name: "Curso 2",
			lessons: new Backbone.Collection([
				{id: 1, name: "Lição 1"},
				{id: 2, name: "Lição 2"},
				{id: 3, name: "Lição 3"},
				{id: 4, name: "Lição 4"}
			])
		});
		mod.coursesCollection.add({
			id: 3, 
			name: "Curso 3",
			lessons: new Backbone.Collection([
				{id: 1, name: "Lição 1"},
				{id: 2, name: "Lição 2"},
				{id: 3, name: "Lição 3"},
				{id: 4, name: "Lição 4"}
			])
		});
		mod.coursesCollection.add({
			id: 4, 
			name: "Curso 4",
			lessons: new Backbone.Collection([
				{id: 1, name: "Lição 1"},
				{id: 2, name: "Lição 2"},
				{id: 3, name: "Lição 3"},
				{id: 4, name: "Lição 4"}
			])
		});
		mod.coursesCollection.add({
			id: 5, 
			name: "Curso 5",
			lessons: new Backbone.Collection([
				{id: 1, name: "Lição 1"},
				{id: 2, name: "Lição 2"},
				{id: 3, name: "Lição 3"},
				{id: 4, name: "Lição 4"}
			])
		});
*/
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
                this.collection.fetch();

                //this.render(this.collection);
				this.$el.hide();
		    },
		    reload : function(viewMode) {
		    	if (viewMode == undefined || (viewMode != 'course' && viewMode != 'lesson') || mod.courseID == null) {
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
		    			mod.courseID = model.get("id");
		    			this.openLessonViewMode();
		    		}
		    	}
		    	this.$el.slideDown(500);
		    },
		    openLessonViewMode : function() {
				var model = this.collection.get(mod.courseID);
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
					mod.courseID = $(e.currentTarget).data("entity-id");
					this.openLessonViewMode();
				} else if (this.viewMode == 'lesson') {
					var model = this.collection.get(mod.courseID);
					var lessonCollection = model.get("lessons");

					mod.lessonID = $(e.currentTarget).data("entity-id");
					var lessonModel = lessonCollection.get(mod.lessonID);

					this.portlet.find("#lessons-title").html(lessonModel.get("name"));
					
					this.$el.fadeOut(500, function() {
						mod.contentActionView.reload();
					});
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

		var contentActionViewClass = Backbone.View.extend({
			el: $('#courses-content'),
		    portlet: $('#courses-widget'),
		    reload : function() {
			 	console.log(mod.courseID);
				console.log(mod.lessonID);
		    	this.$el.show();
		    },

		});

		this.onFilter = function(e, portlet) {
			// INJECT
			this.contentActionView.$el.slideUp(500);
			if ($(e.currentTarget).attr("id") == "lessons-title") {
				this.filterActionView.reload("lesson");
			} else {
				this.filterActionView.reload();
			}
		};

		this.onSearch = function(e, portlet) {
			// INJECT
			this.contentActionView.$el.hide();
			this.filterActionView.reload();
		};
		this.onFullscreen = function(e, portlet) {
			this.filterActionView.portlet.find(".scroller, .slimScrollDiv").css({
				'height': 720
			});
			this.contentActionView.portlet.find(".scroller, .slimScrollDiv").css({
				'height': 720
			});

		};
		this.onRestorescreen = function(e, portlet) {
			this.filterActionView.portlet.find(".scroller, .slimScrollDiv").css({
				'height': 238
			});
			this.contentActionView.portlet.find(".scroller, .slimScrollDiv").css({
				'height': 238
			});
		};

		this.contentActionView = new contentActionViewClass({collection : mod.coursesCollection});
		this.filterActionView = new filterActionViewClass({collection : mod.coursesCollection});
	});

});