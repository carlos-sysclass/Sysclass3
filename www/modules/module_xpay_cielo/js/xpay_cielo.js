jQuery(function($) {
/*
    $("#xpay-cielo-modal").dialog({
        modal: true,
        autoOpen: true,
        height: '750',
        width: '500',
        draggable: true,
        resizable: false,   
        title: 'Cielo',
        dialogClass : "xpay-cielo-modal"
    }).bind( "dialogclose", function(event, ui) {
    	//window.location.reload(true);
    });
*/    
    
    
    
});


/* MODULE CREATING */
(function( $ ) {
	var methods = {
		startUI : function() {
			jQuery(".xpay-cielo-last-transactions-table").dataTable();
		}
	};

	_sysclass("register", "xpay", methods);
})( jQuery );


/* MODULE FLOW-LOGIC */

(function( $ ){
	_sysclass('load', 'xpay_cielo').startUI();
})( jQuery );