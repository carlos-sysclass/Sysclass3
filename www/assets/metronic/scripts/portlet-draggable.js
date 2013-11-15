var PortletDraggable = function () {

    return {
        //main function to initiate the module
        init: function () {

            if (!jQuery().sortable) {
                return;
            }

            var handleEmptyList = function( event, ui ) {
                $("#sortable_portlets .column").each(function(i, el) {
                    jQuery(el).removeClass("sortable-box-placeholder round-all").css("height","");
                    if (jQuery(el).find(":not(.sortable-box-placeholder)").size() == 0) {
                        jQuery(el).addClass("sortable-box-placeholder round-all").css("height","100px");
                    } else {
                        //jQuery(el).text("");
                    //jQuery(el).find(".sortable-box-placeholder").remove();
                    }
                });
            }

            $("#sortable_portlets .sortable").sortable({
                connectWith: ".sortable",
                items: ".portlet",
                opacity: 0.8,
                forceHelperSize: true,
                placeholder: 'sortable-box-placeholder round-all',
                forcePlaceholderSize: true,
                tolerance: "intersect",
                dropOnEmpty : true,
                create: handleEmptyList,
                update: handleEmptyList
            });
            //$("#sortable_portlets .sortable").css("height", "100%");

            $(".sortable").disableSelection();

        }

    };

}();