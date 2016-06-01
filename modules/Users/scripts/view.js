$SC.module("views.users.view", function(mod, app, Backbone, Marionette, $, _) {
    mod.on("start", function(opt) {

		app.on("added.table", function(name, table) {
			if (name == "view-users") {
				this.bindTableEvents(table);
			}
		}.bind(this));

        mod.bindTableEvents = function(table) {
			this.listenTo(table, "draw.datatables", function(row, data) {
				if (data.pending == "0") {
					$(row).find("[data-datatable-action='aprove']").remove();
				}
			});

			this.listenTo(table, "action.datatables", function(el, data, action) {
				if (action == "aprove") {
					var userClass = app.module("models").users().user;

					var user = new userClass(data);

					//user.set("id", data['id']);
					user.set("pending", 0);
					user.save();

					table
						.oTable
						.api()
	        			.row(el)
	        			.data( user.toJSON() )
	        			.draw();
	        	}
			});
        };
    });
});
