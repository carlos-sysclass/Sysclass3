$SC.module("dialog.permission", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {

		 var permissionViewClass = Backbone.View.extend({
		 	events : {
		 		"click .new-permission-action" : "new_permission"
		 	},
		 	initialize : function() {
//		 		console.log(this.el);
		 	},
		 	new_permission : function() {
		 		var permissionViewDialog = new permissionViewDialogClass({el : "#permission-add-dialog-modal"});
		 		//permissionViewDialog.open();
		 		/*
		 		var $modal = $();
		 		$('body').modalmanager('loading');
				$modal.load('/module/permission/dialog/add', '', function() {
            		app.module("ui").refresh(this);
                	$modal.modal();
                });
				*/
		 	}
		});

		 var permissionViewDialogClass = Backbone.View.extend({
		 	initialize : function() {
				this.$el.modal();
				console.log(this.$("[name='condition_id']"));
	    		this.$("[name='condition_id']").on("change", this.loadCondition.bind(this));
		    },
		    loadCondition : function(e) {
	    		//console.info('views/baseSelect2CollectionViewClass::insertModel');
	    		var data = $(e.currentTarget).select2("data");
	    		var condition_id = data.id;
	    		this.$("#permission-add-dialog-options").load('/module/permission/get/options/' + condition_id, '', function() {
            		app.module("ui").refresh(this);
                });
	    		console.log(data.id);
	    		// [:condition_id].html
	    		// MAKE A REQUEST TO 
		 	}/*,
		 	open : function() {
		 		//$('body').modalmanager('loading');
		 		var self = this;
				this.$el.load('/module/permission/dialog/add', '', function() {
            		app.module("ui").refresh(this);
                	$(this).modal();
                });
		 	}*/
		});

		var permissionView = new permissionViewClass({el : "#permission-block"});
	});
});