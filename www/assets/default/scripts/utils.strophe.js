$SC.module("utils.strophe", function(mod, app, Backbone, Marionette, $, _){

    this.startWithParent = false;

    this.rosterCollection = new Backbone.Collection();
    var presences = {};

    this.connectionHandler = function(status) {
        if (status == Strophe.Status.CONNECTING) {
            console.log('Strophe is connecting.');
        } else if (status == Strophe.Status.CONNFAIL) {
            console.log('Strophe failed to connect.');
        } else if (status == Strophe.Status.DISCONNECTING) {
            console.log('Strophe is disconnecting.');
        } else if (status == Strophe.Status.DISCONNECTED) {
            // TRY TO RECONNECT
            mod.connection.connect('student@sysclass.com', '123456', mod.connectionHandler);


        } else if (status == Strophe.Status.CONNECTED) {
            console.log('Strophe is connected.');

            console.warn($pres().tree());

            this.send($pres().tree());

            defered = this.roster.get();
            defered.done(function(roster) {
                var models = [];
                var i = 0;
                for(var jid in roster) {
                    var status = null;
                    if (presences[jid] !== undefined) {
                        status = presences[jid];
                    } else {
                        status = "offline";
                    }
                    models[i++] = {
                        id          : jid,
                        name        : roster[jid].name,
                        status      : status,
                        messages    : new Backbone.Collection()
                    };
                }
                //console.log(models);
                mod.rosterCollection.set(models);
                mod.trigger("xmpp:roster:sync", mod.rosterCollection);
            });

            this.ping.addPingHandler( function(data) {
                console.log('PING');
                console.log(data);
            });
        }
        mod.trigger("xmpp:connect:after", status);
    };

	this.addInitializer(function(){
        var BOSH_SERVICE = 'http://local.sysclass.com/chat-poll';
        this.connection = new Strophe.Connection(BOSH_SERVICE);

        var self = this;
        self.trigger("xmpp:connect:before", status);
        this.connection.connect('student@enterprise.sysclass.com', '123456', this.connectionHandler);
  	});

    this.on("xmpp:connect:after", function(status) {
        if (status == Strophe.Status.CONNECTED) {
            var self = this;
            this.connection.roster.on("xmpp:presence", function(presence) {
                var model = mod.rosterCollection.get(presence.jid);
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
                if (model !== undefined) {
                    model.set("status", status);
                } else {
                    presences[presence.jid] = status;
                }

                self.trigger("xmpp:presence", presence);
            });
            this.connection.messaging.on("xmpp:message", function(message) {
                var model = mod.rosterCollection.get(message.from.barejid);
                if (model !== undefined) {
                    var col = model.get("messages");
                    message.from_me = (message.from.barejid == Strophe.getBareJidFromJid(mod.connection.jid));
                    col.add(message);
                }
                self.trigger("xmpp:message", model);
            });
        }

    });

    this.on("start", function() {
        //app.reqres.setHandler("xmpp:message:send", this.sendMessage);
    });

    this.startChat = function(who) {
        var model = mod.rosterCollection.get(who);
        this.trigger("xmpp:startchat", model);
    };

    this.sendMessage = function(to_jid, message) {
        mod.connection.messaging.send(to_jid, message);

        var from_jid = mod.connection.jid;
        var messageData = {
            //id:    .getAttribute('id'),
            from : {
                jid:  from_jid,
                barejid:  Strophe.getBareJidFromJid(from_jid),
                node: Strophe.getNodeFromJid(from_jid),
                domain: Strophe.getDomainFromJid(from_jid),
                resource: Strophe.getResourceFromJid(from_jid)
            },
            to : {
                jid:  to_jid,
                barejid:  Strophe.getBareJidFromJid(to_jid),
                node: Strophe.getNodeFromJid(to_jid),
                domain: Strophe.getDomainFromJid(to_jid),
                resource: Strophe.getResourceFromJid(to_jid)
            },
            type: "chat",
            body:  message,
            html_body: null,
            from_me : (Strophe.getBareJidFromJid(from_jid) == Strophe.getBareJidFromJid(mod.connection.jid))
        };

        var model = mod.rosterCollection.get(to_jid);
        model.get("messages").add(messageData);

        this.trigger("xmpp:message:sent", model);
        return true;
    };

    this.GetAmI = function() {
        return Strophe.getBareJidFromJid(mod.connection.jid);
    };
    /* GETTING USER CHAT LIST */


});
