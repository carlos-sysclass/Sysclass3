$SC.module("views.eventtypes.edit", function(mod, app, Backbone, Marionette, $, _)
{
	// MODELS
	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;

	mod.addInitializer(function()
	{
		// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
		var eventTypesCollectionClass = Backbone.Collection.extend
		({
			url : "/module/event/types/item/" + entity_id
		});

		var eventTypesCollection = new eventTypesCollectionClass();
		eventTypesCollection.fetch();
	});
});
