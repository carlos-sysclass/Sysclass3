$SC.module("dialogs.questions.select", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    //this.config = dialogs_questions_select;

    this.started = false;

    this.getValue = function(callback) {
        //this.dialogView.setModel(new mod.modelClass());
        this.dialogView.open();

        this.stopListening(this.tableView);
        this.listenTo(this.tableView, "action.datatables", function(el, data, action) {
            
            if (action == "select") {
                this.stopListening(this.tableView);
                callback(data);
                this.dialogView.close();
            }
        }.bind(this));
    };

    this.setFilter = function(filter) {
        // FILTER DATATABLE
        this.filter = filter;
        //var url = "/module/questions/items/lesson-content/datatable/" + JSON.stringify(this.filter);
        var url = "/module/questions/items/lesson-content/datatable";
        this.tableView.setUrl(url);

        return this;
    };


    mod.on("start", function(opt) {
        console.warn(opt);
        this.started = true;

        this.dialogView = null;

        if (_.has(opt, 'modelClass')) {
            this.modelClass = opt.modelClass;
        } else {
            this.modelClass = Backbone.Model;
        }

        var dialogViewClass = app.module("views").dialogViewClass;
        var questionDialogViewClass = dialogViewClass.extend({});

        this.dialogView = new questionDialogViewClass({
            el : "#questions-select-modal",
            model : new mod.modelClass()
        });
        this.dialogView.render();

        // CREATE TABLE SUB-VIEW
        //var baseDatatableViewClass = app.module("views").baseDatatableViewClass;
        //var sAjaxSource = "/module/questions/items/lesson-content/datatable/";

        var config = app.getResource("questions-select_context");
        console.warn(config);

        var tableViewClass = app.module("utils.datatables").tableViewClass;
        this.tableView = new tableViewClass({
            el : "#questions-select-table",
            datatable : config
        });        

    });
    /*
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
    */

        /*
        var groupingEditDialogViewClass = dialogViewClass.extend({
            save : function() {
                console.info('dialogs.fixed_grouping.form/groupingEditDialogViewClass::save');
                //this.trigger("selected.dialogsUsersSelect", this.model.toJSON());
                this.close();

                // DESTROY ???
                //console.warn(_.isFunction(callback));
                if (_.isFunction(callback)) {
                    callback(this.model.toJSON(), this.model);
                }
            }
        });
        */
});
