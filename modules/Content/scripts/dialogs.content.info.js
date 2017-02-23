$SC.module("dialogs.content.info", function(mod, app, Backbone, Marionette, $, _) {

    // MODELS
    this.startWithParent = false;

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

    mod.open = function() {
        mod.dialogView.open();
    };
    mod.close = function() {
        mod.dialogView.close();
    };

    mod.setInfo = function(info) {
        this.dialogView.setModel(info.model);

        this.dialogView.render();

        return this; // for chaining
    };

    var dialogViewClass = app.module("views").dialogViewClass;

    var questionDialogViewClass = dialogViewClass.extend({
        templates : {},
        initialize : function() {
            dialogViewClass.prototype.initialize.apply(this, arguments);

            // CREATE SUB-VIEWS
            // MENU DROPDOW
            // VIDEO
        },
        setModel : function(model) {
            dialogViewClass.prototype.setModel.apply(this, arguments);
        },
        render : function() {
            dialogViewClass.prototype.render.apply(this, arguments);

            if (this.model.isCourse()) {
                // LOAD TEMPLATES ON THE FLY
                var type = 'course';
            } else {
                return this;
            }

            this.loadTemplates(type);

            this.$(".info-header").html(
                this.templates[type + '-header'](this.model.toJSON())
            );
            this.$(".info-body").html(
                this.templates[type + '-body'](this.model.toJSON())
            );
            //this.$("[data-update='course.name']").html(this.model.get("course").get("name"));
        },
        loadTemplates : function(type) {
            if (type == "course") {
                if (!_.has(this.templates, 'course-header')) {
                    this.templates['course-header'] = _.template(
                        $("#content_course_dialog_header").html(),
                        null,
                        {variable: "model"}
                    );
                }
                if (!_.has(this.templates, 'course-body')) {
                    this.templates['course-body'] = _.template(
                        $("#content_course_dialog_body").html(),
                        null,
                        {variable: "model"}
                    );
                }
            }
        }

    });

    mod.on("start", function(opt) {
        this.started = true;

        this.dialogView = null;

        if (_.has(opt, 'modelClass')) {
            this.modelClass = opt.modelClass;
        } else {
            this.modelClass = Backbone.Model;
        }

        this.dialogView = new questionDialogViewClass({
            el : "#content-info-modal",
            model : new mod.modelClass(),
            collection : new Backbone.Collection()
        });
        //this.dialogView.render();

        // CREATE TABLE SUB-VIEW
        //var baseDatatableViewClass = app.module("views").baseDatatableViewClass;
        //var sAjaxSource = "/module/questions/items/lesson-content/datatable/";

        //var config = app.getResource("questions-select_context");
        /*
        var tableViewClass = app.module("utils.datatables").tableViewClass;
        this.tableView = new tableViewClass({
            el : "#questions-select-table",
            datatable : config
        });
        */

    });
});
