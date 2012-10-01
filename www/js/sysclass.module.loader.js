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
			//window["$_" + name + "_mod_data"]
			if (typeof(window['_mod_data_']['_' + name + '_']) != 'undefined') {
				
				for(idx in window['_mod_data_']['_' + name + '_']) {
					newIndex = idx.replace(/[a-z0-9]+\./gi, "");
					mod_config[newIndex] = window['_mod_data_']['_' + name + '_'][idx]; 
				}
//				mod_config = window['_mod_data_']['_' + name + '_'];
			}
			
			
			__modules[name] = jQuery.extend(true, {fake : false}, mod_config, methods, parentWrapper, {"name" : name});
			return __modules[name];
		},
		load : function( name ) {
			/* STATIC CALL */
			if (parentWrapper.exists.apply({name : name, fake : false})) {
				return __modules[name];	
			}
			return methods.register.apply( this, [name, {fake : true}] );
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
		_postAction : function(actionName, sendData, callback, output) {
			if (typeof(output) === "undefined" || output === null || output === "") {
				output = "json";
			}
			
			var url = 
				window.location.protocol + "//" +
				window.location.hostname +
				window.location.pathname + 
				"?ctg=module&op=module_" + this.name +
				"&action=" + actionName + "&output=" + output;

			jQuery.post(
				url,
				sendData,
				function(data, status) {
					if (output == "json") { 
						jQuery.messaging.show(data);
					}
						
					if (typeof(callback) == 'function') {
						callback(data, status);
					}
				},
				output
			);
		},
		_loadAction : function(actionName, sendData, selector) {
			var url = 
				window.location.protocol + "//" +
				window.location.hostname +
				window.location.pathname + 
				"?ctg=module&op=module_" + this.name +
				"&action=" + actionName;
			
			jQuery(selector).load(
				url,
				sendData
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
		}
	};
	/* MAIN LOLADER CLASS */
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
})( jQuery );