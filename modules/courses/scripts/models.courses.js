$SC.module("models.courses", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	mod.addInitializer(function() {
		this.classesCollectionClass = Backbone.Collection.extend({
			course_id : 0,
			datatable : false,

			url : function() {
				var url = "/module/courses/items/classes/:courses/:datatable";
				url = url.replace(":courses", JSON.stringify(this.course_id));
				if (this.datatable) {
					url = url.replace(":datatable", 'datatable');
				} else {
					url = url.replace(":datatable", '');
				}
				return url;
			}
		});

		this.seasonsCollectionClass = Backbone.Collection.extend({
			course_id : 0,
			datatable : false,

			url : function() {
				var url = "/module/courses/items/seasons/:courses/:datatable";
				url = url.replace(":courses", JSON.stringify(this.course_id));
				if (this.datatable) {
					url = url.replace(":datatable", 'datatable');
				} else {
					url = url.replace(":datatable", '');
				}
				return url;
			}
		});


		// TODO Change the way which the data cames from server
		this.courseModelClass = Backbone.Model.extend({
			urlRoot : "/module/courses/item/courses",
			prev : function() {
				if (this.get("prev") != null) {
					this.set("id", this.get("prev"));
					this.fetch();
				}
			},
			next : function() {
				if (this.get("next") != null) {
					this.set("id", this.get("next"));
					this.fetch();
				}
			}
		});
		this.classModelClass = Backbone.Model.extend({
			initialize : function(opt) {
				//this.courses = opt.courses;

				this.listenTo(opt.courses, "change:id", function(model,course_id) {
					this.set("course_id", course_id);
					this.fetch();
				}, this);
				/*
				this.on("change:id", function(model, id) {
					if (model._changing) {
						this.fetch();
					}
				}, this);
				*/
			},
			urlRoot : function() {
				if (this.get("course_id") == 0) {
					return "/module/courses/item/classes";
				} else {
					return "/module/courses/item/classes/" + this.get("course_id");
				}
			},
			goToID : function(id) {
				this.set("id", id);
				this.fetch();
			},
			prev : function() {
				if (this.get("prev") != null) {
					this.goToID(this.get("prev"));
				}
			},
			next : function() {
				if (this.get("next") != null) {
					this.goToID(this.get("next"));
				}
			}
		});
		this.lessonModelClass = Backbone.Model.extend({
			initialize : function(opt) {
				//this.classes = opt.classes;

				this.listenTo(opt.classes, "change:id change:course_id", function(model,class_id) {
					this.set("course_id", opt.classes.get("course_id"));
					this.set("class_id", opt.classes.get("id"));
					this.fetch();
				}, this);
			},
			urlRoot : function() {
				if (this.get("class_id") == 0) {
					return "/module/courses/item/lessons";
				} else {
					return "/module/courses/item/lessons/" + this.get("course_id") + "/" + this.get("class_id")
				}
			},
			prev : function() {
				if (this.get("prev") != null) {
					this.set("id", this.get("prev"));
					this.fetch();
				}
			},
			next : function() {
				if (this.get("next") != null) {
					this.set("id", this.get("next"));
					this.fetch();
				}
			}
		});
/*
		var contentModelClass = Backbone.Model.extend({
			initialize: function() {
			},
			bindEvents : function() {
				this.on("change:id", function(a,b,c,d) {
					this.fetch();
				}, this);
			},
			defaults : {
				course_id 	: 0,
				lesson_id 	: 0
			},
			urlRoot : function() {
				if (this.get("course_id") == 0 && this.get("lesson_id") == 0) {
					return "/module/courses/content";
				} else {
					return "/module/courses/content/" + this.get("course_id") + "/" + this.get("lesson_id");
				}
			}
			// 31/106
		});
*/

		this.fileTreeCollectionClass = Backbone.Collection.extend({
			initialize : function(opt) {
				if (opt.source) {
					this.url = opt.source;
				}
			},
			data: function (options, callback) {
				this.fetch({
					data : options,
					success : function(collection,data) {
						callback({ data: data });
					}
				})
			}
		});
	});

});
