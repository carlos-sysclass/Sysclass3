$SC.module("views.users.edit", function(mod, app, Backbone, Marionette, $, _) {

	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;

    mod.on("start", function(opt) {

    	
    	this.models = {
    		enroll : Backbone.DeepModel.extend({
    			urlRoot : "/module/enroll/item/me"
    		})
    	};

		app.on("added.table", function(name, table) {
			console.warn(name, table)
			if (name == "view-enroll") {

				table
					.putVar('user_id', entity_id)
					.setUrl("/module/enroll/items/me/datatable/" + JSON.stringify({
						user_id : entity_id
					}) + "?block");

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

			this.listenTo(table, "switchItem.datatables", function(el, data, checked) {
				console.warn(el, data);

				var enroll = new mod.models.enroll();
				enroll.set("user_id", entity_id);
				enroll.set("course_id", data['id']);
				if (checked) {
					enroll.save();
				} else {
					enroll.destroy();
				}

			});
        };
    });
});
