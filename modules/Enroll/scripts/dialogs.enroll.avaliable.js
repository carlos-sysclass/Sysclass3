// USE THIS MODULE AS SKELETON TO ANOTHER, 
// 
// TODO: CREATE A JS MODULE UNIFIED MODULE TYPE, DEFINE FIRST, CODE AFTER
$SC.module("dialogs.enroll.avaliable", function(mod, app, Backbone, Marionette, $, _) {

    mod.on("start", function(opt) {
        var dialogViewClass = $SC.module("views").dialogViewClass;
        
        
        var enrollAvaliableDialogViewClass = dialogViewClass.extend({
            initialize : function() {
                dialogViewClass.prototype.initialize.apply(this);
                var self = this;

                this.$el.on('shown.bs.modal', function() {
                    self.$('.carroussel').bxSlider({
                      minSlides: 2,
                      maxSlides: 2,
                      slideWidth: 400
                    });
                });
            }
        });
        

        this.dialogView = new enrollAvaliableDialogViewClass({
            el : "#dialogs-enroll-avaliable"
        });

    });
});
