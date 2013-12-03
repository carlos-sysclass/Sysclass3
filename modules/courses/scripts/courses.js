$SC.module("portlet.courses", function(mod, MyApp, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
	  	mod.coursesCollection = new Backbone.Collection;
		//mod.collection.url = "/module/courses/";
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

	  	// VIEWS
	  	var filterActionViewClass = Backbone.View.extend({
		    // Instead of generating a new element, bind to the existing skeleton of
		    // the App already present in the HTML.
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
				//this.listenTo(this.collection, 'sync', this.render.bind(this));
				//this.listenTo(this.collection, 'add', this.addOne.bind(this));
                //mod.collection.fetch();
                this.render(this.collection);
				this.$el.hide();
		    },
		    reload : function() {
		    	this.viewMode = "course";
				this.render(this.collection);
		    	this.$el.slideDown(500);
		    	this.portlet.find(".portlet-title > .caption #courses-title").html("Choose...");
		    	this.portlet.find(".portlet-title > .caption #lessons-title").html("");
		    },
		    select : function(e) {
				// Get collection index from id
				if (this.viewMode == 'course') {
					this.courseID = $(e.currentTarget).data("entity-id");
					var model = this.collection.get(this.courseID);
					var lessonCollection = model.get("lessons");

					this.portlet.find(".portlet-title > .caption #courses-title").html(model.get("name"));
					this.portlet.find(".portlet-title > .caption #lessons-title").html("Choose...");
					
					this.viewMode = "lesson";
					var self = this;
					this.$el.fadeOut(500, function() {
						self.render(lessonCollection);
						self.$el.fadeIn(500);
					});
					
				} else if (this.viewMode == 'lesson') {
					var model = this.collection.get(this.courseID);
					var lessonCollection = model.get("lessons");

					this.lessonID = $(e.currentTarget).data("entity-id");
					var lessonModel = lessonCollection.get(this.lessonID);

					this.portlet.find(".portlet-title > .caption #lessons-title").html(lessonModel.get("name"));
					
					this.$el.fadeOut(500, function() {
						mod.contentActionView.$el.show();
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
		});

		this.onFilter = function(e, portlet) {
			// INJECT
			this.contentActionView.$el.slideUp(500);
			this.filterActionView.reload();
		};
		this.onSearch = function(e, portlet) {
			// INJECT
			this.contentActionView.$el.hide();
			this.filterActionView.reload();
		};
		this.onFullscreen = function(e, portlet) {
			/*
			this.view.portlet.find("#news-links, .slimScrollDiv").css({
				'height': 720
			});
			*/
		};
		this.onRestorescreen = function(e, portlet) {
			/*
			this.view.portlet.find("#news-links,.slimScrollDiv").css({
				'height': 200
			});
			*/
		};

		this.contentActionView = new contentActionViewClass({collection : mod.coursesCollection});
		this.filterActionView = new filterActionViewClass({collection : mod.coursesCollection});
	});

});