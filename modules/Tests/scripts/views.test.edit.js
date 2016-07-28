$SC.module("views.test.edit", function(mod, app, Backbone, Marionette, $, _) {

	mod.addInitializer(function() {
		app.module("crud.views.edit").on("start", function() {
			var formView = this.getForm();

			formView.renderType = "byView";
		});
	});
});
