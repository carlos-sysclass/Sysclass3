$SC.module("models.institution", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		this.itemModelClass = Backbone.Model.extend({
			urlRoot : "/module/institution/item/me"
		});
		/*
		this.itemsCollection = new Backbone.Collection({
			model : this.newsModelClass
		});
		this.itemsCollection.url = "/module/news/items/me";
		*/
	});
});