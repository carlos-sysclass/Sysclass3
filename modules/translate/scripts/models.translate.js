$SC.module("models.translate", function(mod, app, Backbone, Marionette, $, _) {
	mod.addInitializer(function(collectionData) {

		this.translateLanguageModelClass = Backbone.Model.extend({
			urlRoot : "/module/translate/change/"
		});


		this.translateEditTokenModelClass = Backbone.Model.extend({
			urlRoot : "/module/translate/item/token"
		});

		this.translateWindowsTokenModelClass = this.translateEditTokenModelClass.extend({
			translateURL : "/module/translate/tt",	
			sync : function (method, model, options) {
				//console.info('models.scores/scoreModelClass::sync');
				if (method == "read") {
					options.url = model.translateURL + "/" + model.get("srclang") + "/" +  model.get("dstlang");

					var params = [];

					if (this.group != null) {
						params.push("groups=" + this.group.join(","));
					}
					if (model.get("token") != undefined) {
						params.push("tk=" + model.get("token"));
					}
					if (model.get("text") != undefined) {
						params.push("st=" + model.get("text"));
					}

					options.url = options.url + "?" + params.join("&");

				}
				return Backbone.sync(method, model, options);
			}
		});
	});
});