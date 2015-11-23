$SC.module("views.translate.view.token", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var tableViewClass = Backbone.View.extend({
			translateEditTokenDialog : null,
			events : {
				"click .datatable-option-remove"	: "removeItem"
			},
			initialize : function(opt) {
				//this.oOptions = $.extend($.fn.dataTable.defaults, datatabledefaults, opt.datatable);
				var self = this;
				this.oSettings = {};

				if (opt.datatable != undefined) {
					this.oSettings = opt.datatable;
					/*
					this.oSettings.columns = [
						{ "name": "src", "render": function ( data, type, full, meta ) {
	      					return full[self.srclang];
	    				}},
						{ "name": "dst", "render": function ( data, type, full, meta ) {
	      					return full[self.dstlang];
	    				}},
						{ "mData": "options", 'sType' : 'table-options' }
					];
					*/
				}

				this.recreateTable();
			},
			editItem : function(e) {
				var data = this.oTable._($(e.currentTarget).closest("tr"));
				data = data[0];
				//console.warn(data[0]);
				e.preventDefault();

				var modelData = {
					token 			: data['token'],
					text			: data[this.dstlang],
					language_code	: this.dstlang,
					srclang 		: this.srclang,
					dstlang 		: this.dstlang
				};

				var translateEditTokenModelClass = app.module("models.translate").translateEditTokenModelClass;
				var translateEditTokenModel = new translateEditTokenModelClass(modelData);
				
				if (this.translateEditTokenDialog == null) {
					var translateEditTokenDialogClass = app.module("dialog.translate.edit").translateEditTokenDialogClass;
					this.translateEditTokenDialog = new translateEditTokenDialogClass();
					var self = this;
					this.translateEditTokenDialog.on("token:save", function() {
						self.oTable.api().ajax.reload(null, false);
					});
				}
				this.translateEditTokenDialog.setModel(translateEditTokenModel);
		 		this.translateEditTokenDialog.open();
			},
			translateItemWindows : function(e) {
				var data = this.oTable._($(e.currentTarget).closest("tr"));
				data = data[0];
				//console.warn(data[0]);
				e.preventDefault();

				var modelData = {
					token 			: data['token'],
					text			: data[this.srclang],
					language_code	: this.dstlang,
					srclang 		: this.srclang,
					dstlang 		: this.dstlang
				};

				var translateWindowsTokenModelClass = app.module("models.translate").translateWindowsTokenModelClass;
				var translateWindowsTokenModel = new translateWindowsTokenModelClass(modelData);

				if (this.translateEditTokenDialog == null) {
					var translateEditTokenDialogClass = app.module("dialog.translate.edit").translateEditTokenDialogClass;
					this.translateEditTokenDialog = new translateEditTokenDialogClass();
					var self = this;
					this.translateEditTokenDialog.on("token:save", function() {
						self.oTable.api().ajax.reload(null, false);
					});
				}

				this.listenToOnce(translateWindowsTokenModel, "sync", function() {
					this.translateEditTokenDialog.setModel(translateWindowsTokenModel);
			 		this.translateEditTokenDialog.open();
				}, this);

				translateWindowsTokenModel.fetch();
			},
			removeItem : function(e) {
				/*
				e.preventDefault();
				var data = this.oTable._($(e.currentTarget).closest("tr"));
				var newsModelClass = app.module("models.news").newsModelClass;

				var self = this;
				var model = new newsModelClass(data[0]);
				model.destroy({
					success : function() {
						$(e.currentTarget).closest("tr").remove();
					}
				});
				*/
			},
			recreateTable : function() {
				if ( $.fn.DataTable.isDataTable(this.el) ) {
					this.$el.fnDestroy();
				}
				this.oTable = this.$el.dataTable(this.oSettings);

				this.$el.closest(".dataTables_wrapper").find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
				this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
				this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').select2(); // initialize select2 dropdown

				var self = this;
				this.oTable.on( 'draw.dt', function () {
					app.module("ui").refresh(self.$el);
				});

				//this.oSettings = this.oTable.api().settings();
				//console.warn(this.oSettings);
			}
		});

		var translateViewClass = Backbone.View.extend({
			/*
			events : {
				"click .datatable-option-remove" : "removeItem"
			},
			*/
			initialize : function(opt) {
				//this.oOptions = $.extend($.fn.dataTable.defaults, datatabledefaults, opt.datatable);
				var self = this;
				/*
				this.$(".select2-me[name='src_language']").on("change", function(e) {
					self.tableView.setSourceColumn(e.val);
					//self.$(".select2-me[name='dst_language'] option[value='" + e.val + "']").attr("disabled", "disabled");

				});
				this.$(".select2-me[name='dst_language']").on("change", function(e) {
					self.tableView.setDestinationColumn(e.val);
					//self.$(".select2-me[name='src_language'] option[value='" + e.val + "']").attr("disabled", "disabled");
				});
				*/
				this.tableView = new tableViewClass({
					el : this.$("table#translate-table"),
					datatable : opt.datatable
				});
			}
		});

		var translateView = new translateViewClass({
			el : "#translate-view",
			datatable : {
				sAjaxSource	: "/module/translate/datasources/me/datatable",
				columns 	: [
					{ "name": "code", "mData": "code", "sClass" : "text-center"},
					{ "name": "country_code", "mData": "country_code", 'sType' : 'table-image'},
					{ "name": "name", "mData": "name"},
					{ "name": "local_name", "mData": "local_name"},
					{ "name": "rtl", "mData": "rtl", 'sType' : 'table-boolean'},
					{ "name": "active", "mData": "active", 'sType' : 'table-boolean'},
					{ "name": "options", "mData": "options", 'sType' : 'table-options' }
				]
			}
		});

	});
});