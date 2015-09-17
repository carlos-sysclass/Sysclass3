$SC.module("views.roles.view", function(mod, app, Backbone, Marionette, $, _) {

	mod.addInitializer(function() {
		app.module("crud.views.edit").on("start", function() {
			var viewModule = this;

			this.tableView.listenTo(app.module("dialogs.roles.create").dialogView, "hide.dialog", this.tableView.refreshTable);

			this.listenTo(this.tableView, "action.datatable", function(data, item) {
				console.warn(data);
				var itemModelClass = app.module("crud.models").itemModelClass;
				var model = new itemModelClass(data);
				app.module("dialogs.roles.create").dialogView.setModel(model);
				app.module("dialogs.roles.create").dialogView.open();




			}.bind(this));
		});
	});
});
