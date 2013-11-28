// THINK ABOUT BREAKING THIS MODULE IN TWO.
$SC.module("panel.institution", function(mod, app, Backbone, Marionette, $, _) {

	mod.addInitializer(function() {
		$SC.module("utils.strophe").start();

		var RosterViewClass = Backbone.View.extend({
			el: $('#institution-chat-list'),
		    itemTemplate: _.template($('#institution-status-item-template').html()),
		    events : {
		    	"click a" : "handleClick"
		    },
		    initialize: function(opt) {
		    	this.listenTo(this.collection, 'add', this.addOne);
		    	this.listenTo(this.collection, 'change', this.update);
		    },
		    addOne : function(model) {
				this.$el.append(this.itemTemplate(model.toJSON()));
		    },
		    update : function(model) {
				var jid = model.get("id");
		    	this.$("[data-username='" + jid + "']").replaceWith(this.itemTemplate(model.toJSON()));
		    },
		    handleClick : function(e) {
		    	if (jQuery(e.currentTarget).data("status") != "offline") {
					var model = this.collection.get(jQuery(e.currentTarget).data("username"));
		    		$SC.request("tutoria:start", model);
		    	}
		    }
		});

		this.connectStatus = function(status) {
			if (status == Strophe.Status.CONNECTED) {
				defered = $SC.module("utils.strophe").getRosterList(function(roster) {
					for(jid in roster) {
						mod.collection.add({
							id   	: jid,
							name 	: roster[jid].name,
							status 	: "offline"
						});
					}
				});
			}
		};

		this.receivePresence = function(presence) {
			var model = mod.collection.get(presence.jid);
			if (model != undefined) {
				var status = "offline";
				if (presence.priority == "1") {
					status = "online";
				} else if (presence.show == "dnd") {
					status = "busy";
				} else if (presence.show == "away" || presence.show == "xa") {
					status = "away";
				} else {
					status = "offline";
				}
				model.set("status", status);
			}
		};

		this.collection = new Backbone.Collection;
		this.view = new RosterViewClass({collection : this.collection});

	});
	this.on("start", function() {
		$SC.module("utils.strophe").on("xmpp:connect", this.connectStatus);
		$SC.module("utils.strophe").on("xmpp:presence", this.receivePresence);
	});
});