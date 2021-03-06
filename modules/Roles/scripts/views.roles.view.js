$SC.module("views.roles.view", function(mod, app, Backbone, Marionette, $, _) {

	mod.addInitializer(function() {
		app.module("crud.views.edit").on("start", function() {
			var viewModule = this;
			this.tableView.listenTo(app.module("dialogs.roles.create").dialogView, "hide.dialog", this.tableView.refresh);

			this.listenTo(this.tableView, "action.datatable", function(data, item) {

				if ($(item).hasClass("datatable-option-edit")) {
					var itemModelClass = app.module("crud.models").itemModelClass;
					var model = new itemModelClass(data);
					app.module("dialogs.roles.create").dialogView.setModel(model);
					app.module("dialogs.roles.create").dialogView.open();
				} else if ($(item).hasClass("datatable-option-users")) {
					var itemModelClass = app.module("crud.models").itemModelClass;
					var model = new itemModelClass(data);
					app.module("dialogs.roles.users").dialogView.setModel(model);
					app.module("dialogs.roles.users").dialogView.open();

				} else if ($(item).hasClass("datatable-option-permission")) {
					var itemModelClass = app.module("crud.models").itemModelClass;
					var model = new itemModelClass(data);
					app.module("dialogs.roles.resources").dialogView.setModel(model);
					app.module("dialogs.roles.resources").dialogView.open();
				}
			}.bind(this));
		});
	});
});
