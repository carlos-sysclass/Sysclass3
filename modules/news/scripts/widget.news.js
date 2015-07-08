$SC.module("portlet.news", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		// VIEWS
		var parent = app.module("portlet");

		var newsBlockViewItemClass = parent.blockViewItemClass.extend({
			events: {
			  "click a.list-group-item": "viewDetails"
			},
			tagName : "div",
			template : _.template($("#news-item-template").html(), null, {variable: "model"}),
			viewDetails : function(e) {

				var dialogId = $(e.currentTarget).data('target');
			 	$(dialogId).find(".news-title").html(this.model.get('title'));
				$(dialogId).find(".news-data").html(this.model.get('data'));
			}
		});

		var newsBlockViewClass = parent.blockViewClass.extend({
			nofoundTemplate : _.template($("#news-nofound-template").html()),
			childViewClass : newsBlockViewItemClass
		});

		this.newsWidgetViewClass = parent.widgetViewClass.extend({
			collectionClass : mod.collections.news,
			blockViewClass : newsBlockViewClass,
			onBeforeStart : function() {

			},
			onStart : function() {

			},
			onBeforeFullScreen : function() {
				/// RETURN FALSE TO DISABLE
				return true;
			},
			onFullScreen : function() {
				this.$(".scroller").slimScroll({destroy: true});
				this.$(".scroller").css("height", "auto");

			},
			onBeforeRestoreScreen : function() {
				/// RETURN FALSE TO DISABLE
				return true;
			},
			onRestoreScreen : function() {
				app.module("ui").handleScrollers(this.$el);
			}
		});
	});

	this.models = {
		news : Backbone.DeepModel.extend({
			urlRoot : "/module/news/item/me"
		})
	};
	this.collections = {
		news : Backbone.Collection.extend({
			url : "/module/news/items/me",
			model : this.models.news
		})
	};

	mod.on("start", function() {
		//var userSettingsModel = new userSettingsModelClass();

		this.listenToOnce(app.userSettings, "sync", function(model, data, options) {
			this.newsWidgetView = new this.newsWidgetViewClass({
				model : app.userSettings,
				el: '#news-widget'
			});

		}.bind(this));
	});


});
