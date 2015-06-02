$SC.module("views", function(mod, app, Backbone, Marionette, $, _) {
  	this.baseClass = Backbone.View.extend({
	    events : {
	    	"change :input"			: "update",
	    	"click .save-action" 	: "save"
	    },
	    initialize: function() {
	    	var self = this;
	    	console.info('views/baseClass::initialize');
	    	if (this.model) {
	    		this.listenToOnce(this.model, "sync", this.render.bind(this));
	    	}
	    	// HANDLE SPECIAL bootstrap-switchs CHANGE EVENTS
			this.$('.bootstrap-switch-me').each(function() {
				$(this).on('switchChange.bootstrapSwitch', function(e, state) {
                    if (state) {
                        $(this).attr("checked", "checked");
                        $(this).val(1);
                    } else {
                        $(this).removeAttr("checked");
                        $(this).val(0);
                    }

					self.update(e)
				});
	    	});
	    	// HANDLE SPECIAL wysihtml5 CHANGE EVENTS
	    	this.$('.wysihtml5').each(function() {
	    		var wysihtml5DOM = this;

				var wysihtml5 = $(wysihtml5DOM).data('wysihtml5');
				wysihtml5.editor.on("change", function(e) {
					var changeEvt = jQuery.Event("change");
					//console.log(wysihtml5DOM);
					wysihtml5.el.trigger(changeEvt);
					//self.update()
				});
	    	});

	    },
	    handleAction : function(action) {
	    	console.info('views/baseClass::handleAction');
	    	$CEPETI.module("pages").handleAction(action);
	    },
	    formatValue : function(value, formatTo, formatFrom) {
	    	console.info('views/baseClass::formatValue');
	    	if (formatTo == 'decimal1') {
				return $.jformat.number(value, "#0.0");
	    	} else if (formatTo == 'decimal2') {
	    		return $.jformat.number(value, "#0.00");
	    	} else if (formatTo == 'date' || formatTo == 'time' || formatTo == 'datetime') {
	    		if (value == 0) {
	    			return "";
	    		}
		    	if (formatFrom == 'unix-timestamp') {
		    		value = moment.unix(value);
		    	} else {
		    		value = moment(value);
		    	}
		    	if (formatTo == 'time') {
		    		return value.format("hh:mm:ss");
		    	} else if (formatTo == 'datetime') {
		    		return value.format("L hh:mm");
		    	} else { // DEFAULTS TO date
		    		return value.format("L");
		    	}
	    	} else if (formatTo == 'unix-timestamp') {
	    		if (formatFrom == 'date') {
	    			value = moment(value, "L");
	    		} else if (formatFrom == 'time') {
	    			value = moment(value, "hh:mm:ss");
		    	} else if (formatFrom == 'datetime') {
		    		value = moment(value, "L hh:mm");
		    	} else { // DEFAULTS TO date
		    		value = moment(value);
		    	}
		    	return value.unix();
	    	}
	    	return value;
	    },
	    renderItens : function(values) {
	    	console.info('views/baseClass::renderItens');
			// INJECT VALUES

	    	for (idx in values) {
	    		if (
	    			this.$(":input[data-update^='" + idx + "']").size() > 0 ||
	    			this.$(":input[name='" + idx + "']").size() > 0
	    		) {
		    		if (this.$(":input[data-update^='" + idx + "']").size() > 0) {
		    			var inputField = this.$(":input[data-update^='" + idx + "']");
		    		} else {
		    			var inputField = this.$(":input[name='" + idx + "']");
		    		}
		    		var self = this;
		    		inputField.each(function(el, i) {
		    			var input = $(this);

						if (input.hasClass("datepick")) {
		                    if (values[idx] != null) {
		                        var date = new Date(values[idx]);
		                        // CORRETING TIMEZONE DIFF
		                        date.setTime(date.valueOf() + (date.getTimezoneOffset() * 60 * 1000));

		                        input.datepicker('setDate', date);
		                    }
		                } else if (input.is("[type='radio']") || input.is("[type='checkbox']")) {
	                		if (values[idx] != null) {
		                		if (input.hasClass("icheck-me")) {
									input.filter("[value='" + values[idx] +"']").iCheck("check");
								} else if (input.hasClass("bootstrap-switch-me")) {
									//input.filter("[value='" + values[idx] +"']").iCheck("check");
									input.bootstrapSwitch('state', (values[idx] == 1), true);
		                		} else {
			                		input.filter("[value='" + values[idx] +"']").attr("checked", "checked");
			                		if ($.uniform) {
			                			$.uniform.update(input.filter("[value='" + values[idx] +"']"));
			                		}
			                	}
		                	} else {
		                		if (input.hasClass("icheck-me")) {
		                			input.filter("[value='" + values[idx] +"']").iCheck("check");
		                		} else {
		                			input.filter("[value='']").attr("checked", "checked");
			                		if ($.uniform) {
			                			$.uniform.update(input.filter("[value='']"));
			                		}
		                		}
		                	}
		                } else if (input.hasClass("select2-me") && input.is("select")) {
		                    input.select2("val", values[idx]);

		                } else if (input.hasClass("select2-me") && input.is("[type='hidden']")) {
							//input.select2("data", values[idx]);
							//input.select2("data", {id : values[idx]});
							//console.warn(values[idx]);

                            input.select2("val", values[idx]);


						} else if (input.hasClass("wysihtml5")) {
							var wysihtml5 = $(this).data('wysihtml5');
							wysihtml5.editor.setValue(values[idx]);
		                } else  {
							if (input.is("[data-format]")) {
								input.val(self.formatValue(values[idx], input.data("format"), input.data("format-from")));
			    			} else {
		                    	input.val(values[idx]);
		                    }
		                }
		    		});


	            }
	            if (this.$("[data-update^='" + idx + "']").not(":input").size() > 0) {
	            	var domField = this.$("[data-update^='" + idx + "']");
	            	if (domField.is("[data-format]")) {
	            		domField.html(this.formatValue(values[idx], domField.data("format"), domField.data("format-from")));
	            	} else {
	            		domField.html(values[idx]);
	            	}
	            }
	        }
	        this.trigger("form:rendered");

	    },
		render: function(model) {
	    	console.info('views/baseClass::render');
	    	if (model == undefined) {
	    		model = this.model;
	    	}
	    	var values = model.toJSON();
	    	this.renderItens(values);
	        return this;
	    },
	    update : function(e) {
	    	console.info('views/baseClass::update');
	    	var $el = $(e.currentTarget);

			if ($el.attr("data-update")) {
				var prop = $el.attr("data-update");
			} else if ($el.attr("name")) {
		        var prop = $el.attr("name");
		    } else {
		    	return;
		    }

			var value = $el.val();
			/*
			if ($el.is(".datepick")) {
				value = $el.data("datepicker").getDate().format("isoDate");
			}
			*/
			if ($el.is("[data-format-from]")) {
				value = this.formatValue(value, $el.data("format-from"), $el.data("format"));
			}

		    this.model.set(prop, value);
	    },
	    save : function(e) {
	    	console.info('views/baseClass::save');

	    	var self = this;
	    	this.model.save(/*null, {
	    		success : function(model, response, options) {
	    			if (model.has("_response_")) {
	    				self.handleAction(model.get("_response_"));
	    				model.unset("_response_");
	    			}

					if (mod.submodules) {
						for(i in mod.submodules) {
							mod.submodules[i].triggerMethod("saved", self, model, response);
						}
			       	}
	    		},
	    		error : function(model, xhr, options) {
					if (mod.submodules) {
						for(i in mod.submodules) {
							mod.submodules[i].triggerMethod("error", self, model, xhr);
						}
			       	}
	    		}
	    	}*/);
	    }
  	});

	this.baseFormClass = this.baseClass.extend({
		tagName : "form",
	    events : {
	    	"change :input"			: "update",
	    	"click .save-action" 	: "submit"
	    },
	    initialize: function() {
	    	console.info('views/baseFormClass::initialize');
	    	mod.baseClass.prototype.initialize.apply(this);

	    	if (this.$el.is("form") || this.$("form").size() > 0) {
	    		if (this.$el.is("form")) {
	    			this.oForm = this.$el;
	    		} else {
	    			this.oForm = this.$("form");
	    		}

	    		this.handleValidation();
	    	}
	    },
	    handleValidation : function() {
	    	console.info('views/baseFormClass::handleValidation');
	    	var self = this;




				this.oForm.validate({
					ignore: null,
	                errorElement: 'span', //default input error message container
	                errorClass: 'help-block', // default input error message class

	                errorPlacement: function (error, element) { // render error placement for each input type
	                	console.log('aa');
	                    //if (element.attr("name") == "membership") { // for uniform radio buttons, insert the after the given container
	                    //    error.insertAfter("#form_2_membership_error");
	                    if (element.hasClass("wysihtml5")) { // for wysiwyg editors
	                    	//console.log(element.data('wysihtml5').editor.composer.iframe);
	                        error.insertAfter(element.data('wysihtml5').editor.composer.iframe);
	                    //} else if (element.attr("name") == "service") { // for uniform checkboxes, insert the after the given container
	                    //    error.insertAfter("#form_2_service_error");
	                    } else {
	                    	error.insertAfter(element); // for other inputs, just perform default behavior
	                    }
	                },

	                highlight: function (element) { // hightlight error inputs
	                   $(element)
	                        .closest('.form-group').addClass('has-error'); // set error class to the control group
	                },
	                unhighlight: function (element) { // revert the change done by hightlight
	                    $(element)
	                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
	                },
	                success: function (label) {
                        label
                            .addClass('valid').addClass('help-block') // mark the current input as valid and display OK icon
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
	                },
					submitHandler : function(f) {
						self.save();
					}
				});

		},
	    submit : function(e) {
	    	console.info('views/baseFormClass::submit');
	    	this.oForm.submit();
	    	e.preventDefault();
	    }
  	});

	this.baseInsertableCollectionViewClass = Backbone.View.extend({
		events : {
			"click .add-action" : "insertModel"
		},
		initialize: function(opt) {
			console.info('views/baseInsertableCollectionViewClass::initialize');
			if (this.options) {
				opt = this.options;
			}
	    	// BIND TO select2 search_diagnostico TO add itens to table
	    	//this.listenToOnce(this.collection, 'sync', this.render);
	    	if (opt.modelClass) {
	    		this.modelClass = opt.modelClass;
	    	} else {
	    		this.modelClass = Backbone.Model;
	    	}
	    	if (opt.defaults) {
	    		this.defaults = opt.defaults;
	    	} else {
	    		this.defaults = {};
	    	}
	    },
	    insertModel : function(e) {
	    	console.info('views/baseInsertableCollectionViewClass::insertModel');

	    	var model = new this.modelClass(this.defaults);
	    	this.collection.add(model);
	    }
  	});

	this.baseSelect2CollectionViewClass = this.baseInsertableCollectionViewClass.extend({
		initialize: function(opt) {
			console.info('views/baseSelect2CollectionViewClass::initialize');
			this.options = opt;
	    	mod.baseInsertableCollectionViewClass.prototype.initialize.apply(this, opt);

	    	if (opt.map) {
	    		this.map = opt.map;
	    	} else {
	    		this.map = {id : "id", name : "name"};
	    	}
	    	this.$el.on("change", this.insertModel.bind(this));
	    },
	    insertModel : function(e) {
	    	console.info('views/baseSelect2CollectionViewClass::insertModel');
	    	var data = $(e.currentTarget).select2("data");

			var modelData = {};
	    	for(i in data) {
	    		if (this.map[i]) {
	    			modelData[this.map[i]] = data[i];
	    		} else {
	    			modelData[i] = data[i];
	    		}
	    	}
	    	modelData = _.extend(modelData, this.defaults);

	    	var model = new this.modelClass(modelData);
	    	this.collection.add(model);
	    }
  	});

	this.baseTableViewClass = Backbone.Marionette.CollectionView.extend({
		initialize : function() {
			console.info('views/baseTableViewClass::initialize');

			Backbone.Marionette.CollectionView.prototype.initialize.apply(this);
			/*
			this.listenTo(this.collection, "change", function(a,bc,d,e, obj) {
				console(a,b,c,d,e,obj);
				if (obj.models) {
					if (obj.size() == 0) {
						this.render();
					}
				}
			}, this);
			*/
			this.render();
		}
	});

	this.baseTableItemViewClass = Backbone.Marionette.ItemView.extend({
		_isViewUpdating : false,
		initialize : function() {
			console.info('views/baseTableItemViewClass::initialize');
			Backbone.Marionette.ItemView.prototype.initialize.apply(this);

			// LISTEN TO MODEL CHANGES TO REFRESH UI
			//this.$el.on("change", ":input", this.update.bind(this));

			this.$el.on("change", ":input", this.update.bind(this));
			this.$el.on("click", ".remove", this.delete.bind(this));
		},
		delete : function(e) {
			console.info('views/baseTableItemViewClass::delete');
			this.model.destroy();
		},
	    update : function(e) {
	    	console.info('views/baseTableItemViewClass::update');
	    	if (this._isViewUpdating) {
	    		return;
	    	}

	    	var $el = $(e.currentTarget);

			if ($el.attr("name")) {
		        var matches = $el.attr("name").match(/[a-z_]+\[([a-z_]+)\]/);
		        var prop = matches[1];
		        this.updateModel(prop, e.currentTarget);
		    }
	    },
	    updateModel : function(prop, itemDOM) {
	    	console.info('views/baseTableItemViewClass::updateModel|START');
	    	$el = $(itemDOM);

			var value = $el.val();
			if ($el.is(".datepick")) {
				value = $el.data("datepicker").getDate().format("isoDate");
			}
			this.model.set(prop, value);
			console.info('views/baseTableItemViewClass::updateModel|END');
	    },
	    updateView : function(prop, itemDOM) {
	    	console.info('views/baseTableItemViewClass::updateView');
	    	var inputField = $(itemDOM);
	    	var value = this.model.get(prop);

			if (inputField.hasClass("datepick")) {
				if (value != null) {
					var date = new Date(value);
					// CORRETING TIMEZONE DIFF
					date.setTime(date.valueOf() + (date.getTimezoneOffset() * 60 * 1000));

					inputField.datepicker('setDate', date);
				}
			} else if (inputField.is("[type='radio']") || inputField.is("[type='checkbox']")) {
				if (value != null) {
					if (inputField.hasClass("icheck-me")) {
						inputField.filter("[value='" + value +"']").iCheck("check");
					} else {
						inputField.filter("[value='" + value +"']").attr("checked", "checked");
					}
				} else {
					if (inputField.hasClass("icheck-me")) {
						inputField.filter("[value='" + value +"']").iCheck("check");
					} else {
					inputField.filter("[value='']").attr("checked", "checked");
					}
				}
			} else if (inputField.hasClass("select2-me") && inputField.is("select")) {
				inputField.find("[value='" + value +"']").attr("selected", "selected");
				inputField.select2("val", value);
			} else if (inputField.hasClass("select2-me") && inputField.is("[type='hidden']")) {
				inputField.select2("data", value);
			} else  {
				inputField.val(value);
			}
	    },
	    onRender : function() {
	    	console.info("views/baseTableItemViewClass::onRender|START");

	    	$CEPETI.module("pages").refresh(this.el);

	    	var self = this;
	    	this._isViewUpdating = true;
			this.$(":input").each(function() {

				var $el = $(this);

	    		if ($el.attr("name")) {
			        var matches = $el.attr("name").match(/[a-z_]+\[([a-z_]+)\]/);
			        var prop = matches[1];

			        self.updateView(prop, this);
				}
			});
			this._isViewUpdating = false;
			console.info("views/baseTableItemViewClass::onRender|END");
	    },
	    onClose : function(a,b,c) {
	    	console.info("views/baseTableItemViewClass::onClose");
			this.$el.off();
	    }
	});
});
