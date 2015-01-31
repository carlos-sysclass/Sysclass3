$SC.module("models.users", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		this.userModelClass = Backbone.Model.extend({
			urlRoot : "/module/users/item/me"
		});

        this.userGroupModelClass = Backbone.Model.extend({
            urlRoot : "/module/users/groups/item/me"
        });

        this.itemModelClass = this.userModelClass;
	});

});
