(function(jQuery){
	var topics = {};
	jQuery.Topic = function( id ) {
	    var callbacks;
	    var method;
	    //var topic = id;
	    //var topics[ id ];
	    if ( !topics[ id ] ) {
	        callbacks = jQuery.Callbacks();
	        topic = {
	            publish: callbacks.fire,
	            subscribe: callbacks.add,
	            unsubscribe: callbacks.remove
	        };
	        if ( id ) {
	            topics[ id ] = topic;
	        }
	    }
	    return topics[ id ];
	};
})(jQuery); // plugin code ends