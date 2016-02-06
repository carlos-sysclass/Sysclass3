$SC.module("blocks.enroll.courses", function(mod, app, Backbone, Marionette, $, _) {

    this.startWithParent = false;
    this.started = false;



    mod.on("start", function(formView) {
        this.started = true;

        var baseModelClass = app.module("models").getBaseModel();

        mod.models = {
            course : baseModelClass.extend({
                //idAttribute : ["course_id", "enroll_id"],
                response_type : "object",
                urlRoot : "/module/enroll/item/courses"
            })
        };

        var tableViewClass = $SC.module("utils.datatables").tableViewClass;

        var enrollCoursesTableViewClass = tableViewClass.extend({
            getTableItemModel : function(data) {
                return new mod.models.course(data);
            }
        });

        var enrollCoursesViewClass = Backbone.View.extend({
            initialize: function() {
                console.info('courses.form/enrollCoursesViewClass::initialize');
                //baseDatatableViewClass.prototype.initialize.apply(this);

                var block_context = app.getResource("enroll_courses_context");
                this.tableView = new enrollCoursesTableViewClass({
                    el : "#view-enroll_courses",
                    datatable : {
                        "sAjaxSource": block_context.sAjaxSource + "/" + JSON.stringify({enroll_id : formView.model.get("id")}),
                        "aoColumns": block_context.datatable_fields
                    }
                });

                var self = this;

                this.select2Obj = this.$(".select2-me");

                this.select2Obj.select2("destroy");
                this.select2Obj.data("url", 
                    this.select2Obj.data("url") + JSON.stringify({
                        enroll_id : formView.model.get("id"),
                        exclude : true
                    })
                );

                app.module("ui").handleSelect2(this.$el);


                this.select2Obj.on("change", function (e, a,b,c,d) { 
                    var data = e.added;
                    var model = new mod.models.course({
                        'enroll_id' : formView.model.get("id"),
                        'course_id' : data['id']
                    });
                    model.save();
                    this.tableView.refresh();
                }.bind(this));
            }
        });

        this.dialogView = new enrollCoursesViewClass({
            el : "#blocks-enroll-courses"
        });

    });

    $SC.module("crud.views.edit").on("start", function() {
        if (!mod._isInitialized && this.getForm) {
            mod.start(this.getForm());
        }
    });
});
