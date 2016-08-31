$SC.module("dialogs.content.unit", function(mod, app, Backbone, Marionette, $, _) {
    // MODELS
    this.startWithParent = false;

    //this.config = dialogs_questions_select;

    this.started = false;
    /*
    this.getValue = function(callback) {
        //this.dialogView.setModel(new mod.modelClass());
        this.dialogView.open();

        this.stopListening(this.tableView);
        this.listenTo(this.tableView, "action.datatables", function(el, data, action, evt) {
            if (evt) {
                evt.preventDefault();
            }
            
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
    */

    mod.getForm = function() {
        return mod.dialogView;
    };
    mod.open = function() {
        mod.dialogView.open();
    };
    mod.close = function() {
        mod.dialogView.close();
    };

    mod.setInfo = function(info) {
        this.dialogView.setModel(info.model);

        console.warn(info.model.toJSON());

        return this; // for chaining
    };

    var dialogViewClass = app.module("views").dialogViewClass;
    var questionDialogViewClass = dialogViewClass.extend({
        initialize : function() {
            dialogViewClass.prototype.initialize.apply(this, arguments);

            // CREATE SUB-VIEWS
            // VIDEO
            // MATERIALS
            

        },
        render : function() {
            dialogViewClass.prototype.render.apply(this, arguments);

            //this.$("[data-update='course.name']").html(this.model.get("course").get("name"));
        }
    });

    mod.on("start", function(opt) {
        console.warn(opt);
        this.started = true;

        this.dialogView = null;

        if (_.has(opt, 'modelClass')) {
            this.modelClass = opt.modelClass;
        } else {
            this.modelClass = Backbone.Model;
        }



        this.dialogView = new questionDialogViewClass({
            el : "#content-unit-modal",
            model : new mod.modelClass()
        });
        this.dialogView.render();

        // CREATE TABLE SUB-VIEW
        //var baseDatatableViewClass = app.module("views").baseDatatableViewClass;
        //var sAjaxSource = "/module/questions/items/lesson-content/datatable/";

        //var config = app.getResource("questions-select_context");
        //console.warn(config);
        /*
        var tableViewClass = app.module("utils.datatables").tableViewClass;
        this.tableView = new tableViewClass({
            el : "#questions-select-table",
            datatable : config
        });
        */

    });
});
