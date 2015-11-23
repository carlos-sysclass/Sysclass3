$SC.module("views.users.view", function(mod, app, Backbone, Marionette, $, _) {

    mod.on("start", function(opt) {


    	this.models = {
    		users : Backbone.DeepModel.extend({
    			urlRoot : "/module/users/item/me"
    		})
    	};

		app.on("added.table", function(name, table) {
			if (name == "view-users") {
				this.bindTableEvents(table);
			}
		}.bind(this));


        mod.bindTableEvents = function(table) {
        	/*
			this.listenTo(this.collection, "sync", function() {
				table.redraw();
			});
        	this.listenTo(table, "draw.datatables", function(row, data) {
        		//console.warn('DRAW', row, data);
				var exists = this.collection.findWhere({resource_id: data['id']});

				var innerInput = $(row).find(".datatable-option-switch");

				if (typeof exists != "undefined") {
					innerInput.bootstrapSwitch('state', true, true);
				} else {
					innerInput.bootstrapSwitch('state', false, true);
				}
			}.bind(this));
			*/

			this.listenTo(table, "action.datatables", function(el, data, action) {
				if (action == "aprove") {
					var user = new mod.models.users(data);
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
