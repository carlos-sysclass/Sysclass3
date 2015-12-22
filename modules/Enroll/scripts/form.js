$SC.module("views.enroll.form", function(mod, app, Backbone, Marionette, $, _) {

	this.startWithParent = false;

    mod.on("start", function(formView) {

    	// INITIALIZE GOUPING-FIXED WIDGET
    	this.groupingFixedBlockViewClass = Backbone.View.extend({
            events : {
                "click .add-item-action" : "addItem"
            },
            initialize : function() {
                this.listenToOnce(this.collection, 'sync', this.render.bind(this));
                //this.listenTo(this.collection, 'add', this.addOne.bind(this));
                //this.listenTo(this.collection, 'add', this.refreshCounters.bind(this));
                this.listenTo(this.collection, 'remove', this.refreshCounters.bind(this));

                this.initializeSortable();
            },
            initializeSortable : function() {
                var self = this;

                this.$(".list-group").sortable({
                    items: "li.list-file-item.draggable",
                    opacity: 0.8,
                    placeholder: 'list-file-item placeholder',
                    dropOnEmpty : true,
                    forceHelperSize : true,
                    forcePlaceholderSize: true,
                    tolerance: "intersect",
                    update : function( event, ui ) {
                        var order = $(this).sortable("toArray", {attribute : "data-roadmap-grouping-id"});

                        self.collection.setOrder(order);

                        self.refreshCounters();
                    },
                    over : function( event, ui ) {
                        $(this).addClass("ui-sortable-hover");
                    },
                    out  : function( event, ui ) {
                        $(this).removeClass("ui-sortable-hover");
                    }
                });
            },
            addItem : function() {
                var self = this;
                var itemModel = new mod.groupingModelClass(null);

                self.collection.add(itemModel);

                this.listenToOnce(itemModel, "sync", function(model) {
                    self.addOne(model);
                    self.refreshCounters();
                });

                if (_.isNull(mod.groupingAddDialog)) {
                    mod.groupingAddDialog = new mod.groupingAddDialogClass({
                        el : "#roadmap-grouping-dialog-modal",
                        model : itemModel
                    });
                } else {
                    mod.groupingAddDialog.setModel(itemModel);

                }
                mod.groupingAddDialog.render();

                mod.groupingAddDialog.open();
            },
            /*
            addItem : function(e) {
                var self = this;

                var itemModel = new mod.classModelClass(null);

                self.collection.add(itemModel);

                var roadmapBlockClassItemView = new mod.roadmapBlockClassItemViewClass({
                    model : itemModel,
                    opened : true
                });

                this.listenTo(roadmapBlockClassItemView, "lesson:updated", function(model) {
                    self.refreshCounters();
                });

                $(roadmapBlockClassItemView.render().el).appendTo( this.$("ul.items-container") );
                roadmapBlockClassItemView.start();
            },
            */
            addOne : function(model) {
                console.info('blocks.roadmap/classLessonsView::addOne');

                var self = this;


                var roadmapBlockItemView = new mod.roadmapBlockItemViewClass({
                    model : model
                });

                $(roadmapBlockItemView.render().el).appendTo(this.$("ul.items-container"));
                roadmapBlockItemView.start();

                this.listenTo(roadmapBlockItemView, "grouping:updated", function(model) {
                    self.refreshCounters();
                });
            },
            refreshCounters : function() {
                console.info('blocks.roadmap.grouping/classLessonsView::refreshCounters');
                var total = this.collection.size();
                this.$("ul.items-container > li.list-file-item .total").html(total);

                this.$("ul.items-container > li.list-file-item").each(function(i, item) {
                    $(this).find(".counter").html(i+1);
                });
            },
            render: function() {
                console.info('blocks.roadmap.grouping/classLessonsView::render');

                var self = this;

                this.collection.each(function(model, i) {
                    self.addOne(model, i);
                });
                //this.refreshCounters();
                app.module("ui").refresh( this.$("ul.items-container ") );

                this.refreshCounters();
            },
            remove : function(e) {
                var fileId = $(e.currentTarget).data("fileId");
                var fileObject = new mod.lessonFileModelClass();
                fileObject.set("id", fileId);
                fileObject.destroy();
                $(e.currentTarget).parents("li").remove();
            }
        });


		new this.groupingFixedBlockViewClass({
			el : ""
		})


    });

    $SC.module("crud.views.edit").on("start", function() {
    	if (!mod._isInitialized && this.getForm) {
           	mod.start(this.getForm());
        }
    });

    $SC.module("crud.views.add").on("start", function() {
    	if (!mod._isInitialized && this.getForm) {
    		mod.start(this.getForm());
    	}
    });
});
