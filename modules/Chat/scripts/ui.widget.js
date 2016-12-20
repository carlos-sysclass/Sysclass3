$SC.module("widget.chat", function(mod, app, Backbone, Marionette, $, _) {
	this.blockChatView = null;
	this.canStart = false;
	this.started = false;

	this.startWithParent = false;

	/**
	 * @todo MOVE RELATED MODELS TO CHAT MODULE
	 */
	 /*
	this.models = {
		queue : Backbone.DeepModel.extend({
			urlRoot : "/module/chat/item/me"
		}),
		conversation : Backbone.DeepModel.extend({}),
	};

	this.collections = {
		queues : Backbone.Collection.extend({
			model : this.models.queue
		})
	};

	*/
	this.on("start", function() {

	    mod.chatModule = app.module("utils.chat");

		this._chatViews = {};

	    var messageViewClass = Backbone.View.extend({
	        tagName: "div",
	        className : "chat-message-item",
	        templates : {
	            info : _.template($('#chat-item-info-template').html(), null, {variable : "model"}),
	            all : _.template($('#chat-item-template').html(), null, {variable : "model"})
	        },
	        render: function() {
	            if (_.has(this.templates, this.model.get("type"))) {
	                var template = this.templates[this.model.get("type")];
	            } else {
	                var template = this.templates['all'];
	            }
	            this.$el.append(template(this.model.toJSON()));
	            return this;
	        }
	    });
	    /*
	    var statusViewClass = Backbone.View.extend({
	        tagName: "li",
	        itemTemplate: _.template($('#tutoria-chat-status-template').html()),
	        render: function() {
	            this.$el.append(this.itemTemplate(this.model.toJSON()));
	            return this;
	        }
	    });
	    */
	    var chatViewClass = Backbone.View.extend({
	    	chatModule : mod.chatModule,
	        chatTemplate: _.template($('#chat-template').html(), null, {variable : "model"}),
	        collection  : null,
	        model       : null,
	        bbview      : null,
	        isStarted   : false,

			previousMessageContainer : null,
			currentMessageContainer : null,

	        events : {
	            "click .portlet" : "removeFocus",
	            "keyup .send-block input" : "keyenter",
	            "click .portlet-title" : "toggleWindow",
	            "click .tools a.remove" : "close",
	            "click .load-previous-messages-action" : "loadPrevious"
	        },
	        initialize: function(opt) {
	        	console.warn(this.model);

	            this.$el.addClass("chat-widget");

	            //this.render();

	            this.listenTo(mod.chatModule, "receiveMessage.chat", this.addOne.bind(this));
	            this.listenTo(mod.chatModule, "errorConnection.chat", this.disable.bind(this));
	            this.listenTo(mod.chatModule, "afterConnection.chat", this.enable.bind(this));

	            this.collection = this.chatModule.getConversation(this.model.get("topic"));

				this.listenTo(this.collection, "request", this.showPreviousLoader.bind(this));
				this.listenTo(this.collection, "sync", this.renderPrevious.bind(this));
				this.listenTo(this.collection, "add", this.addOne.bind(this));

				this.render();

				this.collection.each(this.addOne.bind(this));
	        },
	        keyenter: function(e) {
	            if ( e.which == 13 ) {
	                e.preventDefault();

	                if (jQuery(e.currentTarget).val() != "") {
	                    
	                    var message = {
	                        topic     : this.model.get("topic"),
	                        //from        : "me",
	                        message    : jQuery(e.currentTarget).val()
	                    };
	                    
	                    mod.chatModule.sendMessage(
	                        this.model.get("topic"), 
	                        message
	                    );
	                    //this.collection.add(message);
	                    jQuery(e.currentTarget).val("");

	                }
	            }
	        },
			loadPrevious : function() {
	        	this.collection.fetch();
	        },
	        showPreviousLoader : function() {
	        	var loadingText = this.$(".load-previous-messages-container").data("loadingText");
	        	this.$(".load-previous-messages-container a").html(loadingText);
	        	this.$(".load-previous-messages-container i.fa").removeClass("fa-clock-o").addClass("fa-refresh fa-spin");
	        },
			renderPrevious : function() {
				var showedDate = moment();
				this.$(".load-previous-messages-container").remove();
				this.previousMessageContainer.empty();
				this.currentMessageContainer.empty();

				this.collection.each(function(model, index) {
					if (model.get("chat.topic") == this.model.get("topic")) {
						var modelDate = moment.unix(model.get("sent"));
						if (!modelDate.isSame(showedDate, 'day')) {

							var infoModel = new this.chatModule.models.message({
								type : "info",
								message : modelDate.format("LL")
							})
							var view = new messageViewClass({model: infoModel});
		                	this.previousMessageContainer.append(view.render().el);

							showedDate = modelDate;
						}
						this.addPreviousOne(model, index);
					}
				}.bind(this));

				this.messageContainer.slimScroll({
    		        scrollTo: '1000000px'
				});
			},
	        addPreviousOne : function(model) {
				console.warn('widget/chatViewClass->addPreviousOne');

	            if (model.get("chat.topic") == this.model.get("topic")) {
	                var view = new messageViewClass({model: model});
	                this.previousMessageContainer.append(view.render().el);
	            }
	        },
	        render : function() {
	        	console.warn('widget/chatViewClass->render');
	            this.$el.empty();
	            console.warn(this.model.toJSON());
	            this.$el.html(this.chatTemplate(this.model.toJSON()));

	            $("#off-windows").append(this.$el);

				this.messageContainer = this.$('.chat-contents');
				this.previousMessageContainer = this.$('.chat-contents-previous');
				this.currentMessageContainer = this.$('.chat-contents-current');

				//this.collection.fetch();


	            this.$(".portlet").data("portlet-type", "chat");

	            var scroller = this.$(".scroller");
	            var height;
	            if (scroller.attr("data-height")) {
	                height = scroller.attr("data-height");
	            } else {
	                height = scroller.css('height');
	            }

	            app.module("ui").handleScrollers(this.$el);

	            //this.collection.fetch();

	            return this;
	        },
	        addOne: function(model) {
	            if (model.get("topic") == this.model.get("topic")) {
	                var view = new messageViewClass({model: model});
	                this.currentMessageContainer.append(view.render().el);

	                if (!model.get("mine")) {
	                    this.focus();
	                } else {
	                    var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
	                    this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
	                }
	            }
	        },
	        updateStatus : function(model) {
	            //var view = new statusViewClass({model: model});
	            //this.$(".chat-contents").append(view.render().el);
	        },
	        focus : function() {
	            //document.getElementById('ping').play();
	            this.$(".portlet").addClass("yellow");

	            if (!this.$(".portlet").is(":visible")) {
	                this.$el.removeClass("hidden");
	            }

	            if (!this.$(".portlet > .portlet-body").hasClass("hidden")) {
	                var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
	                this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
	            }
	        },

	        enable : function() {
	            this.$(".portlet").removeClass("red");
	            this.$(".portlet-body").unblock();
	        },
	        disable : function() {
	            //this.collapse();
	            this.$(".portlet").addClass("red");

	            var html = '<div class="loading-message loading-message-boxed red">' +
	                '<a href="javascript:void(0)" class="btn btn-lg blockable-item">' +
	                    '<i class="fa fa-edit"></i> ' + 
	                '</a>' +
	            '</div>';

	            var html = '<div class="">' +
	                    '<i class="fa fa-lg fa-close text-danger"></i><br /> ' + 
	                  'No Connection' +
	            '</div>';

	            this.$(".portlet-body").block({
	                message: html,
	                baseZ: 20,
	                centerY: true,
	                css: {
	                    border: '1px dashed #ccc',
	                    padding: '5px 10px 10px 10px',
	                    //backgroundColor: '#fff',
	                    width: '50%'
	                },
	                overlayCSS: {
	                    backgroundColor: '#888',
	                    opacity: 0.1,
	                    cursor: 'wait'
	                }
	            });


	        },

	        toggleWindow : function() {
	            this.$(".portlet > .portlet-body").toggleClass("hidden");

	            if (!this.$(".portlet > .portlet-body").hasClass("hidden")) {
	            	this.removeFocus();

                    var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
                    this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
	            }

	        },
	        close : function() {
	            //this.removeFocus();

	            this.$el.addClass("hidden");
	        },
	        removeFocus : function() {
	            this.$(".portlet").removeClass("yellow");
	        }
	    });


		this.listenTo(this.chatModule, "createChat.chat", function(topic, model) {
			console.warn("createChat.chat", topic, model);
			mod.startChatView(topic, model);
		});
		this.listenTo(this.chatModule, "receiveChat.chat", function(topic, model, collection) {
			mod.startChatView(topic, model, false);
		});
		this.listenTo(this.chatModule, "receiveInfo.chat", function(topic, model, collection) {
		});

	    this.startChatView = function(topic, model, closed) {

	        if (!_.has(mod._chatViews, topic)) {

	            mod._chatViews[topic] = new chatViewClass({
	                model : model
	            });
	            if (closed === true) {
	            	mod._chatViews[topic].close();
	            }
	        } else {
	        	if (!model.get("mine")) {
	            	mod._chatViews[topic].focus();
	            }
	        }
	    };




		this.blockChatQueueViewClass = Backbone.View.extend({
			chatModule : mod.chatModule,
			template : _.template($("#widget-chat-queue-template").html(), null, {variable : 'model'}),
			events : {
				"click .start-chat-action" : "startChat"
			},
			tagName : "li",
			className : "chat-li",
			initialize : function() {
				this.listenTo(this.model, "change", this.render.bind(this));

				//this.wrapperChat = this.$el.parents('.page-quick-sidebar-chat');

				//this.listenTo(this.chatModule, "receiveMessage.chat", this.refreshCounter.bind(this));
				//this.chatModule.subscribeToChat(this.model.get("topic"), this.model, true, false);
			},
			render : function() {
				console.warn(this.model.toJSON());
				this.$el.html(this.template(this.model.toJSON()));
				return this;
			},
			startChat : function() {
				var topic = this.model.get("topic");

				//console.warn(this.model.toJSON(), this.model.get("user").id, this.model.get("user.id"));

				//console.warn(topic, this.model.get("name"), this.model.get("user"), this.model.get("user.id"));

				this.chatModule.createChat(this.model.get("user").id);
			}
		});

		this.blockChatViewClass = Backbone.View.extend({
			chatModule : mod.chatModule,
			//userSelectDialog : app.module("dialogs.users.select"),
			//conversationViews : {},
			//conversationHeight : 0,
			blockingOptions : {
			    css: {
			        border: '0px',
			        padding: '5px 10px 10px 10px',
			        backgroundColor: 'transparent',
			        opacity: 1,
			        color: "#aaa",
			        width: 'auto'
			    },
			    overlayCSS: {
			        backgroundColor: '#888',
			        opacity: 0.9,
			        cursor: 'wait'
			    }
			},
			initialize : function() {
				//this.initializeScrolls();

				//this.listenTo(this.collection, "reset", this.renderChatQueues.bind(this));
				/*
				this.listenTo(this.chatModule, "beforeConnection.chat", function(status) {
					var html = '<i class="fa">' +
						'<span class="fa fa-circle-o-notch fa-4x fa-spin"></span>' +
					'</i>';

					this.$el.block(_.extend({message: html, ignoreIfBlocked : true}, this.blockingOptions));
				}.bind(this));

				this.listenTo(this.chatModule, "errorConnection.chat", function(status) {
					var html = '<div class="">' +
					        '<i class="fa fa-lg fa-close text-danger"></i><br /> ' + 
					      'No Connection' +
					'</div>';

					this.$el.block(_.extend({message: html, ignoreIfBlocked : true}, this.blockingOptions));
				}.bind(this));
				*/

				//this.listenTo(this.collection, "request", this.showLoader.bind(this));
				//this.listenTo(this.collection, "sync", this.render.bind(this));
				this.listenTo(this.collection, "reset", this.render.bind(this));
				this.listenTo(this.collection, "add", this.addOne.bind(this));

				//this.$(".start-chat-action").on("click", this.startChat.bind(this))

				if (this.chatModule.isConnected()) {
					this.$el.unblock();
					//this.chatModule.getAvaliableQueues(this.renderAvaliableQueues.bind(this));

					//this.chatModule.getQueues(this.renderChatQueues.bind(this));
					//this.chatModule.subscribe("chat-events", this.receiveChatUpdates.bind(this));
				} else {
					this.listenTo(this.chatModule, "afterConnection.chat", function(status) {
						this.$el.unblock();
						//this.chatModule.getAvaliableQueues(this.renderAvaliableQueues.bind(this));

						//this.chatModule.getQueues(this.renderChatQueues.bind(this));
						//this.chatModule.subscribe("chat-events", this.receiveChatUpdates.bind(this));
					}.bind(this));
				}
				
				//if (!this.chatModule.started) {
				//	this.chatModule.start();
				//}

				this.render();
			},
			showLoader : function() {
				var html = '<i class="fa">' +
					'<span class="fa fa-circle-o-notch fa-4x fa-spin"></span>' +
				'</i>';

				this.$el.block(_.extend({message: html, ignoreIfBlocked : true}, this.blockingOptions));
			},
			render : function(result) {
				this.$el.unblock();

				this.$(".queue-container").empty();
				this.collection.each(this.addOne.bind(this));
				app.module("ui").refresh(this.$(".queue-container"));
			},
			addOne : function(model) {
				var users = model.get("users");


				var itemView = new mod.blockChatQueueViewClass({
					model: model
				});
				this.$(".queue-container").append(itemView.render().el);
			},
		});

		this.startChatBlock = function() {
			//mod.chatModule.getAvaliableQueues();

			this.started = true;
			this.blockChatView = new this.blockChatViewClass({
				model : app.userSettings,
				el: '#advisor-chat-widget',
				collection : mod.chatModule.getAvaliableQueuesCollection()
			});
		}

		this.listenTo(this.chatModule, "afterConnection.chat", function(topic, model) {
			this.canStart = true;

			if (!this.started) {
	    		this.startChatBlock();
	    	}
		}.bind(this));

	});

	app.module("utils.chat").on("start", this.start.bind(this));
});
