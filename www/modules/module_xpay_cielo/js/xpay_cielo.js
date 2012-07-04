jQuery(function($) {
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
});