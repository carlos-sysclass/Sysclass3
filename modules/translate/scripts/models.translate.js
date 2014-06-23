$SC.module("models.translate", function(mod, app, Backbone, Marionette, $, _) {
	mod.addInitializer(function(collectionData) {

		this.translateLanguageModelClass = Backbone.Model.extend({
			urlRoot : "/module/translate/change/"
		});


		this.translateEditTokenModelClass = Backbone.Model.extend({
			urlRoot : "/module/translate/item/token"
		});


	});
});