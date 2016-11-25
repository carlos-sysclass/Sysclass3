$SC.module("views.form.users", function(mod, app, Backbone, Marionette, $, _) {

	mod.startWithParent = false;

	mod.on("start", function(opt) {
		console.warn("FORM", opt.module.getForm());

		var form = opt.module.getForm();
		form.renderType = "byView";
		form.render();

	});
    $SC.module("crud.views.edit").on("start", function() {
        mod.start({
            module: this
        });
    });
    $SC.module("crud.views.edit").on("start", function() {
        mod.start({
            module: this
        });
    });

});
