// USE THIS MODULE AS SKELETON TO ANOTHER, 
// 
// TODO: CREATE A JS MODULE UNIFIED MODULE TYPE, DEFINE FIRST, CODE AFTER
$SC.module("dialogs.enroll.settings", function(mod, app, Backbone, Marionette, $, _) {

    mod.on("start", function(opt) {
        var dialogViewClass = $SC.module("views").dialogViewClass;
        
        var enrollUserDialogViewClass = dialogViewClass.extend({
            renderType : "byView",
        	/*
            initialize: function() {
                console.info('dialogs.enroll.settings/enrollUserDialogViewClass::initialize');
                dialogViewClass.prototype.initialize.apply(this, arguments);
            },
            */
            open : function() {
            	dialogViewClass.prototype.open.apply(this, arguments);

				//var values = this.model.toJSON();
                //console.warn(values);
				//this.renderItens(values);

                this.render();
            },
			setModel : function(model) {
				this.stopListening(this.model);
				model.urlRoot = "/module/enroll/item/courses"
				dialogViewClass.prototype.setModel.apply(this, arguments);

				this.listenToOnce(this.model, "sync", this.close.bind(this));
			}
        });

        this.dialogView = new enrollUserDialogViewClass({
            el : "#dialogs-enroll-settings"
        });

    });
});
