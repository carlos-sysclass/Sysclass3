(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory(require('jquery'));
    } else {
        factory(jQuery || Zepto);
    }

}(function ($) {

    var FC = $.fullCalendar; // a reference to FullCalendar's root namespace
    var View = FC.View;      // the class that all views must inherit from
    var CustomView;          // our subclass

    TimelineView = View.extend({ // make a subclass of View

        initialize: function() {
            console.warn("TimelineView::initialize");
            // called once when the view is instantiated, when the user switches to the view.
            // initialize member variables or do other setup tasks.
            
            View.prototype.initialize.apply(this, arguments);
        },
        renderSkeleton: function() {
            console.warn("TimelineView::renderSkeleton");
            View.prototype.renderSkeleton.apply(this, arguments);

            console.warn(this.el, this.$el);
        },
        /*
        render: function() {
            // responsible for displaying the skeleton of the view within the already-defined
            // this.el, a jQuery element.
            console.warn("TimelineView::render");
            View.prototype.render.apply(this, arguments);
        },
        */
        setHeight: function(height, isAuto) {
            // responsible for adjusting the pixel-height of the view. if isAuto is true, the
            // view may be its natural height, and `height` becomes merely a suggestion.
            console.warn("TimelineView::initialize");
            View.prototype.setHeight.apply(this, arguments);
        },

        renderEvents: function(events) {
            // reponsible for rendering the given Event Objects
            console.warn("TimelineView::initialize");
            View.prototype.renderEvents.apply(this, arguments);
        },

        destroyEvents: function() {
            // responsible for undoing everything in renderEvents
            console.warn("TimelineView::initialize");
            View.prototype.destroyEvents.apply(this, arguments);
        },

        renderSelection: function(range) {
            // accepts a {start,end} object made of Moments, and must render the selection
            console.warn("TimelineView::initialize");
            View.prototype.renderSelection.apply(this, arguments);
        },

        destroySelection: function() {
            // responsible for undoing everything in renderSelection
            console.warn("TimelineView::initialize");
            View.prototype.destroySelection.apply(this, arguments);
        }

    });

    FC.views.timelineView = TimelineView; // register our class with the view system

}));