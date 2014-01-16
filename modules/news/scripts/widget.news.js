$SC.module("portlet.news", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		/*
	  	mod.collection = new Backbone.Collection;
		mod.collection.url = "/module/news/data";
		*/


	  	// VIEWS
	  	mod.viewClass = Backbone.View.extend({
		    // Instead of generating a new element, bind to the existing skeleton of
		    // the App already present in the HTML.
		    el: $('#news-links'),
		    portlet: $('#news-widget'),

		    itemTemplate: _.template($('#news-item-template').html()),
		    noDataFoundTemplate: _.template($('#news-nofound-template').html()),

		    events: {
		      "click a.list-group-item": "select"
		    },
		    initialize: function() {
				this.listenTo(mod.collection, 'sync', this.render);
                mod.collection.fetch();
		    },
		    select : function(e) {
		      // Get collection index from id
		      var newsID = $(e.currentTarget).data("news-id");
		      var model = this.collection.get(newsID);

		      this.portlet.find(".news-title").html(model.get('title'));
		      this.portlet.find(".news-data").html(model.get('data'));
		    },
		    // Re-rendering the App just means refreshing the statistics -- the rest
		    // of the app doesn't change.
		    render: function(collection) {
		      this.$el.empty();

		      if (collection.size() == 0) {
		        this.$el.append(this.noDataFoundTemplate());
		      } else {
		        var self = this;
		        collection.each(function(model,i) {
		          self.$el.append(
		            self.itemTemplate(model.toJSON())
		          );
		        });
		      }
		    }
	  	});

		this.view = new mod.viewClass({collection: app.module("models.news").itemsCollection});
		this.searchBy = "title";

		this.onFullscreen = function(e, portlet) {
			this.view.portlet.find("#news-links,.slimScrollDiv").css({
				'height': 720
			});
		};
		this.onRestorescreen = function(e, portlet) {
			this.view.portlet.find("#news-links,.slimScrollDiv").css({
				'height': 200
			});
		};
	});
	


});