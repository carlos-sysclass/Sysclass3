$SC.module("views.lessons.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;
	mod.addInitializer(function() {

		mod.lessonFileModelClass = Backbone.Model.extend({
			urlRoot : "/module/lessons/upload/" + mod.config.entity_id
		});

		var fileUploadWidgetViewClass = Backbone.View.extend({
			template : _.template($("#file-upload-widget-item").html()),
			events : {
				"click .remove-file-action" : "remove"
			},
			initialize: function(opt) {
				console.info('views.lessons.edit/fileUploadWidgetViewClass::initialize');

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
				console.info('views.lessons.edit/fileListView::addOne');

				this.$("ul").append(
					this.template(data)
				);
			},
			render: function() {
				console.info('views.lessons.edit/fileListView::render');

				var files = this.model.get("files");
				var data = files[this.type];
				//this.$el.empty();
				for (i in data) {
					this.addOne(data[i]);
				}
			},
			remove : function(e) {
				var fileId = $(e.currentTarget).data("fileId");
				var fileObject = new mod.lessonFileModelClass();
				fileObject.set("id", fileId);
				fileObject.destroy();
				$(e.currentTarget).parents("li").remove();
			}
		});

		/*
		var new_video_template = _.template($("#file-upload-new-video-item").html());
		var videourl = "/module/lessons/upload/1/video";

		$(".upload-new-video-file").click(function() {
			var index = $("#video-file-list li").size();
			var index = 0;
			$("#video-file-list").html(
				new_video_template({index : index})
			);

		  	$("#video-file-list [name='file_" + index + "']").fileupload({
			    url: videourl + "?name=file_" + index,
			    dataType: 'json',
			    paramName: "file_" + index,
			    add: function (e, data) {
			      data.context = $("[name='file_" + index + "']").parents("li");
			      data.context.find(".file-name").html($(data.fileInput).val());
			      data.submit();
			    },
			    done: function (e, data) {
			      console.warn(data.result['file_' + index]);
			      var file_result = data.result['file_' + index];
			      data.context.text(file_result.name);
			    },
			    progressall: function (e, data) {
			      var progress = parseInt(data.loaded / data.total * 100, 10);
			      $('#progress .progress-bar').css(
			        'width',
			        progress + '%'
			      );
			    }
			}).click();
		});
		*/

		/*
		$(".upload-new-material-file").click(function() {
			var index = $("#material-file-list li").size();

			$("#material-file-list").append(
				new_material_template({index : index})
			);

		  	$("#material-file-list [name='file_material_" + index + "']").fileupload({
			    url: materialurl + "?name=file_material_" + index,
			    dataType: 'json',
			    paramName: "file_material_" + index,
			    add: function (e, data) {

			      data.context = $("#file-upload-item-" + index);
			      data.context.find(".file-name").html($(data.fileInput).val());
			      data.submit();
			    },
			    done: function (e, data) {
			    	console.warn(e, data);
			    },
			    progressall: function (e, data) {
			      var progress = parseInt(data.loaded / data.total * 100, 10);
			      $("#file-upload-item-" + index).find('.progress .progress-bar').css(
			        'width',
			        progress + '%'
			      );
			    }
			}).click();
		});

		var fileListViewClass = Backbone.View.extend({
			template  : _.template($("#file-upload-item").html()),
			initialize: function(opt) {
				console.info('views.lessons.edit/fileListView::initialize');

				this.type = opt.type

				this.listenTo(this.model, 'change:files', this.render.bind(this));
			},
			addOne : function(data) {
				console.info('views.lessons.edit/fileListView::addOne');

				this.$el.append(
					this.template(data)
				);
			},
			render: function() {
				console.info('views.lessons.edit/fileListView::render');

				var files = this.model.get("files");
				var data = files[this.type];

				this.$el.empty();
				for (i in data) {
					this.addOne(data[i]);
				}
			}
		});
		*/
		$SC.module("crud.views.edit").on("start", function() {
			// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT


	        mod.materialFileUploadWidgetView = new fileUploadWidgetViewClass({
				el : "#video-file-upload-widget",
				url : "/module/lessons/upload/" + mod.config.entity_id + "/video?name=files_videos",
				param_name : "files_videos",
				singleUpload : false,
				type: "video",
				model : this.itemModel
			});

	        mod.materialFileUploadWidgetView = new fileUploadWidgetViewClass({
				el : "#material-file-upload-widget",
				url : "/module/lessons/upload/" + mod.config.entity_id + "/material?name=files_materials",
				param_name : "files_materials",
				singleUpload : false,
				type: "material",
				model : this.itemModel
			});

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
