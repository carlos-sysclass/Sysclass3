/* MODULE CREATING */
(function( $ ) {
	var methods = {
		startUI : function() {
			
			dataTableDefaults = {
				"bJQueryUI": false,
				"bPaginate": true,
				"bLengthChange": true,
				"bFilter": true,
				"bSort": true,
				"bInfo": false,
				"bAutoWidth": true,
				"iDisplayLength"	: 10,
				"aLengthMenu": [[10, 50, 100, -1], [10, 50, 100, "Tudo"]],
				"bDeferRender" : true,
				"sPaginationType": "full_numbers",
				"bScrollCollapse": true,
				"sDom": 't<"datatables-header-controls"ilrp>',
				"oLanguage": {
					"sUrl": window.location.pathname + "?ctg=module&op=module_language&action=get_section&section_id=datatable&output=json"
				}
			};
			
		
			dataTablePaidDefaults = jQuery.extend(true, dataTableDefaults);
			
			jQuery("#xpay-cielo-last-transactions-table").dataTable(dataTablePaidDefaults)
			/* Deve-se atrasar a inicialização para atualização por JSON */
			
			.columnFilter({ 
				aoColumns: [
				    { type: "text" },
				    { type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
					{ type: "text" },
					{ type: "select", values: this.opt.bandeiras },
					{ type: "select", values: this.opt.formas_pagamento },
					{ type: "text" },
					{ type: "select", values: this.opt.statuses },
					null
				]
			});
/*
			jQuery(".xpay-cielo-do-capture-link").click(function() {
				//var oTable = jQuery("#xpay-cielo-last-transactions-table").dataTable();
				
			});
*/
			jQuery("#xpay-cielo-repeatable-table").dataTable(dataTablePaidDefaults)
			/* Deve-se atrasar a inicialização para atualização por JSON */
			.columnFilter({ 
				aoColumns: [
				    { type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
				    { type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
					{ type: "text" },
					{ type: "select", values: this.opt.bandeiras },
					{ type: "text" },
					{ type: "text" },
					null
				]
			});

			
		},
		doCaptureAction : function(transTID) {
			//var oTable = jQuery("#xpay-cielo-last-transactions-table").dataTable();
			
			this._postAction(
				"do_capture",
				{transaction_tid : transTID},
				function(data, response) {
					/// oTable.fnReloadAjax();
					window.location.reload(true);
				},
				'json'
			);
		}
	};

	_sysclass("register", "xpay_cielo", methods);
})( jQuery );


/* MODULE FLOW-LOGIC */

(function( $ ){
	_sysclass('load', 'xpay_cielo').startUI();
})( jQuery );