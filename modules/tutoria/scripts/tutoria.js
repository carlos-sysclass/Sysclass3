$SC.module("portlet.tutoria", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
	  	this.collection = new Backbone.Collection;
		this.collection.url = "/module/tutoria/data";
	  	
	  	// VIEWS
	  	var viewClass = Backbone.View.extend({
		    el: $('#tutoria-accordion'),
		    portlet: $('#tutoria-widget'),

		    itemTemplate: _.template($('#tutoria-item-template').html()),
		    noDataFoundTemplate: _.template($('#tutoria-nofound-template').html()),

		    initialize: function() {
				this.listenTo(mod.collection, 'sync', this.render);
				mod.collection.fetch();
		    },
		    render: function(collection) {
				this.$el.empty();

				if (collection.size() == 0) {
					this.$el.append(this.noDataFoundTemplate());
				} else {
					var self = this;

					collection.each(function(model,i) {
						self.$el.append(
							self.itemTemplate(model.toJSON())
						);
					});
				}
		    }
	  	});

		this.view = new viewClass();
		this.searchBy = "title";

		this.onFullscreen = function(e, portlet) {
			this.view.$el.css({
				'height': 720
			});
		};
		this.onRestorescreen = function(e, portlet) {
			this.view.$el.css({
				'height': 'auto'
			});
		};

	  	// VIEWS
	  	var formClass = Backbone.View.extend({
		    el: $('#tutoria-widget-form'),
		    initialize: function() {

				this.$el.validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block', // default input error message class
					focusInvalid: false, // do not focus the last invalid input
					ignore: "",
					rules: {
						title: {
							minlength: 10,
							required: true
						}
					},
					highlight: function (element) { // hightlight error inputs
						// set error class to the control group
						$(element).closest('.form-group').addClass('has-error')
							.find(".input-group-btn button").removeClass("blue").addClass("red");
					},
					unhighlight: function (element) { // revert the change done by hightlight
						$(element).closest('.form-group').removeClass('has-error')
							.find(".input-group-btn button").removeClass("red").addClass("blue");
					},
					success: function (label) {
						label.closest('.form-group').removeClass('has-error'); // set success class to the control group
					},
					errorPlacement: function(error, element) {
						error.appendTo( element.closest(".chat-form") );
					},
					submitHandler: function (form) {
						jQuery.post(
						  $(form).attr("action"),
						  $(form).serialize(),
						  function(response, status) {
							$(form).find(":input").val("");
							mod.collection.fetch();
							if (response.action) {
								$SC.request("toastr:message", response.action.type, response.action.message);
							}
						  }
						);
					}
				});
		    }
	  	});
		this.formView = new formClass();
	});
});

// THINK ABOUT BREAKING THIS MODULE IN TWO.
$SC.module("portlet.chat", function(mod, app, Backbone, Marionette, $, _) {
	/*
	this.ChatCollectionClass = Backbone.Collection.extend({
		initialize : function(models, options) {
			$SC.module("utils.strophe").start();

			app.reqres.setHandler("xmpp:connect:status", this.connectStatus);
			//app.reqres.setHandler("xmpp:message", this.receiveMessage);

			$SC.module("utils.strophe").connection.messaging.on("xmpp:message", this.receiveMessage);
			$SC.module("utils.strophe").connection.roster.on("xmpp:presence", this.receivePresence);
		},
		connectStatus : function(status) {
			//console.log('fdks');
		},
		receivePresence : function(presence) {
			var chat_index = presence.jid;
			var username = presence.jid.split("@");
			presence.from = username[0];
			if (mod.chatCollections[chat_index]) {
				mod.chatCollections[chat_index].add(presence);	
			}
		},
		receiveMessage : function(message) {
			var chat_index = message.from.barejid;
			var username = message.from.node;
			console.log(chat_index);
			console.log(mod.chatCollections);
			if (mod.chatCollections[chat_index]) {
				mod.chatCollections[chat_index].add(message);
			}
		}
	});
	*/
	var messageViewClass = Backbone.View.extend({
		tagName: "li",
	    itemTemplate: _.template($('#tutoria-chat-item-template').html()),
	    render: function() {
	    	this.$el.append(this.itemTemplate(this.model.toJSON()));
	    	return this;
	    }
  	});
	var statusViewClass = Backbone.View.extend({
		tagName: "li",
	    itemTemplate: _.template($('#tutoria-chat-status-template').html()),
	    render: function() {
	    	this.$el.append(this.itemTemplate(this.model.toJSON()));
	    	return this;
	    }
  	});

  	var chatViewClass = Backbone.View.extend({

	    chatTemplate: _.template($('#tutoria-chat-template').html()),
	    collection 	: null,
	    model 		: null,
	    bbview		: null,
	    isStarted 	: false,
	    events : {
	    	"keyup .send-block input" : "keyenter"
	    },
	    initialize: function(opt) {
	    	//console.log(this.model.toJSON());
	    	this.collection = this.model.get("messages");
//		    	this.bbview  = opt.bbview;
	    	this.$el.addClass("chat-widget");
	    	
	    	this.render();
	    	// ADD FIRST MESSAGES
	    	this.updateStatus(this.model);

	    	this.collection.each(this.addOne.bind(this));
			
			this.listenTo(this.model, 'change:status', this.updateStatus);
	    	this.listenTo(this.collection, 'add', this.addOne);

	    },
	    keyenter: function(e,a,b,c) {
	    	if ( e.which == 13 ) {
				e.preventDefault();

				if (jQuery(e.currentTarget).val() != "") {
					var message = {
						jid 	: this.model.get("id"),
						from 	: "me",
						body 	: jQuery(e.currentTarget).val()
					};
					$SC.module("utils.strophe").sendMessage(this.model.get("id"), jQuery(e.currentTarget).val());
					//this.collection.add(message);
					jQuery(e.currentTarget).val("");
				}
			}
	    },
	    render : function() {
	    	this.$el.empty();
	    	this.$el.append(this.chatTemplate(this.model.toJSON()));

			$("#off-windows").append(this.$el);
			this.$(".portlet").data("portlet-type", "chat");

			var scroller = this.$(".scroller");
			var height;
        	if (scroller.attr("data-height")) {
            	height = scroller.attr("data-height");
        	} else {
            	height = scroller.css('height');
        	}

        	App.handleScrollers();

            return this;
	    },
	    updateStatus : function(model) {
	    	var view = new statusViewClass({model: model});
	    	this.$(".chat-contents").append(view.render().el);

	    	
	    },
	    addOne: function(model) {
	    	var view = new messageViewClass({model: model});
	    	this.$(".chat-contents").append(view.render().el);

			document.getElementById('ping').play();
	    },
	    focus : function(restoreIfHidden) {
	    	if (!this.$(".portlet").is(":visible") && restoreIfHidden != false) {
            	//this.$(".portlet-title .tools a.expand").removeClass("expand").addClass("collapse");
				this.$el.slideDown(200);
			} else if (!this.$(".portlet > .portlet-body").is(":visible") && restoreIfHidden != false) {
            	this.$(".portlet-title .tools a.expand").removeClass("expand").addClass("collapse");
            	var self = this;
				this.$(".portlet > .portlet-body").slideDown(200, function() {
					self.$(".portlet-title").pulsate({
	            		color: "#399bc3",
	               		repeat: false
					});
				});	
			} else {
				this.$(".portlet-title").pulsate({
	            	color: "#399bc3",
	               	repeat: false
				});
			}

	    	var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
			this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
	    }
  	});

	
	this.onRemove = function(e, portlet) {
		portlet.closest(".chat-widget").hide();
		return false;
	};

	this.chatViews = [];

	this.on("start", function() {
		$SC.module("utils.strophe").on("xmpp:message xmpp:startchat", function(model) {
			var index = model.get("id");
			if (mod.chatViews[index] == undefined) {
				mod.chatViews[index] = new chatViewClass({
					model 		: model
				});
			} else {
				mod.chatViews[index].focus();	
			}
		});
	});
});