(function( $ ){
	  var methods = {
	     toggleContactListBlock : function(index) {
	    	 jQuery(".quick_mails-contact-list").hide();
	    	 jQuery(".quick_mails-contact-list-" + index).toogle('fast');
	     }
	  };
	  _sysclass("register", "quick_mails", methods).toggleContactListBlock(1);
})( jQuery );