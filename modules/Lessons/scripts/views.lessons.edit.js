$SC.module("views.units.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;
	mod.addInitializer(function() {

		mod.unitFileModelClass = Backbone.Model.extend({
			urlRoot : "/module/units/upload/" + mod.config.entity_id
		});

		var fileUploadWidgetViewClass = Backbone.View.extend({
			template : _.template($("#file-upload-widget-item").html()),
			events : {
				"click .remove-file-action" : "remove"
			},
			initialize: function(opt) {
				console.info('views.units.edit/fileUploadWidgetViewClass::initialize');

				this.type = opt.type;
				this.listenToOnce(this.model, 'change:files', this.render.bind(this));

				this.files = new Array();

				var self = this;

				this.param_name = opt.param_name;

				if (!opt.singleUpload) {
					opt.param_name = opt.param_name + "[]";
				}

				this.$el.fileupload({
					url: opt.url,
					paramName: opt.param_name,
					acceptFileTypes : opt.acceptFileTypes,
					dataType: 'json',
					singleFileUploads: opt.singleUpload,
					done: function (e, data) {
						self.$(".list-group-item");

						var filelist = data.result[self.param_name];

						for (var i in filelist) {
							self.$("[data-fileindex='" + i + "']").remove();
							self.addOne(filelist[i]);
							/*
								.removeAttr("data-fileindex")
								.removeClass("red-stripe")
								.addClass("green-stripe")
									.find("a")
									.attr("href", filelist[i].url)
									.html(filelist[i].name);
							*/
						}

						self.files = new Array();

						self.$('.upload-action').attr("disabled", "disabled").addClass("disabled");

						window.setTimeout(function() {
							self.$('.progress').fadeOut(500, function() {
								self.$('.progress-bar').css({
									'width' : '0%'
								}).show();
							});
						}, 2500);
					},
				    progressall: function (e, data) {
						var progress = parseInt(data.loaded / data.total * 100, 10);
						self.$('.progress-bar').css({
							'width' : progress + '%'
						});
				    }
				});

				this.$("[type='file']").on('change', function (e) {
					var f;
					f = e.target.files || [{name: this.value}];
					self.files.push(f[0]);

					self.$("ul").append(self.template({
						name : f[0].name,
						size : f[0].size,
						index : (self.files.length - 1)
					}));

					self.$('.upload-action').removeAttr("disabled").removeClass("disabled");

				});

				this.$('.upload-action').click(function() {
				  self.$('.progress').fadeIn(500);
				  self.$el.fileupload('send', {files: self.files});
				});
			},
			addOne : function(data) {
				console.info('views.units.edit/fileListView::addOne');

				var html = this.template(data);

				$SC.module("ui").refresh( $(html).appendTo( this.$("ul") ) );

			},
			render: function() {
				console.info('views.units.edit/fileListView::render');

				var files = this.model.get("files");
				var data = files[this.type];
				//this.$el.empty();
				for (i in data) {
					this.addOne(data[i]);
				}
			},
			remove : function(e) {
				var fileId = $(e.currentTarget).data("fileId");
				var fileObject = new mod.unitFileModelClass();
				fileObject.set("id", fileId);
				fileObject.destroy();
				$(e.currentTarget).parents("li").remove();
			}
		});

		$SC.module("crud.views.edit").on("start", function() {
			// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT

	        mod.materialFileUploadWidgetView = new fileUploadWidgetViewClass({
				el : "#video-file-upload-widget",
				url : "/module/units/upload/" + mod.config.entity_id + "/video?name=files_videos",
				param_name : "files_videos",
				singleUpload : false,
				type: "video",
				acceptFileTypes: /(\.|\/)(mp4|webm)$/i,
				model : this.itemModel
			});
	        /*
	        mod.materialFileUploadWidgetView = new fileUploadWidgetViewClass({
				el : "#material-file-upload-widget",
				url : "/module/units/upload/" + mod.config.entity_id + "/material?name=files_materials",
				param_name : "files_materials",
				singleUpload : false,
				type: "material",
				model : this.itemModel
			});
			*/
	        /*
			mod.fileVideoListView = new fileListViewClass({
				el: "#video-file-list",
				type: "video",
				model : this.itemModel
				//collection: new Backbone.Collection()
			});
			*/
		});
	});
});
