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
			var from = message.jid.split("/");
			chat_index = from[0];
			username = from[0].split("@");
			message.from = username[0];
			mod.chatCollections[chat_index].add(message);
		}
	});

	
	// MODELS
	mod.addInitializer(function() {
	  	// VIEWS
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

	  	this.chatClass = Backbone.View.extend({

		    chatTemplate: _.template($('#tutoria-chat-template').html()),
		    collection 	: null,
		    model 		: null,
		    bbview		: null,
		    isStarted 	: false,
		    events : {
		    	"keyup .send-block input" : "keyenter"
		    },
		    initialize: function(opt) {
		    	console.log(this.model.toJSON());
		    	this.bbview  = opt.bbview;
		    	this.$el
		    		.addClass("chat-widget");
		    	
		    	this.render();

		    	this.listenTo(this.collection, 'add', this.addOne);
		    	//this.collection.fetch();

		    	//this.startPooling();
		    },
		    keyenter: function(e,a,b,c) {
		    	if ( e.which == 13 ) {
					e.preventDefault();

					var message = {
						jid 	: this.model.get("id"),
						from 	: "me",
						body 	: jQuery(e.currentTarget).val()
					};
					app.request("xmpp:message:send", message);

					this.collection.add(message);

					jQuery(e.currentTarget).val("");
				}
		    },
			startPooling : function() {
				if (this.pollInterval == undefined) {
					this.isStarted = true;
					var bbview = this.bbview;
					this.pollInterval = setInterval(function () {
					    mod.chatCollections[bbview].fetch();
					}, 5000);
				}
		    },
		    stopPooling : function() {
		    	clearInterval(this.pollInterval);
		    },
		    render : function() {
		    	this.$el.empty();
		    	this.$el.append(this.chatTemplate(this.model.toJSON()));

				$("#off-windows").append(this.$el);
				this.$(".portlet")
					.data("portlet-type", "chat")
					.data("bbview", this.bbview);

				var scroller = this.$(".scroller");
				var height;
            	if (scroller.attr("data-height")) {
                	height = scroller.attr("data-height");
            	} else {
                	height = scroller.css('height');
            	}

            	App.handleScrollers();
/*
	            scroller.slimScroll({
	                size: '7px',
	                color: (scroller.attr("data-handle-color")  ? scroller.attr("data-handle-color") : '#a1b2bd'),
	                railColor: (scroller.attr("data-rail-color")  ? scroller.attr("data-rail-color") : '#333'),
	                position: isRTL ? 'left' : 'right',
	                height: height,
	                alwaysVisible: (scroller.attr("data-always-visible") == "1" ? true : false),
	                railVisible: (scroller.attr("data-rail-visible") == "1" ? true : false),
	                disableFadeOut: true
	            });
*/
	            return this;
		    },
		    addOne: function(model) {
		    	//if (this.isStarted) {
               		this.$(".portlet-title").pulsate({
                   		color: "#399bc3",
                   		repeat: false
               		});
		    	//}
		    	if (!this.$el.is("visible")) {
		    		this.$el.slideDown(200);
		    	}
		    	if (model.get("show")) {
					var view = new statusViewClass({model: model});
		    	} else {
			    	var view = new messageViewClass({model: model});
		    	}
		    	this.$(".chat-contents").append(view.render().el);
		    },
		    open : function() {
		    	// EXPAND CHAT VIEW
	            this.$(".portlet-title .tools a.expand").removeClass("expand").addClass("collapse");
				this.$(".portlet-body").slideDown(200);
		    },
		    hide : function() {
		    	this.$el.hide();
		    }
	  	});
		/*
		this.onCollapse = function(e, portlet) {
			var index = portlet.data("bbview");
			this.chatViews[index].stopPooling();
		};
		this.onExpand = function(e, portlet) {
			var index = portlet.data("bbview");
			this.chatViews[index].startPooling();
		};
		*/

		this.onRemove = function(e, portlet) {
			var index = portlet.data("bbview");
			this.chatViews[index].hide();
			
			//this.chatViews[index].destroy();

			//this.chatViews[index] = undefined;
			//this.chatCollections[index] = undefined;

			return false;
		};

		this.chatViews = [];
		this.chatCollections = [];

		this.startTutoria = function(data) {
			// OPEN CHAT DIALOG WITH USER TYPE "data.username" OR
			// OPEN CHAT DIALOG WITH USER GROUP "data.systemgroup" OR
			// OPEN CHAT DIALOG WITH USER NAME "data.lessongroup" OR
			// OPEN CHAT DIALOG WITH USER NAME "data.usergroup"
			//var usernameToChat = data.username;
			var index = data.jid;
			console.log(data);
			/*
			var index = "";
			for (i in data) {
				if (_.indexOf(["username","systemgroup","lessongroup","usergroup"], i) != -1) {
					if (data[i] != "") {
						index = i + ":" + data[i];
						break;
					}
				}
			}
			*/
			if (index == "") {
				return false;
			}

			// FIRST OF ALL, CHECK IF THE USER HAS PRIVILEGES
			if (mod.chatViews[index] == undefined) {
				// CREATE THE COLLECTION TO HANDLE MESSAGES
	  			mod.chatCollections[index] = new mod.ChatCollectionClass([], {
  					"index": index
				});
				mod.chatViews[index] = new mod.chatClass({
					bbview 		: index, 
					collection 	: mod.chatCollections[index],
					model 		: data
				});
				//mod.chatViews[index].render();
			} else {
				mod.chatViews[index].open();
			}

		};
		this.pauseTutoria = function(data) {
		};
		this.stopTutoria = function(data) {
		};
	});
	this.on("start", function() {

		// SET REQUEST/RESPONSE HANDLERS
	  	app.reqres.setHandler("tutoria:start", this.startTutoria);
	  	app.reqres.setHandler("tutoria:pause", this.pauseTutoria);
	  	app.reqres.setHandler("tutoria:stop", this.stopTutoria);
	});
});