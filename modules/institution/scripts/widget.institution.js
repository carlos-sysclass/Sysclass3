// THINK ABOUT BREAKING THIS MODULE IN TWO.
$SC.module("panel.institution", function(mod, app, Backbone, Marionette, $, _) {

	var RosterViewClass = Backbone.View.extend({
		el: $('#institution-chat-list'),
	    itemTemplate: _.template($('#institution-status-item-template').html()),
	    events : {
	    	"click a" : "handleClick"
	    },
	    initialize: function(opt) {
	    	this.listenTo(this.collection, 'add', this.addOne);
	    	//this.listenTo(this.collection, 'sync', this.pulsateAll);
	    	this.listenTo(this.collection, 'change', this.update);
	    	this.collection.each(this.addOne.bind(this));
	    	this.pulsateAll();
	    },
	    pulsateAll : function() {
	    	this.$el.pulsate({
				color: "#399bc3",
		        repeat: false
			});
	    },
	    addOne : function(model) {
			this.$el.append(this.itemTemplate(model.toJSON()));
	    },
	    update : function(model) {
			var jid = model.get("id");
			//console.log(jid);
	    	this.$("[data-username='" + jid + "']").replaceWith(this.itemTemplate(model.toJSON()));
			this.$("[data-username='" + jid + "']").closest('div').pulsate({
				color: "#399bc3",
	            repeat: false
			});
	    },
	    handleClick : function(e) {
	    	if ($(e.currentTarget).data("status") != "offline") {
				var who = this.collection.get($(e.currentTarget).data("username"));
				$SC.module("utils.strophe").startChat(who);
	    		//$SC.request("tutoria:start", model);
	    		
	    	}
	    }
	});

	mod.addInitializer(function() {
		$SC.module("utils.strophe").start();
	});
	this.on("start", function() {
		$SC.module("utils.strophe").on("xmpp:roster:sync", function(col) {
			if (mod.view == undefined) {
				mod.view = new RosterViewClass({collection : col, el : '#institution-chat-list'});
			}
		});
		
		$SC.module("utils.strophe").on("xmpp:presence", function(presence) {
			if (mod.view != undefined) {
				console.log(presence);
				//console.log($SC.module("utils.strophe").rosterCollection == mod.view.collection);	
			}
		});

		//self.trigger("xmpp:presence", presence);
	});
});