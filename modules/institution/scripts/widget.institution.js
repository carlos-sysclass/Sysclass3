// THINK ABOUT BREAKING THIS MODULE IN TWO.
$SC.module("panel.institution", function(mod, app, Backbone, Marionette, $, _) {

	var RosterViewClass = Backbone.View.extend({
		el: $('#institution-chat-list'),
	    itemTemplate: _.template($('#institution-status-item-template').html()),
	    events : {
	    	"click a" : "handleClick"
	    },
	    initialize: function() {
			App.blockUI({
			    target: '#institution-chat-list',
			    overlayColor: 'none',
				iconOnly : true,
			    boxed: true
			});
			this.started = false;
	    },
	    start : function(col) {
	    	if (!this.started) {

		    	this.started = true;
		    	this.collection = col;

		    	this.listenTo(this.collection, 'add', this.addOne);
		    	//this.listenTo(this.collection, 'sync', this.pulsateAll);
		    	this.listenTo(this.collection, 'change', this.update);

		    	this.collection.each(this.addOne.bind(this));
		    	this.unblock();
	    	}
	    },
	    unblock : function() {
			App.unblockUI('#institution-chat-list');
			/*
	    	this.$el.pulsate({
				color: "#399bc3",
		        repeat: false
			});
			*/
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
		//$("#institution-chat-list").blockUI();
		mod.view = new RosterViewClass({el : '#institution-chat-list'});

		$SC.module("utils.strophe").on("xmpp:roster:sync", function(col) {
			mod.view.start(col);
		});
		
		$SC.module("utils.strophe").on("xmpp:presence", function(presence) {
			if (mod.view != undefined) {
				//console.log(presence);
				//console.log($SC.module("utils.strophe").rosterCollection == mod.view.collection);	
			}
		});

		//self.trigger("xmpp:presence", presence);
	});
});