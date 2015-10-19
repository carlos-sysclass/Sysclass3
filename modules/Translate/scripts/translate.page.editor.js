$SC.module("translate.page.editor", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.startWithParent = false;
	mod.addInitializer(function(collectionData) {
		this.models = {
			session_tokens : Backbone.Model.extend({
				urlRoot : "/module/translate/session_tokens"
			})
		};

		session_tokens = new this.models.session_tokens();
		session_tokens.fetch();

		//var conditionCollection = new conditionCollectionClass;
		//var permissionView = new permissionViewClass({el : "#permission-block", collection : conditionCollection});
		//conditionCollection.fetch();
		//permissionView.render();
		//
		// Exports
		//this.conditionCollection = conditionCollection;
		//this.conditionCollection.fetch({data : collectionData});

	});
});