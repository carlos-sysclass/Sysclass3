$SC.module("dialogs.tests.info", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    //this.config = dialogs_questions_select;

    this.started = false;

    mod.on("start", function(opt){
        // MOVE THIS MODEL TO ANOTHER MODULE
        var questionModelClass = Backbone.Model.extend({});

        var testsInfoDialogViewClass = Backbone.View.extend({
            template : _.template($("#tests_info_modal-template").html(), null, {variable : "model"}),
            initialize : function() {
                // CREATE DIALOG
                this.$el.modal({
                    show : false,

                });

                // CREATE TABLE SUB-VIEW
//                var baseDatatableViewClass = app.module("views").baseDatatableViewClass;
//                var sAjaxSource = "/module/questions/items/lesson-content/datatable/";
/*
                var tableViewClass = baseDatatableViewClass.extend({
                    onSelectItem : function(e) {
                        var data = this.oTable._($(e.currentTarget).closest("tr"));
                        var model = new questionModelClass(data[0]);
                        mod.trigger("select:item", e, model);
                    }
                });
*/
/*
                this.tableView = new tableViewClass({
                    el : "#questions-select-modal-table",
                    datatable : {
                        "sAjaxSource": sAjaxSource,
                        "aoColumns": mod.config.datatable_fields
                    }
                });
*/
                mod.started = true;
            },
            setModel : function(model) {
                /*
                if (this.model) {
                    this.stopListening(this.model);
                }
                */
                this.model = model;
                this.render();
            },
            render : function() {

            }
        });

        this.setInfo = function(info) {
            // FILTER DATATABLE
            //this.filter = filter;
            this.model = info.model;
            // LOAD TEST MODEL FROM
            //
            this.dialogView.setModel(info.model);

            //var url = "/module/questions/items/lesson-content/datatable/" + JSON.stringify(this.filter);

            //this.dialogView
        };

        this.dialogView = new testsInfoDialogViewClass({
            el : "#tests-info-modal"
        });

        mod.open = function() {
            mod.dialogView.$el.modal('show');
        };
        mod.close = function() {
            mod.dialogView.$el.modal('hide');
        };
    });
});
