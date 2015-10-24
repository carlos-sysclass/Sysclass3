$SC.module("models.news", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		this.newsModelClass = Backbone.Model.extend({
			urlRoot : "/module/news/item/me"
		});

		this.itemsCollection = new Backbone.Collection({
			model : this.newsModelClass
		});
		this.itemsCollection.url = "/module/news/items/me";
	});
});