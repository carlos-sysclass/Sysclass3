$SC.module("dialogs.questions.select", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    this.config = dialogs_questions_select;

    this.started = false;

    mod.on("start", function(opt){
        // MOVE THIS MODEL TO ANOTHER MODULE
        var questionModelClass = Backbone.Model.extend({});

        var questionDialogViewClass = Backbone.View.extend({
            initialize : function() {
                // CREATE DIALOG
                this.$el.modal({
                    show : false,

                });
                // CREATE TABLE SUB-VIEW
                var baseDatatableViewClass = app.module("views").baseDatatableViewClass;
                var sAjaxSource = "/module/questions/items/lesson-content/datatable/";

                var tableViewClass = baseDatatableViewClass.extend({
                    onSelectItem : function(e) {
                        var data = this.oTable._($(e.currentTarget).closest("tr"));
                        var model = new questionModelClass(data[0]);
                        mod.trigger("select:item", e, model);
                    }
                });

                this.tableView = new tableViewClass({
                    el : "#questions-select-modal-table",
                    datatable : {
                        "sAjaxSource": sAjaxSource,
                        "aoColumns": mod.config.datatable_fields
                    }
                });

                mod.started = true;
            }
        });

        this.setFilter = function(filter) {
            // FILTER DATATABLE
            this.filter = filter;

            var url = "/module/questions/items/lesson-content/datatable/" + JSON.stringify(this.filter);

            this.dialogView.tableView.oTable.fnReloadAjax( url );
        };

        this.dialogView = new questionDialogViewClass({
            el : "#questions-select-modal"
        });

        mod.open = function() {
            mod.dialogView.$el.modal('show');
        };
        mod.close = function() {
            mod.dialogView.$el.modal('hide');
        };
    });
});
