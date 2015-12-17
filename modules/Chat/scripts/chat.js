$SC.module("utils.chat", function(mod, app, Backbone, Marionette, $, _) {
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
    var statusViewClass = Backbone.View.extend({
        tagName: "li",
        itemTemplate: _.template($('#tutoria-chat-status-template').html()),
        render: function() {
            this.$el.append(this.itemTemplate(this.model.toJSON()));
            return this;
        }
    });
    var chatViewClass = Backbone.View.extend({
        chatTemplate: _.template($('#chat-template').html(), null, {variable : "model"}),
        collection  : null,
        model       : null,
        bbview      : null,
        isStarted   : false,
        events : {
            "click .portlet" : "removeFocus",
            "keyup .send-block input" : "keyenter",
            "click .portlet-title" : "toggleWindow",
            "click .tools a.remove" : "close",
        },
        initialize: function(opt) {
            //console.log(this.model.toJSON());
            //this.collection = this.model.get("messages");
            //  this.bbview  = opt.bbview;
            this.$el.addClass("chat-widget");

            this.render();

            this.listenTo(mod, "receiveMessage.chat", this.addOne.bind(this));
            this.listenTo(mod, "errorConnection.chat", this.disable.bind(this));
            this.listenTo(mod, "afterConnection.chat", this.enable.bind(this));
            // ADD FIRST MESSAGES
            //this.updateStatus(this.model);

            //this.collection.each(this.addOne.bind(this));

            //this.listenTo(this.model, 'change:status', this.updateStatus);
            //this.listenTo(this.collection, 'add', this.addOne);

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
                    
                    mod.sendMessage(
                        this.model.get("topic"), 
                        message
                    );
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

            app.module("ui").handleScrollers(this.$el);

            return this;
        },
        updateStatus : function(model) {
            var view = new statusViewClass({model: model});
            this.$(".chat-contents").append(view.render().el);
        },
        addOne: function(topic, data) {

            if (topic == this.model.get("topic")) {
                var model = new mod.models.message(data);
                var view = new messageViewClass({model: model});
                this.$(".chat-contents").append(view.render().el);
                /*
                var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
                this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
                */
                if (!model.get("mine")) {
                    this.focus();
                } else {
                    var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
                    this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
                }
            }
        },
        focus : function() {
            document.getElementById('ping').play();
            
            this.$(".portlet").addClass("yellow");

            if (!this.$(".portlet").is(":visible")) {
                this.$el.removeClass("hidden");
            }

            if (!this.$(".portlet > .portlet-body").hasClass("hidden")) {
                var scrollTo_val = this.$(".chat-contents").prop('scrollHeight') + 'px';
                this.$(".chat-contents").slimScroll({scrollTo : scrollTo_val});
            }
        },
        /*
        collapse : function(e) {
            if (_.isObject(e)) {
                e.preventDefault();
                e.stopPropagation();
            }

            this.$(".portlet > .portlet-body").addClass("hidden");
        },
        */
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

            $(".portlet-body").block({
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
        /*
        expand : function(e) {
            e.preventDefault();
            e.stopPropagation();
            //this.removeFocus();

            $(e.currentTarget).removeClass("expand").addClass("collapse");
            this.$(".portlet > .portlet-body").removeClass("hidden");
        },
        */
        toggleWindow : function() {
            this.$(".portlet > .portlet-body").toggleClass("hidden");
            /*
            if (this.$(".portlet > .portlet-body").hasClass("hidden")) {
                //this.$(".tools .expand").removeClass("expand").addClass("collapse");
                this.$(".portlet > .portlet-body").removeClass("hidden");
            } else {
                this.$(".portlet > .portlet-body").addClass("hidden");
            }
            */
        },
        close : function() {
            //this.removeFocus();

            this.$el.addClass("hidden");
        },
        removeFocus : function() {
            this.$(".portlet").removeClass("yellow");
        }
    });

    /*
    this.onRemove = function(e, portlet) {
        portlet.closest(".chat-widget").hide();
        return false;
    };

    this.chatViews = [];

    $SC.module("utils.strophe").on("xmpp:message xmpp:startchat", function(model) {

    this.on("start", function() {

        $SC.module("utils.strophe").on("xmpp:message xmpp:startchat", function(model) {
            var index = model.get("id");
            if (mod.chatViews[index] == undefined) {
                mod.chatViews[index] = new chatViewClass({
                    model       : model
                });
            } else {
                mod.chatViews[index].focus();
            }
        });
    });
    */
    this.startWithParent = false;

    this._chatViews = [];
    this._canStart = false;
    this._conn = null;
    this._token = null;
    this._started = moment().unix();
    this._subscribedTopics = {};

    this._wsUri = 'ws://' + window.location.hostname +':8080';

    this._options = {
        maxRetries : 10,
        tryCount : 0,
        delayTime: 100
    }

    this.models = {
        chat : Backbone.Model.extend({}),
        message : Backbone.DeepModel.extend({})
    };

    this.collections = {
        conversations : Backbone.Collection.extend({
            id: null,
            model : this.models.message,
            url : function() {
                return "/module/chat/items/conversation/default/" + JSON.stringify({chat_id: this.id});
            }
        })
    };

    mod.initialize = function() {
        this.on("errorConnection.chat", this.startRetryMode.bind(this));

        //this.on("errorConnection.chat", this.disableChatViews.bind(this));
        this.on("afterConnection.chat", this.restoreSession.bind(this));
        
        this.startRetryMode(true);
    }
    /*
    mod.onChatConnected = function(result) {
    }
    */
    mod.startConnection = function(force_close) {
        if (_.isNull(app.userSettings.get("websocket_key"))) {
            return false;
        } 
        if (!_.isNull(this._conn) && force_close === true) {
            this._conn.close();
            this._conn = null;
        }
        if (_.isNull(this._conn)) {
            this.trigger("beforeConnection.chat");
            
            this._conn = new ab.Session(
                this._wsUri,
                function() {
                    console.warn('WebSocket connection open');
                    var websocket_key = app.userSettings.get("websocket_key");
                    var session_key = $.cookie("SESSIONID");

                    this._options.tryCount = 0;

                    this._conn
                        .call("authentication", websocket_key, session_key)
                        .then(function (result) {
                            this._token = result.token;
                            this._started = result.started;

                            this.trigger("afterConnection.chat", result);
                        }.bind(this), function (error) {
                            this.trigger("errorConnection.chat", error);
                            console.warn("error", error);

                        }.bind(this));

                }.bind(this),
                function(code, reason, detail) {
                    console.warn('WebSocket connection closed');
                    this._conn = null;
                    this.trigger("errorConnection.chat");

                }.bind(this),
                {'skipSubprotocolCheck': true, retryDelay : 1000}
            );
            
        }
        return this._conn;
    }

    mod.startRetryMode = function(now) {
        this._options.tryCount++;
        if (this._options.maxRetries > 0 && this._options.tryCount <= this._options.maxRetries) {
            

            if (_.isBoolean(now) && now) {
                console.warn('WebSocket connection Try #' + this._options.tryCount);
                mod.startConnection();
            } else {
                var delay = this._options.delayTime + this._options.tryCount * this._options.tryCount * 25;
                console.warn('WebSocket connection Try #' + this._options.tryCount + ", delaying for " + delay + "ms");
                setTimeout(mod.startConnection.bind(this), delay);
            }
        }
    }

    this.restoreSession = function() {
        for (var topic in this._subscribedTopics) {
            if (!_.isNull(this._subscribedTopics[topic])) {
                this._conn.subscribe(this._subscribedTopics[topic], this.parseReceivedTopic.bind(this));
            }
        }
    }

    this.createQueue = function(topic, title) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("startQueue", topic, title)
            .then(function (result) {
                //this.trigger("afterConnection.chat", result);
                console.warn("success", result);
                var model = new this.models.chat(result);

                var new_topic = model.get("topic");

                this.subscribeToChat(new_topic, model);
                
                //this.trigger("startQueue.chat", new_topic, model);
                

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    this.subscribeToChat = function(topic, model, exclusive) {
        if (exclusive && _.has(this._subscribedTopics, topic)) {
            return false;
        }
        this._subscribedTopics[topic] = topic;

        this._conn.subscribe(topic, this.parseReceivedTopic.bind(this));

        //this.startChatView(model);
    }

    this.unsubscribeToChat = function(topic) {
        this._conn.unsubscribe(topic);
''
        this._subscribedTopics[topic] = null;
    }

    this.parseReceivedTopic = function(topic, data) {
        // CHECK IF IS A COMMAND OR A MESSAGE
        console.warn("RECEIVE", topic, data);
        if (data.origin == this._token) {
            data.mine = true;
        } else {
            data.mine = false;
        }
        
        this.trigger("receiveMessage.chat", topic, data);
    }

    this.sendMessage = function(topic, message) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat", error);
            return false;
        }
        console.warn("SEND", topic, message);
        this._conn.publish(topic, message, false);
    }

    this.getQueues = function(callback) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("getUnassignedQueues")
            .then(function (result) {
                callback(result);

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    this.disableChatViews = function() {
        for (var topic in this._chatViews) {
            this._chatViews[topic].disable();
        }
    };

    this.startChatView = function(model) {
        var topic = model.get("topic");
        if (mod._chatViews[topic] == undefined) {
            mod._chatViews[topic] = new chatViewClass({
                model : model
            });
        } else {
            mod._chatViews[topic].focus();
        }
    }

    // REMOTE FUNCTIONS
    this.getQueues = function(callback) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("getQueues")
            .then(function (result) {
                callback(result);

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    /*
    this.startChat = function(who) {
        // CHECK IF USER IS ALLOWED 

        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat", error);
            return false;
        }
        return false;
    };
    */

    mod.on("start", function() {
        if (!_.isUndefined(app.userSettings)) {
            this.initialize();
        } else {
            //var userSettingsModel = new userSettingsModelClass();
            this.listenToOnce(app.userSettings, "sync", function(model, data, options) {
                mod.initialize();
            }.bind(this));
        }
    });
});
