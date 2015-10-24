$SC.module("views.news.add", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var newsModelClass = $SC.module("models.news").newsModelClass;
		var newsModel = new newsModelClass();

		var baseFormClass = app.module("views").baseFormClass;
		var newsFormView = new baseFormClass({el : "#form-news", model: newsModel});

		// EXPORTS
		this.newsModel = newsModel;
	});
});