// USE THIS MODULE AS SKELETON TO ANOTHER, 
// 
// TODO: CREATE A JS MODULE UNIFIED MODULE TYPE, DEFINE FIRST, CODE AFTER
$SC.module("dialogs.enroll.avaliable", function(mod, app, Backbone, Marionette, $, _) {

    var baseModel = app.module("models").getBaseModel();

    mod.models = {
        enroll : {
            user : baseModel.extend({
                response_type : "reload",
                //idAttribute : "user_id",
                urlRoot : "/module/enroll/item/users"
                /*
                urlRoot : function() {
                    return "/module/enroll/item/users/" + this.get("role_id")
                } 
                */
            })
        }
    };

    mod.on("start", function(opt) {
        var dialogViewClass = $SC.module("views").dialogViewClass;
        
        var enrollAvaliableDialogViewClass = dialogViewClass.extend({
            events : {
                "click .enroll-action" : "enrollUser"
            },
            initialize : function() {
                dialogViewClass.prototype.initialize.apply(this);
                var self = this;

                this.$el.on('shown.bs.modal', function() {
                    self.$('.carroussel').bxSlider({
                      minSlides: 2,
                      maxSlides: 2,
                      slideWidth: 420,
                      slideMargin: 10,
                      adaptiveHeight : true,
                      responsive : true,
                      infiniteLoop : false
                    });
                });
            },
            enrollUser : function(e) {
                var enroll_id = $(e.currentTarget).data("enrollId")
                var program_id = $(e.currentTarget).data("programId")

                model = new mod.models.enroll.user({
                    enroll_id : enroll_id,
                    course_id : program_id,
                    //user_id : typeId[1]
                });
                model.save();
            }
        });
        

        this.dialogView = new enrollAvaliableDialogViewClass({
            el : "#dialogs-enroll-avaliable"
        });

    });
});
