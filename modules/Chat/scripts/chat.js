$SC.module("utils.chat", function(mod, app, Backbone, Marionette, $, _) {
    ab.debug(true, false);

    this.startWithParent = true;
    /*
    this._chatViews = [];
    //this._canStart = false;
    
    this._subscribedChats = {};
    */

    this._wsUri = 'ws://' + window.location.hostname +':8080'; // DEFAULT

    this._options = {
        maxRetries : 10,
        tryCount : 0,
        delayTime: 100
    }

    this.models = {
        //chat : Backbone.Model.extend({}),
        queue : Backbone.DeepModel.extend({}),
        message : Backbone.DeepModel.extend({})
    };

    this.collections = {
        queues : Backbone.Collection.extend({
            models : this.models.queue
        }),
        conversations : Backbone.Collection.extend({
            id: null,
            model : this.models.message,
            url : function() {
                return "/module/chat/items/conversation/default/" + JSON.stringify({chat_id: this.id});
            }
        })
    };

    this._session_key = null;
    this._token = null;
    this._started = moment().unix();
    this._conn = null;


    this._queues = new this.collections.queues(); // COLLECTION HOLDING HOLD ALL CONVERSATIONS COLLECTIONS
    this._avaliableQueues = new this.collections.queues();
    this._conversations = {}; // HOLD ALL CONVERSATIONS COLLECTIONS

    this._subscribedTopics = {};




    this.initialize = function(settings) {

        this.listenTo(this._queues, "reset", this.subscribeQueues.bind(this));

        if (window.location.protocol == 'https:') {
            this._wsUri = 'wss://' + window.location.hostname +':' + app.userSettings.get("websocket_ssl_port");
        } else {
            this._wsUri = 'ws://' + window.location.hostname +':' + app.userSettings.get("websocket_port");
        }

        this.on("errorConnection.chat", this.startRetryMode.bind(this));

        //this.on("errorConnection.chat", this.disableChatViews.bind(this));
        //this.on("afterConnection.chat", this.restoreSession.bind(this));
        
        this.startRetryMode(true);
    }
    /*
    mod.onChatConnected = function(result) {
    }
    */
    this.isConnected = function() {
        if (!_.isNull(this._conn)) {
            session_id = this._conn.sessionid();
            return !_.isNull(session_id);
        }
        return false;
    }
    this.startConnection = function(force_close) {
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
                    if (_.isEmpty(session_key)) {
                        session_key = $.cookie("PHPSESSID");
                    }

                    this._options.tryCount = 0;

                    this._conn
                        .call("authentication", websocket_key, session_key)
                        .then(function (result) {
                            this._token = result.token;
                            this._started = result.started;

                            this._session_key = session_key;

                            // ALWAYS SUBSCRIBE TO A PRIVATE 
                            //this.subscribe(this._session_key, true);

                            this._subscribedTopics[this._session_key] = this._session_key;

                            console.warn("afterConnection.chat");
                            this.restoreSession();

                            this.trigger("afterConnection.chat", result);

                        }.bind(this), function (error) {
                            this.trigger("errorConnection.chat", error);
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

    this.startRetryMode = function(now) {
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

    mod.restoreSession = function() {
        console.warn("chat->restoreSession");

        for (var topic in this._subscribedTopics) {

            console.warn(topic, this._subscribedTopics);
            this.unsubscribe(topic);
            this.subscribe(topic);
        }

        this.getQueues();
    };
    // CALLED ON QUEUE RESET COLLECTION
    this.subscribeQueues = function(collection) {
        console.warn("chat->subscribeQueues");
        this._queues.each(function(item, index) {
            var topic = item.get("topic");
            this.subscribe(topic, true);
            //this._conn.subscribe(this._subscribedTopics[topic], this.parseReceivedTopic.bind(this));
        }.bind(this));
    };

    this.subscribe = function(topic, exclusive) {
        console.warn("chat->subscribe", topic);
        if (exclusive && _.has(this._subscribedTopics, topic) && !_.isNull(this._subscribedTopics[topic])) {
            return false;
        }
        this._subscribedTopics[topic] = topic;
        if (!_.has(this._conversations, topic)) {
            this._conversations[topic] = new this.collections.conversations();
        }

        this._conn.subscribe(topic, this.parseReceivedTopic.bind(this));

        return true;
    }

    this.parseReceivedTopic = function(topic, data) {
        // CHECK IF IS A COMMAND OR A MESSAGE
        if (topic == this._session_key) { // PRIVATE CHANNEL
            this.parseCommandTopic(data);
        } else {
            // APPEND TO CONVERSATION COLLECTION
            if (data.origin == this._token) {
                data.mine = true;
            } else {
                data.mine = false;
            }
            var model = new this.models.message(data);
            this._conversations[topic].add(model);
            this._conversations[topic].id = model.get("chat_id");

            if (model.get("type") == "info") {
                this.trigger("receiveInfo.chat", topic, model, this._conversations[topic]);
            } else {
                this.trigger("receiveChat.chat", topic, model, this._conversations[topic]);
                document.getElementById('ping').play();
            }



            //this.trigger("receiveChat.chat", topic, model, this._conversations[topic]);

            
            /*
            this.receiveChat(topic, function() {
                if (data.origin == this._token) {
                    data.mine = true;
                } else {
                    data.mine = false;
                }

                var model = new this.models.message(data);
                console.warn("receiveMessage.chat", topic, model);
                this.trigger("receiveMessage.chat", topic, model);

                document.getElementById('ping').play();
            }.bind(this));
            */
        }
    }



    // REMOTE FUNCTIONS
    this.getQueues = function(callback) {
        console.warn("chat->getQueues");
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("getQueues")
            .then(function (result) {
                this.parseQueues(result);
                callback(result);
            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    this.parseQueues = function(queues) {
        this._queues.reset(queues);
    }

    this.getAvaliableQueues = function(callback) {
        console.warn("chat->getAvaliableQueues");
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }

        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("getAvaliableQueues")
            .then(function (result) {
                
                this._avaliableQueues.reset(result);

                if (_.isFunction(callback)) {
                    callback(result);
                }
            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };




    /*
    this.createQueue = function(topic, title, exclusive, startChat) {
        //console.warn("createQueue");
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("startQueue", topic, title)
            .then(function (result) {
                var model = new this.models.chat(result);

                var new_topic = model.get("topic");

                this.trigger("createQueue.chat", new_topic, model);

                this.subscribeToChat(new_topic, model, exclusive, startChat);
                
                //this.trigger("startQueue.chat", new_topic, model);
                

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };
    */
    this.createChat = function(user_id) {
        //console.warn("createQueue");
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }
        // CALL A FUNCTION TO CREATE THE TOPIC
        this._conn
            .call("createChat", user_id)
            .then(function (result) {
                var model = new this.models.queue(result);
                this._queues.add(model);

                var new_topic = model.get("topic");

                console.warn(new_topic, model, this.subscribe(new_topic, true));

                this.subscribe(new_topic, true);

                this.trigger("createChat.chat", new_topic, model);


                
                //this.trigger("startQueue.chat", new_topic, model);
                

            }.bind(this), function (error) {
                //this.trigger("errorConnection.chat", error);
                console.warn("error", error);
            }.bind(this));
    };

    /*
    this.receiveChat = function(topic, callback) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat");
            return false;
        }

        console.warn(this._subscribedChats, topic);

        if (!_.has(this._subscribedChats, topic) || _.isNull(this._subscribedChats[topic])) {
            // CALL A FUNCTION TO CREATE THE TOPIC
            this._conn
                .call("receiveChat", topic)
                .then(function (result) {
                    //console.warn(result);
                    var model = new this.models.chat(result);

                    //this._subscribedChats[topic] = model;

                    this.trigger("receiveChat.chat", topic, model);

                    callback();
                }.bind(this), function (error) {
                    console.warn("error", error);
                }.bind(this));
        } else {
            //console.warn("receiveChat-error");
            callback();
        }
    };
    */
    /*
    this.subscribe = function(topic, model, exclusive, startChat) {
        if (exclusive && _.has(this._subscribedTopics, topic)) {
            return false;
        }
        this._subscribedTopics[topic] = topic;
        this._subscribedChats[topic] = model ? model : null;

        this._conn.subscribe(topic, this.parseReceivedTopic.bind(this));

        //this.trigger("queueSubscribed.chat", topic, model);

        if (startChat !== false) {

            //this.startChatView(model);
        }
    }
    */

    this.unsubscribe = function(topic) {
        if (_.has(this._conn._subscriptions, topic)) {
            this._conn.unsubscribe(topic);
        }
        this._subscribedTopics[topic] = null;
    }



    this.parseCommandTopic = function(data) {
        if (data.command == "subscribe") {
            this.subscribe(data.data.topic, true);
            this.getQueues();
        }
    };

    this.sendMessage = function(topic, message) {
        if (_.isNull(this._token)) {
            this.trigger("notConnected.chat", error);
            return false;
        }
        console.warn("SEND", topic, message);
        this._conn.publish(topic, message, false);
    }
    /*
    this.disableChatViews = function() {
        for (var topic in this._chatViews) {
            this._chatViews[topic].disable();
        }
    };
    */
    /*
    this.startChatView = function(model) {
        console.warn("startChatView");
        var topic = model.get("topic");
        if (mod._chatViews[topic] == undefined) {
            mod._chatViews[topic] = new chatViewClass({
                model : model
            });
        } else {
            mod._chatViews[topic].focus();
        }
    } 
    */


    // GETTERS / SETTERS
    this.getQueuesCollection = function() {
        return this._queues;
    };
    this.getAvaliableQueuesCollection = function() {
        return this._avaliableQueues;
    };
    /*
    this.getQueueModel = function(topic) {
        this._queues
    };
    */
    this.getConversation = function(topic) {
        return this._conversations[topic];
    }
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

    if (app.hasSettings) {
        this.initialize(app.userSettings);
    } else {
        mod.listenTo(app, "settings.sysclass", function( model,data, xhR) {
            //console.warn( model,data, xhR);
            this.initialize(model);
        });
    }        

});
