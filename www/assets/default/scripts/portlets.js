$SC.module("portlet", function(mod, MyApp, Backbone, Marionette, $, _){

	mod.triggerSubMethod = function(type, method, e, portlet, data) {
		if (mod.submodules[type] != undefined) {
			if (mod.submodules[type].triggerMethod(method, e, portlet, data) === false) {
				return false;
			}
        }
        return true;
	};
    mod.onCollapse = function(e, portlet) {
		if (jQuery(e.currentTarget).is(".disabled")) {
        	return false;
        }
		var type = portlet.data("portlet-type");

		if (mod.triggerSubMethod(type, "collapse", e, portlet)) {
			var el = portlet.children(".portlet-body");
			jQuery(e.currentTarget).removeClass("collapse").addClass("expand");
            el.slideUp(200);
		}
	};
    mod.onFilter = function(e, portlet) {
        if (jQuery(e.currentTarget).is(".disabled")) {
            return false;
        }
        var type = portlet.data("portlet-type");

        if (mod.triggerSubMethod(type, "filter", e, portlet)) {
        }
    };
	mod.onExpand = function(e, portlet) {
		if (jQuery(e.currentTarget).is(".disabled")) {
        	return false;
        }
        var type = portlet.data("portlet-type");

		if (mod.triggerSubMethod(type, "expand", e, portlet)) {
			var el = portlet.children(".portlet-body");

            jQuery(e.currentTarget).removeClass("expand").addClass("collapse");
            el.slideDown(200);
		}
	};
    /*
	var oldColumn = "";
    var timeout = 1000;

	mod.onFullscreen = function(e, portlet) {
		if (jQuery(e.currentTarget).is(".disabled")) {
        	return false;
        }
        var type = portlet.data("portlet-type");

		if (mod.triggerSubMethod(type, "fullscreen", e, portlet)) {
            var column = $(e.currentTarget).parents(".portlet").closest("div[class^='col-lg-']");
            console.warn(e.currentTarget, column);
            var portlets = $(".page-content .row > div[class^='col-lg-'] > .panel, .page-content .row > div[class^='col-lg-'] > .portlet");

			jQuery(e.currentTarget).removeClass("fullscreen").addClass("normalscreen");
            jQuery(e.currentTarget).removeClass("glyphicon-fullscreen").addClass("glyphicon-resize-small");

            for(i = 1; i <=12; i++) {
            	if (column.hasClass('col-lg-' + i)) {
                	oldColumn = 'col-lg-' + i;
                    break;
                }
            }

            portlets.fadeOut(timeout/2, function() {
                column.removeClass(oldColumn).addClass("col-lg-12");
                portlet.fadeIn(timeout/2, function() {
                    mod.triggerSubMethod(type, "resized", e, portlet);
                }).addClass("portlet-fullscreen");

            });
        }
	};
	mod.onRestorescreen = function(e, portlet) {
		if (jQuery(e.currentTarget).is(".disabled")) {
        	return false;
        }
        var type = portlet.data("portlet-type");

		if (mod.triggerSubMethod(type, "restorescreen", e, portlet)) {
            var column = jQuery(e.currentTarget).closest("div[class^='col-lg-']");
            var portlets = $(".page-content .row > div[class^='col-lg-'] > .panel, .page-content .row > div[class^='col-lg-'] > .portlet");

			jQuery(e.currentTarget).removeClass("normalscreen").addClass("fullscreen");
            jQuery(e.currentTarget).removeClass("glyphicon-resize-small").addClass("glyphicon-fullscreen");

            portlet.removeClass("portlet-fullscreen").fadeOut(timeout/2, function() {
                column.removeClass("col-lg-12").addClass(oldColumn);
                portlets.fadeIn(timeout/2, function() {
                    mod.triggerSubMethod(type, "resized", e, portlet);
                });
            } );

        }
	};

	mod.onReload = function(e, portlet) {
		if (jQuery(e.currentTarget).is(".disabled")) {
        	return false;
        }
        var type = portlet.data("portlet-type");

		if (mod.triggerSubMethod(type, "reload", e, portlet)) {
            var el = portlet.children(".portlet-body");
            App.blockUI(el);
            var unblockNeeded = true;

            if (mod.submodules[type] != undefined) {
                var submod = mod.submodules[type];

                if (submod.onReload == undefined && submod.collection != undefined) {
                    var unblockNeeded = false;

                    submod.collection.fetch({
                        success : function(collection, response, options) {
                            App.unblockUI(el);
                        }
                    });
                }
            }
            if (unblockNeeded) {
                window.setTimeout(function () {
                    App.unblockUI(el);
                }, 1000);
            }
		}
	};
    */

	mod.onRemove = function(e, portlet) {
		if (jQuery(e.currentTarget).is(".disabled")) {
        	return false;
        }
        var type = portlet.data("portlet-type");

		if (mod.triggerSubMethod(type, "remove", e, portlet)) {
            portlet.remove();
		}
	};
	mod.onSearch = function(e, portlet, search) {
		if (jQuery(e.currentTarget).is(".disabled")) {
        	return false;
        }
        var type = portlet.data("portlet-type");

		if (mod.triggerSubMethod(type, "search", e, portlet, search)) {
            // EXPAND PORLET, CASE IT's COLLAPSED
            //this.triggerMethod("expand", {currentTarget : portlet.find(".portlet-title > .tools > .collapse")}, portlet);

            if (mod.submodules[type] != undefined) {
                var submod = mod.submodules[type];

                if (submod.onSearch == undefined && submod.searchBy != undefined && submod.view != undefined && submod.collection != undefined) {
                    search = search.toUpperCase();

                    if (search.length == 0) {
                        submod.view.render(submod.collection);
                    } else {
                        submod.view.render(
                            new Backbone.Collection(
                                submod.collection.filter(function(model, i, collection) {
                                    return (model.get("title").toUpperCase().indexOf(search) != -1);
                                })
                            )
                        );
                    }
                }
            }
		}
	};


	mod.addInitializer(function(){
		// BINDING PORTLET EVENTS

		// Handles portlet tools & actions
		jQuery('body').on('click', '.portlet > .portlet-title > .tools > .collapse', function (e) {
            e.preventDefault();
			var portlet = jQuery(this).closest(".portlet");
			mod.triggerMethod("collapse", e, portlet);
		});
		jQuery('body').on('click', '.portlet .portlet-title > .tools > .expand', function (e) {
            e.preventDefault();
			var portlet = jQuery(this).closest(".portlet");
			mod.triggerMethod("expand", e, portlet);
		});
        jQuery('body').on('click', '.portlet > .portlet-title a.filter', function (e) {
            e.preventDefault();
            var portlet = jQuery(this).closest(".portlet");
            mod.triggerMethod("filter", e, portlet);
        });
        jQuery('body').on('click', '.portlet > .portlet-title > .tools > a.remove', function (e) {
            e.preventDefault();
			var portlet = jQuery(this).closest(".portlet");
			mod.triggerMethod("remove", e, portlet);
        });
        jQuery('body').on('click', '.portlet > .portlet-title > .tools > a.reload', function (e) {
            e.preventDefault();
			var portlet = jQuery(this).closest(".portlet");
			mod.triggerMethod("reload", e, portlet);
        });
		jQuery('body').on('click', '.portlet > .portlet-title > .tools > a.fullscreen', function (e) {
            e.preventDefault();
			var portlet = jQuery(this).closest(".portlet");
			mod.triggerMethod("fullscreen", e, portlet);
		});
		jQuery('body').on('click', '.portlet > .portlet-title > .tools > a.normalscreen', function (e) {
            e.preventDefault();
			var portlet = jQuery(this).closest(".portlet");
			mod.triggerMethod("restorescreen", e, portlet);
		});
        // CREATE SEARCH POPOVERS
		jQuery('.portlet > .portlet-title > .tools > a.search').each(function (e) {
            jQuery(this).popover(
                jQuery.extend(
                    jQuery(this).data(),
                    {content : jQuery("#" + jQuery(this).data("inject-selector")).html()}
                )
            ).on('show.bs.popover', function () {
                jQuery(this)
                    .closest(".portlet")
                    .animate({opacity: 0.4}, 600)
                    .find(".tools a:not(.search)")
                    .addClass("disabled");
            }).on('shown.bs.popover', function () {
                jQuery(this).data("bs.popover").tip().find("input").focus();
            }).on('hide.bs.popover', function () {
                jQuery(this)
                    .closest(".portlet")
                    .animate({opacity: 1}, 600)
                    .find(".tools a:not(.search)")
                    .removeClass("disabled");
            });
        });

        jQuery('body').on('click', '.portlet > .portlet-title > .tools > a.search', function (e) {
            e.preventDefault();
            if (jQuery(this).is(".disabled")) {
                return;
            }
            if (jQuery(this).data("bs.popover").tip().hasClass("in")) {
                jQuery(this).popover("hide");
            } else {
                jQuery(this).popover("show");
                App.setLastPopedPopover(jQuery(this));
                e.stopPropagation();

                var self = this;
                jQuery(this).data("bs.popover").tip().find("form").on("submit", function() {
					var portlet = jQuery(self).closest(".portlet");
					mod.triggerMethod("search", e, portlet, jQuery(this).find(":input").val());
                    //portlet.trigger("portlet.search", jQuery(this).find(":input").val());
                    jQuery(self).popover("hide");
                    return false;
                });
            }
        });
  	});
});
