$SC.module("views.events.edit", function(mod, app, Backbone, Marionette, $, _)
{
	// MODELS
	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;
	
	mod.addInitializer(function()
	{
		// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
		var eventsCollectionClass = Backbone.Collection.extend
		({
			url : "/module/events/item/" + entity_id
		});

		var eventsCollection = new eventsCollectionClass();
		eventsCollection.fetch();
	});
});
