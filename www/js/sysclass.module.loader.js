(function( $ ){
    var __modules = {};
    if (typeof(window['_mod_data_']) == 'undefined') {
        window['_mod_data_'] = {};
    }

    var methods = {
        modules : function() {
            return __modules;
        },
        register : function(name, methods) {
            mod_config = {};
            if (typeof(window['_mod_data_']['_' + name + '_']) != 'undefined') {

                for(idx in window['_mod_data_']['_' + name + '_']) {
                    newIndex = idx.replace(/[a-z0-9_]+\./gi, "");
                    mod_config[newIndex] = window['_mod_data_']['_' + name + '_'][idx]; 
                }
                //mod_config = window['_mod_data_']['_' + name + '_'];
            }

            opt_config = jQuery.extend(
                    {
                        "ajax" : {
                            "async" : true
                        },
                       "noMessages"	: false
                    },
                    mod_config
            );

            __modules[name] = jQuery.extend(true, {fake : false}, {"opt" : opt_config}, methods, parentWrapper, {"name" : name});

            __modules[name].init();

            return __modules[name];
        },
        load : function( name ) {
            /* STATIC CALL */
            if (parentWrapper.exists.apply({name : name, fake : false})) {
                return __modules[name];
            }
            return methods.register.apply( this, [name, {fake : true}] );
        },
        publish : function(eventName) {
            jQuery.Topic(eventName).publish.apply(jQuery.Topic(eventName), Array.prototype.slice.call( arguments, 1 ));
        },
        subscribe : function(eventName, callback) {
            jQuery.Topic(eventName).subscribe(callback);
        }
    };
    /* ANCESTOR MODULE CLASS */
    var parentWrapper = {
        exists : function() {
            return (typeof(__modules[this.name]) != 'undefined' && this.fake == false);
        },
        isFake : function() {
            return this.fake;
        },
        config : function(name) {
            return this.opt;
        },
        sync: function(bSwitch) {
            if (typeof(bSwitch) != "boolean") {
                output = "json";
            }
            this.opt.ajax.async = !bSwitch;

            return this;
        },
        init: function() {
            /* EXPERIMENTAL CODE- -- MODVE TO PARENT WHEN APLICABLE */
            for (var i in this.opt.blocks) {
                _sysclass("load", "block").inject(
                        this.opt.blocks[i].type,
                        this.opt.blocks[i].selector,
                        this.opt.blocks[i].data
                        );
            }
        },
        _getActionUrl : function(actionName, sendData) {
            var url = this.opt.baseUrl + "&action=" + actionName;

            if (typeof(sendData) == 'object') {
                url = url + "&" + jQuery.param(sendData); 
            }

            return url;
        },
        _loadAction : function(actionName, sendData, selector, callback) {
            var url = 
                window.location.protocol + "//" +
                window.location.hostname +
                window.location.pathname + 
                "?ctg=module&op=module_" + this.name +
                "&action=" + actionName;

            jQuery(selector).load(
                    url,
                    sendData,
                    function(data, status) {
                        if (typeof(callback) == 'function') {
                            callback(data, status);
                        }
                    }
                    );
        },
        _redirectAction : function(actionName, sendData) {
            if (actionName == null) {
                actionName = this.action;
            }

            var url = 
                window.location.protocol + "//" +
                window.location.hostname +
                window.location.pathname + 
                "?ctg=module&op=module_" + this.name +
                "&action=" + actionName + 
                "&" + jQuery.param(sendData);

            window.location.href = url;
            return;
        },
        _getAction : function(actionName, sendData, callback, output) {
            this.__requestAjax(actionName, sendData, callback, output, "get");
        },
        _postAction : function(actionName, sendData, callback, output) {
            this.__requestAjax(actionName, sendData, callback, output, "post");
        },
        __requestAjax : function(actionName, sendData, callback, output, method) {
            if (typeof(output) === "undefined" || output === null || output === "") {
                output = "json";
            }

            var self = this;

            var url = 
                window.location.protocol + "//" +
                window.location.hostname +
                window.location.pathname + 
                "?ctg=module&op=module_" + this.name +
                "&action=" + actionName + "&output=" + output;

            if (this.opt.noMessages) {
                var callbackWrapper = callback;
            } else {
                var callbackWrapper = function(data, status) {
                    if (output == "json") { 
                        jQuery.messaging.show(data);
                    }
                    if (typeof(callback) == 'function') {
                        callback(data, status);
                    }
                };
            }

            var options = jQuery.extend(this.opt.ajax, {
                "type" 		: method,
                "data"		: sendData,
                "dataType"	: output,
                "success"	: callbackWrapper
            });

            jQuery.ajax(
                    url,
                    options
                    );
        }
    };
    /* MAIN LOADER CLASS */
    _sysclass = function( method ) {
        /* CLASS CONSTRUCTOR */
        var self = _sysclass;
        if ( methods[method] ) {
            return methods[method].apply( self, Array.prototype.slice.call( arguments, 1 ));
            //} else if ( typeof method === 'object' || ! method ) {
        } else {
            return methods.load.apply( self, arguments );
        }
    };



    // REGISTER UTILS CLASSES
    var utilsMethods = {
        sanitizeDOMString : function (value) {
            return new String(value).replace(/\./g, "_");
        }
    };

    _sysclass("register", "utils", utilsMethods);

    // REGISTER UTILS CLASSES
    var i18nMethods = {
        text : function (token) {
            if (typeof($languageJS[token]) == 'undefined') {
                return token;
            }
            return $languageJS[token];
        }
    };

    _sysclass("register", "i18n", i18nMethods);

    // REGISTER UTILS CLASSES
    var blockMethods = {
        inject : function (type, selector, data) {
            if (type == "autocategorycomplete") {
                return this._create_autocategorycomplete(selector, data);
            }
            return false;
        },
        _create_autocategorycomplete : function(selector, data) {
            var defaults = {
                delay: 0,
                minLength: 0,
                select: function( event, ui ) {
                    jQuery(this).blur();
                    _sysclass("publish", "autocategorycomplete-select", this, ui.item);
                }
            };
            data = jQuery.extend(true, defaults, data);

            jQuery(selector).val(data.value).autocategorycomplete(data).focus(function() {
                jQuery(this).val("");
                jQuery(this).autocategorycomplete( "search", "" );
            });

            _sysclass("publish", "autocategorycomplete-start", jQuery(selector).get(0));

        }
    };

    _sysclass("register", "block", blockMethods);

})( jQuery );
