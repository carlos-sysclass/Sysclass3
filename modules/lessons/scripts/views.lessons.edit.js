$SC.module("views.lessons.edit", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	this.config = $SC.module("crud.config").getConfig();
	var entity_id = mod.config.entity_id;
	mod.addInitializer(function() {

		var new_template = _.template($("#file-upload-new-item").html());
		var videourl = "/module/lessons/upload/1/video";

		$(".upload-new-video-file").click(function() {
			var index = $("#video-file-list li").size();
			var index = 0;
			$("#video-file-list").html(
				new_template({index : index})
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
			      /*
			      $.each(, function (index, file) {

			      });
				  */
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
		/*
		var materialurl = "/module/lessons/upload/1/material";

		$(".upload-new-material-file").click(function() {
			var index = $("#material-file-list li").size();
			$("#material-file-list").append(
			new_template({index : index})
			);

		  	$("#material-file-list [name='file_" + index + "']").fileupload({
			    url: materialurl + "?name=file_" + index,
			    dataType: 'json',
			    paramName: "file_" + index,
			    add: function (e, data) {
			      data.context = $("[name='file_" + index + "']").parents("li");
			      data.context.find(".file-name").html($(data.fileInput).val());
			      data.submit();
			    },
			    done: function (e, data) {
			      console.warn(data);
			      $.each(data.result.files, function (index, file) {
			        data.context.text(file.name);
			      });
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

		var fileListViewClass = Backbone.View.extend({
			template  : _.template($("#file-upload-item").html()),
			initialize: function(opt) {
				console.info('views.lessons.edit/fileListView::initialize');

				this.type = opt.type

				this.listenTo(this.model, 'sync', this.render.bind(this));
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


		$SC.module("crud.views.edit").on("start", function() {
			// HANDLE PERMISSION VIEWS, TO INJECT NEWS OBJECT
	        //console.log(this.itemModel.toJSON());
			mod.fileVideoListView = new fileListViewClass({
				el: "#video-file-list",
				type: "video",
				model : this.itemModel
				//collection: new Backbone.Collection()
			});

			mod.fileMaterialListView = new fileListViewClass({
				el: "#material-file-list",
				type: "material",
				model : this.itemModel
				//collection: new Backbone.Collection()
			});

		});


//		itemModel.fetch();
	});
});
