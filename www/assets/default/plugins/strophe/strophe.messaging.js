Strophe.addConnectionPlugin('messaging', {

    _connection: null,

    init: function (conn) {
        this._connection = conn;
        Strophe.addNamespace('XHTML_IM', 'http://jabber.org/protocol/xhtml-im');
        Strophe.addNamespace('XHTML', 'http://www.w3.org/1999/xhtml');
        _.extend(this, Backbone.Events);
    },

    // Register message notifications when connected
    statusChanged: function (status, condition) {
        if (status === Strophe.Status.CONNECTED || status === Strophe.Status.ATTACHED) {
            this._connection.addHandler(this._onReceiveChatMessage.bind(this), null, 'message', 'chat');
        }
    },

    // Upon message receipt trigger an `xmpp:message` event.
    _onReceiveChatMessage: function (message) {
        var body, html_body;
        body = $(message).children('body').text();
        if (body === '') {
            return true; // Typing notifications are not handled.
        }
        html_body = $('html[xmlns="' + Strophe.NS.XHTML_IM + '"] > body', message);
        if (html_body.length > 0) {
            html_body = $('<div>').append(html_body.contents()).html();
        } else {
            html_body = null;
        }

        var from_jid = message.getAttribute('from');
        var to_jid = message.getAttribute('to');

        this.trigger('xmpp:message', {
            id:   message.getAttribute('id'),
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
            type: message.getAttribute('type'),
            body: body,
            html_body: html_body
        });
        return true;
    },

    // **send** sends a message. `body` is the plaintext contents whereas `html_body` is the html version.
    send: function (to, body, html_body) {
        var msg = $msg({to: to, type: 'chat'});

        if (body) {
            msg.c('body', {}, body);
        }

        if (html_body) {
            msg.c('html', {xmlns: Strophe.NS.XHTML_IM})
                .c('body', {xmlns: Strophe.NS.XHTML})
                .h(html_body);
        }

        this._connection.send(msg.tree());
    }
});
