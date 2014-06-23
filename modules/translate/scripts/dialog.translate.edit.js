$SC.module("dialog.translate.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	//this.startWithParent = false;
	mod.addInitializer(function(collectionData) {
		/*
		// FETCH COLLECTION FROM SERVER
		var conditionCollectionClass = Backbone.Collection.extend({
			model : conditionModelClass,
			url : "/module/permission/items/me"
		});
		*/
		/*
		var permissionViewClass = Backbone.View.extend({
		 	events : {
		 		"click .new-permission-action" : "open_dialog"
		 	},
		 	//nofoundTemplate : _.template($("#permission-block-nofound-template").html()),
		 	initialize : function() {
		 		var conditionModel = new conditionModelClass();
		 		this.listenTo(this.collection, "sync", this.render.bind(this));
		 		this.listenTo(this.collection, "add", this.addOne.bind(this));

		 		var self = this;

			 	this.permissionViewDialog = new permissionViewDialogClass({
			 		el : "#permission-add-dialog-modal"
			 	}).on("permission:save", function(model) {
			 		mod.trigger("before:save", model);
		 			model.save(null, {
		 				success : function(model,data,xhr) {
		 					// INJECT MODEL ON TABLE
		 					self.trigger("permission:add", model);
		 					self.collection.add(model);
		 					self.permissionViewDialog.close();
		 					mod.trigger("before:save", model);
		 				}
		 			});
		 		});
		 	},
		 	open_dialog : function() {
		 		this.permissionViewDialog.setModel(new conditionModelClass());
		 		this.permissionViewDialog.open();
		 	},
		 	render : function() {
		 		this.$("table tbody").empty();
		 		var self = this;
		 		if (this.collection.size() == 0) {
		 			this.$("table tbody").append(this.nofoundTemplate());
		 		} else {
			 		this.collection.each(function(model, i) {
			 			var permissionItemView = new permissionItemViewClass({model : model, index : i});
			 			self.$("table tbody").append(permissionItemView.render().el);
			 		});
		 		}
		 	},
		 	addOne : function(model) {
	 			var permissionItemView = new permissionItemViewClass({model : model});
	 			this.$("table tbody").append(permissionItemView.render().el);
		 	}
		});

		var permissionItemViewClass = Backbone.View.extend({
		 	events : {
		 		"click .permission-item-remove-action" : "destroy"
		 	},
		 	tagName : "tr",
		 	//itemTemplate : _.template($("#permission-block-item-template").html()),
		 	initialize : function() {
		 		var conditionModel = new conditionModelClass();
		 		//this.listenTo(this.collection, "sync", this.render.bind(this));
		 		this.listenTo(this.model, "change", this.render.bind(this));
		 	},
		 	render : function() {
		 		this.$el.empty().append(this.itemTemplate(this.model.toJSON()));
		 		return this;
		 	},
		 	destroy : function(e) {
		 		e.preventDefault();
		 		this.$el.fadeOut(1000, function() {
		 			$(this).remove();
		 		});
		 		this.model.destroy();
		 	}
		});
		*/
		var baseFormClass = $SC.module("views").baseFormClass;

		this.translateEditTokenDialogClass = baseFormClass.extend({
			el : "#translate-edit-token-modal",
			/*
		 	events : {
		 		"click .save-permission-action" : "save"
		 	},
		 	*/
		 	initialize : function() {
				this.$el.modal({
					show : false
				});
				baseFormClass.prototype.initialize.apply(this);
	    		//this.$("[name='condition_id']").on("change", this.changeCondition.bind(this));
		    },
		    setModel : function(model) {
		    	// UNLISTEN FROM OLD MODEL
		    	if (this.model != undefined) {
		    		this.stopListening(this.model);
		    	}
		    	this.model = model;

		    	this.render();
		    	//this.listenTo(this.model, "change:condition_id", this.loadCondition, this);
		    	//this.$("[name='condition_id']").trigger("change");
		    },
		 	save : function() {
		 		/*
		 		jsonObj	= {};
		 		this.$("#permission-add-dialog-options :input").each(function() {
		 			if ($(this).attr("name") != undefined) {
						jsonObj[$(this).attr("name")] = $(this).val();
					}
		 		});
		 		this.model.set("data", jsonObj);
		 		*/
		 		baseFormClass.prototype.save.apply(this);

		 		console.warn(this.model.toJSON());
		 		this.trigger("permission:save", this.model);
		 	},
		 	open : function() {
		 		this.$el.modal('show');

		 	},
		 	close : function() {
		 		this.$el.modal('hide');
		 	}
		});

		//var conditionCollection = new conditionCollectionClass;
		//var permissionView = new permissionViewClass({el : "#permission-block", collection : conditionCollection});
		//conditionCollection.fetch();
		//permissionView.render();
		//
		// Exports
		//this.conditionCollection = conditionCollection;
		//this.conditionCollection.fetch({data : collectionData});

	});
});