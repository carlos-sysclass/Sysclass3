$SC.module("views.translate.view.token", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		var tableViewClass = Backbone.View.extend({
			translateEditTokenDialog : null,
			events : {
				"click .datatable-option-edit" 				: "editItem",
				"click .datatable-option-translate-windows"	: "translateItemWindows",

			},
			initialize : function(opt) {
				//this.oOptions = $.extend($.fn.dataTable.defaults, datatabledefaults, opt.datatable);
				this.srclang = this.$el.data("srclang");
				this.dstlang = this.$el.data("dstlang");

				var self = this;
				this.oSettings = {};

				if (opt.datatable != undefined) {
					this.oSettings = opt.datatable;
					this.oSettings.columns = [
						{ "name": "src", "render": function ( data, type, full, meta ) {
	      					return full[self.srclang];
	    				}},
						{ "name": "dst", "render": function ( data, type, full, meta ) {
	      					return full[self.dstlang];
	    				}},
						{ "mData": "options", 'sType' : 'table-options' }
					];
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
					language_id		: this.dstlang,
					//language_code	: this.dstlang,
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
					token 		: data['token'],
					text		: data[this.srclang],
					language_id	: this.dstlang,
					srclang 	: this.srclang,
					dstlang 	: this.dstlang
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
			/*
			removeItem : function(e) {
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
			},
			*/
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
			},
			setSourceColumn: function(mData) {
				this.srclang = mData;
				//this.recreateTable();
				this.oTable.api().ajax.reload(null, false);
			},
			setDestinationColumn: function(mData) {
				this.dstlang = mData;
				//this.recreateTable();
				this.oTable.api().ajax.reload(null, false);
			},
			refreshTable : function() {
				this.oTable.api().ajax.reload(null, false);
			}
		});

		var translateViewClass = Backbone.View.extend({
			events : {
				"click .translate-automatic" : "triggerAutoTranslation"
			},
			initialize : function(opt) {
				//this.oOptions = $.extend($.fn.dataTable.defaults, datatabledefaults, opt.datatable);
				var self = this;
				this.$(".select2-me[name='src_language']").on("change", function(e) {
					self.tableView.setSourceColumn(e.val);
					//self.$(".select2-me[name='dst_language'] option[value='" + e.val + "']").attr("disabled", "disabled");

				});
				this.$(".select2-me[name='dst_language']").on("change", function(e) {
					self.tableView.setDestinationColumn(e.val);
					//self.$(".select2-me[name='src_language'] option[value='" + e.val + "']").attr("disabled", "disabled");
				});

				this.tableView = new tableViewClass({
					el : this.$("table#translate-token-table"),
					datatable : opt.datatable
				});
			},
			triggerAutoTranslation : function() {
				var translateAllTokensModelClass = app.module("models.translate").translateAllTokensModelClass;

				var src = this.$(".select2-me[name='src_language']").val();
				var dst = this.$(".select2-me[name='dst_language']").val();

				if (src != dst) {
					var translateAllTokensModel = new translateAllTokensModelClass({
						srclang : src,
						dstlang : dst
					});

					this.listenToOnce(translateAllTokensModel, "sync", function() {
						this.tableView.refreshTable();
					}, this);

					translateAllTokensModel.fetch();
				} else {
					alert("Please select diferents source and destination languages!");
				}

			}
		});

		var translateView = new translateViewClass({
			el : "#translate-token-view",
			datatable : {
				"sAjaxSource": "/module/translate/datasources/token/datatable"
			}
		});

	});
});
