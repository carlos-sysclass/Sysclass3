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
			
			dataTablePaidDefaults = jQuery.extend(true, dataTableDefaults, {
				"aoColumns": [
					null,
					null,
					null,
					{sType : "img-src", sSortDataType : "img-src"},
					null,
					null,
					null
				]
				         
				/*,
				fnFooterCallback : function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
					// Calculate the market share for browsers on this page
					var iFilterValor = 0;
					var iFilterPago = 0;
					var iFilterSaldo = 0;
					for ( var i=0 ; i<aiDisplay.length ; i++ )
					{
						iFilterValor += parseFloat(aaData[ aiDisplay[i] ][6].replace('R$', '').replace('.', '').replace(',','.'));
						iFilterPago += parseFloat(aaData[ aiDisplay[i] ][7].replace('R$', '').replace('.', '').replace(',','.'));
						iFilterSaldo += parseFloat(aaData[ aiDisplay[i] ][8].replace('R$', '').replace('.', '').replace(',','.'));
					}
					
					// Calculate the market share for browsers on this page
					var iPageValor = 0;
					var iPagePago = 0;
					var iPageSaldo = 0;
					for ( var i=iStart ; i<iEnd ; i++ ) {
						iPageValor += parseFloat(aaData[ aiDisplay[i] ][6].replace('R$', '').replace('.', '').replace(',','.'));
						iPagePago += parseFloat(aaData[ aiDisplay[i] ][7].replace('R$', '').replace('.', '').replace(',','.'));
						iPageSaldo += parseFloat(aaData[ aiDisplay[i] ][8].replace('R$', '').replace('.', '').replace(',','.'));
					}

					jQuery(nRow).next().children().eq(1).html(
						Globalize.format( iPageValor, "c" )
					);
					jQuery(nRow).next().children().eq(2).html(
						Globalize.format( iPagePago, "c" )
					);
					jQuery(nRow).next().children().eq(3).html(
						Globalize.format( iPageSaldo, "c" )
					);
					
					jQuery(nRow).next().next().children().eq(1).html(
						Globalize.format( iFilterValor, "c" )
					);
					jQuery(nRow).next().next().children().eq(2).html(
						Globalize.format( iFilterPago, "c" )
					);
					jQuery(nRow).next().next().children().eq(3).html(
						Globalize.format( iFilterSaldo, "c" )
					);
				}
				*/

			});
			
			
			
			
			
			jQuery("#xpay-cielo-last-transactions-table").dataTable(dataTablePaidDefaults).columnFilter({ 
				aoColumns: [
				    { type: "text" },
				    { type: "date-range", sRangeFormat: "De: {from}<br />Até: {to}" },
					{ type: "text" },
					{ type: "select", values: this.opt.bandeiras },
					{ type: "select", values: this.opt.formas_pagamento },
					{ type: "text" },
					{ type: "select", values: this.opt.statuses },
				]
			});
		}
	};

	_sysclass("register", "xpay_cielo", methods);
})( jQuery );


/* MODULE FLOW-LOGIC */

(function( $ ){
	_sysclass('load', 'xpay_cielo').startUI();
})( jQuery );