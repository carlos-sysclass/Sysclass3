(function( $ ){
	var __modules = {};
	var methods = {
		modules : function() {
			return __modules;
		},
		register : function(name, methods) {
			__modules[name] = jQuery.extend(true, {fake : false}, methods, parentWrapper, {"name" : name});
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