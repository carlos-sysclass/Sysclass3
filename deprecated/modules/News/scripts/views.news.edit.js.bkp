$SC.module("views.news.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var NEWS_ID = views_news_edit.id;

		var newsModelClass = $SC.module("models.news").newsModelClass;
		var newsModel = new newsModelClass();

		if (typeof views_news_edit != 'undefined') {
			if (typeof views_news_edit.id != 'undefined') {
				var NEWS_ID = views_news_edit.id;
				newsModel.set("id", NEWS_ID);

				var baseFormClass = app.module("views").baseFormClass;
				var newsFormView = new baseFormClass({el : "#form-news", model: newsModel});

				newsModel.fetch();

				// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
				app.module("dialog.permission").on("before:save", function(model) {
					// SET MODEL PROPERTIES 
					//console.log(model);
					model.set("entity", {
						'type' 		: 'news',
						'entity_id'	: newsModel.get("id")
					});
					return true;
				});

				//app.module("dialog.permission").setCollectionParam({data : });
				app.module("dialog.permission").start({type : 'news', 'entity_id' : NEWS_ID});

				//$SC.module("dialog.permission").conditionCollection.fetch();
			}
		}

		// EXPORTS
		this.newsModel = newsModel;
	});
});