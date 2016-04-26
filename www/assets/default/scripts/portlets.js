$SC.module("portlet", function(mod, app, Backbone, Marionette, $, _){

    /* UTILITY CLASSES */
    mod.blockViewItemClass = Backbone.View.extend({
        initialize: function(opt) {
            console.info('portlet/blockViewItemClass::initialize');
            Marionette.triggerMethodOn(this, "beforeInitialize");

            this.portlet = opt.portlet;
            this.model_index = opt.model_index;
            this.parent = opt.parent;

            Marionette.triggerMethodOn(this, "initialize");
        },
        render : function(e) {
            console.info('portlet/blockViewItemClass::render');
            Marionette.triggerMethodOn(this, "beforeRender");

            this.$el.html(
                this.template(_.extend(this.model.toJSON(), {
                    model_index : this.model_index
                }))
            );

            Marionette.triggerMethodOn(this, "render");
            return this;
        }
    });

    mod.blockViewClass = Backbone.View.extend({
        childViewClass : mod.blockViewItemClass,
        childContainer : null,
        initialize: function(opt) {
            console.info('portlet/blockViewClass::initialize');
            Marionette.triggerMethodOn(this, "beforeInitialize");

            if (!_.isUndefined(this.collection)) {
                this.listenTo(this.collection, 'sync', this.render.bind(this));
                this.listenTo(this.collection, 'add', this.addOne.bind(this));
            } else if (!_.isUndefined(this.model)) {
                this.listenTo(this.model, 'sync', this.render.bind(this));
            }

            this.portlet = opt.portlet;

            Marionette.triggerMethodOn(this, "initialize");
        },
        render : function(e) {
            console.info('portlet/blockViewClass::render');
            Marionette.triggerMethodOn(this, "beforeRender");

            var container = this.$el;
            if (!_.isNull(this.childContainer)) {
                container = this.$(this.childContainer);
            }
            container.empty();

            if (!_.isUndefined(this.collection) && this.collection.size() === 0) {
                container.html(this.nofoundTemplate());

                Marionette.triggerMethodOn(this, "renderEmpty");
            } else {
                var self = this;

                if (!_.isUndefined(this.template)) {
                    container.html(
                        this.template(this.model.toJSON())
                    );
                } else {
                    this.collection.each(function(model, i) {
                        var childView = new self.childViewClass({
                            model : model,
                            portlet : self.portlet,
                            model_index : i,
                            parent : self
                        });
                        container.append(childView.render().el);
                    });
                }
                app.module("ui").refresh(container);
                Marionette.triggerMethodOn(this, "render");
            }


        },
        addOne : function(model) {

            var childView = new this.childViewClass({
                model   : model,
                portlet : this.portlet
            });
            this.$el.append(childView.render().el);
        }
    });

    mod.widgetViewClass = Backbone.View.extend({
        collectionClass : null,
        blockViewClass : mod.blockViewClass,
        blockView : null,
        events : {
            'click .portlet-title > .tools > a.fullscreen'      : 'goFullScreen',
            'click .portlet-title > .tools > a.restorescreen'   : 'goRestoreScreen'
        },
        initialize: function() {
            console.info('portlet/widgetViewClass::initialize');
            if (this.$el.isOnScreen(1, 0.3)) {
                //$(document).off("scroll resize");
                // CALl VIEW START
                this.start();
            } else {

                this.scrollEvent = $(document).on("scroll."+this.cid + " resize"+this.cid, function(e) {
                    //console.warn("isOnScreen", this.$el.isOnScreen(1, 0.3), this);
                    if (this.$el.isOnScreen(1, 0.3)) {
                        $(document).off("scroll."+this.cid + " resize"+this.cid);
                        // CALl VIEW START
                        this.start();
                    }

                }.bind(this));
            }
        },
        start : function() {
            console.info('portlet/widgetViewClass::start');
            //this.triggerMethod("beforeStart");
            Marionette.triggerMethodOn(this, "beforeStart");

            // CREATE SUB-VIEWS AND FETCH MODELS AND COLLECTIONS
            this.collection = new this.collectionClass();

            this.blockView = new this.blockViewClass({
                collection : this.collection,
                el: this.$(".widget-block-view-container"),
                portlet : this.$el
            });

            this.collection.fetch();
            Marionette.triggerMethodOn(this, "start");
        },
        goFullScreen : function(e) {
            if (jQuery(e.currentTarget).is(".disabled")) {
                return false;
            }
            var self = this;
            var timeout = 1000;

            //var type = portlet.data("portlet-type");

            var canGoFullscreen = Marionette.triggerMethodOn(this, "beforeFullScreen") !== false;

            if (canGoFullscreen) {
                var column = this.$el.closest("div[class^='col-lg-']");

                var portlets = $(".page-content .row > div[class^='col-lg-'] > .panel, .page-content .row > div[class^='col-lg-'] > .portlet").not(this.$el);


                portlets.fadeOut({
                    duration: timeout,
                    start : function() {
                        self.prevScroolTop = $("html,body").scrollTop();
                    }
                });
                portlets.promise().done(function() {
                    $("html,body").animate({ scrollTop: 0 }, 400, null, function() {
                        column.addClass("full-width", {
                            duration : timeout
                        });
                    });

                    $(e.currentTarget).removeClass("fullscreen").addClass("restorescreen");
                    $(e.currentTarget).removeClass("glyphicon-fullscreen").addClass("glyphicon-resize-small");

                    self.$el.fadeIn(timeout/2, function() {
                        Marionette.triggerMethodOn(self, "fullScreen");
                    }).addClass("portlet-fullscreen");
                });

            }
        },
        goRestoreScreen : function(e) {
            if (jQuery(e.currentTarget).is(".disabled")) {
                return false;
            }
            var self = this;
            var timeout = 1000;

            //var type = portlet.data("portlet-type");

            var canRestoreScreen = Marionette.triggerMethodOn(this, "beforeRestoreScreen") !== false;

            if (canRestoreScreen) {
                var column = this.$el.closest("div[class^='col-lg-']");

                var portlets = $(".page-content .row > div[class^='col-lg-'] > .panel, .page-content .row > div[class^='col-lg-'] > .portlet").not(this.$el);

                column.removeClass("full-width", timeout, null, function() {
                    portlets.fadeIn(timeout);

                    portlets.promise().done(function() {
                        $(e.currentTarget).removeClass("restorescreen").addClass("fullscreen");
                        $(e.currentTarget).removeClass("glyphicon-resize-small").addClass("glyphicon-fullscreen");


                        $("html,body").animate({ scrollTop: self.prevScroolTop }, 400, null, function() {
                            self.$el.removeClass("portlet-fullscreen");
                            Marionette.triggerMethodOn(self, "restoreScreen");
                        });
                    });

                });
            }
        }
    });


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
        /*
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
        */
        jQuery('body').on('click', '.portlet > .portlet-title a.filter', function (e) {
            e.preventDefault();
            var portlet = jQuery(this).closest(".portlet");
            mod.triggerMethod("filter", e, portlet);
        });
        /*
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
        */
        /*
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
        */
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
