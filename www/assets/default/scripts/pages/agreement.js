$SC.module("view.agreement", function(mod, app, Backbone, Marionette, $, _){
	this.startWithParent = false;

	this.on("start", function(opt) {
		var itemModelClass = Backbone.Model.extend({
			urlRoot : "/module/users/item/agreement"
		});

		var itemModel = new itemModelClass();
		itemModel.set("id", opt.user_id);
		//itemModel.fetch();

		var baseFormClass = app.module("views").baseFormClass;
		mod.formView = new baseFormClass({el : "#form-agreement", model: itemModel});

		this.listenTo(mod.formView, "complete:save", function() {
			if (itemModel.get("viewed_license") == 1) {
			}
		})
	});

});
