$SC.module("portlet.tutoria", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
	  	// VIEWS
	  	var baseFormClass = app.module("views").baseFormClass;
	  	var tutoriaFormViewClass = baseFormClass.extend({

			initialize: function() {
		    	console.info('portlet.tutoria/tutoriaFormViewClass::initialize');
		    	baseFormClass.prototype.initialize.apply(this);

		    	var self = this;
				this.on("after:save", function(model) {
					self.model = new mod.models.tutoria({
						title : ""
					});
					self.render();
				});
			},
		    handleValidation: function() {
		    	console.info('views/baseFormClass::handleValidation');
		    	var self = this;
				this.oForm.validate({
					ignore: null,
	                errorElement: 'span', //default input error message container
	                errorClass: 'help-block', // default input error message class
					errorPlacement: function(error, element) {
						error.appendTo( element.closest(".chat-form") );
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
					submitHandler : function(f) {
						self.save();
					}
				});
		    }
	  	});

		var parent = app.module("portlet");

		var tutoriaBlockViewItemClass = parent.blockViewItemClass.extend({
			tagName : "div",
			template : _.template($("#tutoria-item-template").html(), null, {variable: "model"})
		});

		var tutoriaBlockViewClass = parent.blockViewClass.extend({
			nofoundTemplate : _.template($("#news-nofound-template").html()),
			childViewClass : tutoriaBlockViewItemClass
		});

		this.tutoriaWidgetViewClass = parent.widgetViewClass.extend({
			collectionClass : mod.collections.tutoria,
			blockViewClass : tutoriaBlockViewClass,
			onStart : function() {

				// CREATE SUB-VIEWS AND FETCH MODELS AND COLLECTIONS
				this.tutoriaFormView = new tutoriaFormViewClass({
					el: '#tutoria-widget-form',
					model: new mod.models.tutoria()
				});

				this.listenTo(this.tutoriaFormView, "after:save", function(model) {
					this.collection.add(model, {at: 0});
				});
			},
			onFullScreen : function() {
				this.$(".scroller").slimScroll({destroy: true});
				this.$(".scroller").css("height", "auto");
			},
			onRestoreScreen : function() {
				app.module("ui").handleScrollers(this.$el);
			}
		});
	});

	this.models = {
		tutoria : Backbone.DeepModel.extend({
			urlRoot : "/module/tutoria/item/question"
		})
	};
	this.collections = {
		tutoria : Backbone.Collection.extend({
			url : "/module/tutoria/items/question",
			model : this.models.tutoria
		})
	};

	mod.on("start", function() {

		mod.listenToOnce(app.userSettings, "sync", function(model, data, options) {

			mod.tutoriaWidgetView = new this.tutoriaWidgetViewClass({
				model : app.userSettings,
				el: '#tutoria-widget'
			});

		}.bind(this));
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
			// 	this.bbview  = opt.bbview;
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
					/*
					var message = {
						jid 	: this.model.get("id"),
						from 	: "me",
						body 	: jQuery(e.currentTarget).val()
					};
					*/
					app.module("utils.strophe").sendMessage(this.model.get("id"), jQuery(e.currentTarget).val());
					//this.collection.add(message);
					jQuery(e.currentTarget).val("");

			    	var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
					this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
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

        	app.module("ui").handleScrollers(this.$el);

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
