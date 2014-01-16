$SC.module("models.news", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		this.itemsCollection = new Backbone.Collection;
		this.itemsCollection.url = "/module/news/items/me";
	});
});