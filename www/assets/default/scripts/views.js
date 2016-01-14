$SC.module("views", function(mod, app, Backbone, Marionette, $, _) {
	mod.formatValue = function(value, formatTo, formatFrom) {
    	if (formatTo == 'decimal1') {
			return numeral(value).format('0.0');
    	} else if (formatTo == 'decimal2') {
    		return numeral(value).format('0.00');
    	} else if (formatTo == 'decimal-custom') {
    		return numeral(value).format(formatFrom);
    	} else if (formatTo == 'date' || formatTo == 'time' || formatTo == 'datetime' || formatTo == "isodate") {
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
	    	} else if (formatTo == "isodate") {
				return value.format("YYYY-MM-DD");
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
    };

  	this.baseClass = Backbone.View.extend({
  		dataPooling : true,
  		renderType : "byModel",
	    events : function() {
	    	return {
		    	"change :input"			: "update",
		    	"click .save-action" 	: "save"
	    	};
    	},
	    initialize: function() {
	    	console.info('views/baseClass::initialize');

	    	var self = this;

	    	if (this.model) {
	    		this.listenToOnce(this.model, "sync", this.render.bind(this));
	    	}

	    	//this.bindViewEvents();
	    	// HANDLE SPECIAL bootstrap-switchs CHANGE EVENTS
	    	// CREATE A DELEGATED EVENT, BECAUSE THE ITEM SOMETIMES IS NOT IN THE DOM YET

			// HANDLE SPECIAL bootstrap-switch CHANGE EVENTS
			this.$el.delegate('.bootstrap-switch-me', 'switchChange.bootstrapSwitch', function(e, state) {
				self.update(e);
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
	    	// HANDLE SPECIAL icheck CHANGE EVENTS
	    	//this.$('.icheck-me').each(function() {
	    	this.$el.delegate("[type='radio'].icheck-me", "ifChecked", function(e) {
				self.update(e);
    		});

    		this.$el.delegate("[type='checkbox'].icheck-me", "ifChanged", function(e) {
				self.update(e);
    		});


			/*	    	
    		this.$("[type='radio'].icheck-me").on("ifChecked", function(e) {
				self.update(e);
    		});

    		this.$("[type='checkbox'].icheck-me").on("ifChanged", function(e) {
				self.update(e);
    		});
			*/
	    	//});
	    },
	    bindViewEvents : function() {
			
	    },
	    handleAction : function(action) {
	    	console.info('views/baseClass::handleAction');

	    	$SC.module("pages").handleAction(action);
	    },
	    formatValue : function(value, formatTo, formatFrom) {
	    	console.info('views/baseClass::formatValue');
	    	return mod.formatValue(value, formatTo, formatFrom);
	    	/*
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
	    	*/
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

                        if (input.is("[type='radio']") || input.is("[type='checkbox']")) {
	                		if (values[idx] !== null) {
	                			var valueArray = values[idx];
		                        if (!_.isArray(valueArray)) {
		                            valueArray = [values[idx]];
		                        }
		                        _.each(valueArray, function(itemValue, index) {
		                        	if (input.filter("[value='" + itemValue +"']").size() > 0 || input.filter("[data-value-unchecked='" + itemValue +"']")) {
		                        		var innerInput = input.filter("[value='" + itemValue +"']");

	                                    if (innerInput.size() == 0) {

	                                        innerInput = input.filter("[data-value-unchecked='" + itemValue +"']");
	                                        uncheck = true;

	                                    }

				                		if (innerInput.hasClass("icheck-me")) {
				                			//if (input.filter("[value='" + itemValue +"']").size() > 0) {
				                			innerInput.iCheck("check");
				                			//}
										} else if (input.hasClass("bootstrap-switch-me")) {
											//input.bootstrapSwitch('state', (itemValue == 1), true);
											

                                            innerInput.bootstrapSwitch('state', (itemValue == 1), true);
				                		} else {
				                			/*
					                		input.filter("[value='" + itemValue +"']").attr("checked", "checked");
					                		if ($.uniform) {
					                			$.uniform.update(input.filter("[value='" + itemValue +"']"));
					                		}
					                		*/
					                	}
				                	}
		                        });

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
		                	if (
		                		!_.isUndefined(input.data("format-attr"))
		                		&& _.isObject(_.first(values[idx]))
		                	) {
		                		var attr = "id";

		                		input.select2("val", _.pluck(values[idx], attr));
		                	} else {
			                    input.select2("val", values[idx]);
							}
		                } else if (input.hasClass("select2-me") && input.is("[type='hidden']")) {
		                	if (
		                		!_.isUndefined(input.data("format-attr"))
		                		&& _.isObject(_.first(values[idx]))
		                	) {
		                		var attr = "id";

		                		input.select2("val", _.pluck(values[idx], attr));
		                	} else {
			                    input.select2("val", values[idx]);
							}
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

                        if (input.hasClass("date-picker")) {
                            input.datepicker('update');
                        }
                            /*
                            if (values[idx] !== null) {
                                var date = new Date(values[idx]);
                                // CORRETING TIMEZONE DIFF
                                //date.setTime(date.valueOf() + (date.getTimezoneOffset() * 60 * 1000));

                                //input.datepicker('setDate', date);
                                input.val(datepicker('update');

                            }
                            */
                    });

	            }
	        }
	        this.trigger("form:rendered");

	    },
        renderViewItens : function(inputList) {
            // TEMPORARLY DISABLE UPDATE METHOD
            this.disableDataPooling();
            inputList.each(function(index, inputDOM) {
                var input = $(inputDOM);
                var modelField  = input.attr("name");
                if (input.is("[data-update]")) {
                    modelField = input.data("update");
                }

                if (!_.isUndefined(modelField)) {
                    // UPDATE inputField WITH  this.model.get(modelField)
                    var values = this.model.get(modelField);

                    if (input.is("[type='radio']") || input.is("[type='checkbox']")) {
                        if (values !== null) {
                            var valueArray = values;
                            if (!_.isArray(valueArray)) {
                                valueArray = [values];
                            }

                            _.each(valueArray, function(itemValue, index) {
                                if (input.filter("[value='" + itemValue +"']").size() > 0 || input.filter("[data-value-unchecked='" + itemValue +"']")) {
                                    var innerInput = input.filter("[value='" + itemValue +"']");
                                    var uncheck = false;

                                    if (innerInput.size() == 0) {

                                        innerInput = input.filter("[data-value-unchecked='" + itemValue +"']");
                                        uncheck = true;

                                    }

                                    if (innerInput.hasClass("icheck-me")) {
                                        
                                        if (uncheck) {
                                            innerInput.iCheck("uncheck");
                                        } else {
                                            innerInput.iCheck("check");
                                        }
                                    } else if (input.hasClass("bootstrap-switch-me")) {
                                        innerInput.bootstrapSwitch('state', (itemValue == 1), true);



                                    } else {
                                        if (uncheck) {
                                            innerInput.removeAttr("checked");
                                        } else {
                                            innerInput.attr("checked", "checked");
                                        }
                                        if ($.uniform) {
                                            $.uniform.update(innerInput);
                                        }
                                    }
                                }
                            });

                        } else {
                            var innerInput = input.filter("[value='']");

                            if (innerInput.hasClass("icheck-me")) {
                                innerInput.iCheck("check");


                            } else {
                                innerInput.attr("checked", "checked");
                                if ($.uniform) {
                                    $.uniform.update(innerInput);
                                }
                            }
                        }
                    } else {
		                if (input.hasClass("select2-me")) {
		                	if (
		                		!_.isUndefined(input.data("format-attr"))
		                		&& _.isObject(_.first(values))
		                	) {
		                		var attr = "id";

		                		input.select2("val", _.pluck(values, attr));
		                	} else {
			                    input.select2("val", values);
							}
						} else if (input.hasClass("wysihtml5")) {
							var wysihtml5 = $(input).data('wysihtml5');
							wysihtml5.editor.setValue(values);
						 } else {
                        	input.val(values);
                        }
                    }
                }
            }.bind(this));
            this.enableDataPooling();
        },
        renderUiItems : function() {
            if (this.$("[data-update]").not(":input").size() > 0) {
                var self = this;
                this.$("[data-update]").not(":input").each(function() {
                    var domField = $(this);

                    var modelField = $(this).data("update");

                    if (self.model.get(modelField)) {
                        var values = self.model.get(modelField);

                        if (domField.is("[data-format]")) {
                            domField.html(self.formatValue(values, domField.data("format"), domField.data("format-from")));
                        } else {
                            domField.html(values);
                        }
                    }
                });
            }
        },
		render: function(model) {
	    	console.info('views/baseClass::render');
	    	Marionette.triggerMethodOn(this, "beforeRender", model);

	    	if (model === undefined) {
	    		model = this.model;
	    	}
	    	if (this.renderType == "byView") {
				this.renderViewItens(this.$(":input"));
	    	} else {
	    		var values = model.toJSON();
				this.renderItens(values);
	    	}

            this.renderUiItems();

	    	Marionette.triggerMethodOn(this, "render", model);
	        return this;
	    },
	    setReadonly : function(toogle) {
	    	this.$(":input").each(function(i, el) {
	    		if ($(el).hasClass("icheck-me")) {
	    			$(el).iCheck("disable");
	    		} else {
	    			$(el).attr("disable", "disable").addClass("disabled");
	    		}
	    	}.bind(this));
	    },
	    disableDataPooling : function() {
	    	this.dataPooling = false;
	    },
		enableDataPooling : function() {
			this.dataPooling = true;
		},
	    update : function(e) {
	    	if (this.dataPooling) {
	    		console.info('views/baseClass::update');
		    	var $el = $(e.currentTarget);

		    	var prop = null;

				if ($el.attr("data-update")) {
					prop = $el.attr("data-update");
				} else if ($el.attr("name")) {
			        prop = $el.attr("name");
			    } else {
			    	return;
			    }

				var value = $el.val();

				if ($el.is("[type='checkbox']"))  {
					// CHECK FOR ANOTHER VALUES, BECAUSE THE ELEMENT TRIGGERING THE EVENT IS JUST ONE OFF MULTIPLE CHECKBOXES
					var brothers = null;
					if ($el.attr("data-update")) {
						brothers = this.$("[type='checkbox'][data-update='" + prop +"']");
					} else {
						brothers = this.$("[type='checkbox'][name='" + prop +"']");
					}

					var values = [];
					brothers.each(function() {

						if (!$(this).is(":checked") && $(this).is("[data-value-unchecked]")) {
							values.push($(this).data("value-unchecked"));
						} else if ($(this).is(":checked")) {
							values.push($(this).val());
						}
					});
					console.warn(values);
					// TODO: CREATE A WAY TO CLEAR ALL Backbone DeepModel Variables when prop is his father
					this.model.unset(prop, {silent: true});
                    if ($el.is("[data-update-single]")) {
                        value = _.first(values);

                    } else {
                        value = values;
                    }
				}

				if ($el.is("[data-format-from]")) {
					value = this.formatValue(value, $el.data("format-from"), $el.data("format"));
				}

				if ($el.is("[data-format-attr]")) {
					if (_.isArray(value)) {
						var attr = $el.data("format-attr");

						for(i in value) {
							var currentValue = value[i];
							value[i] = {};
							value[i][attr] = currentValue;
						}
					}
				}

			    this.model.set(prop, value);

                this.renderUiItems();
		    }
	    },
	    save : function(e) {
	    	console.info('views/baseClass::save');
	    	var self = this;

	    	self.trigger("before:save", this.model);

	    	this.model.save(null, {
	    		success : function(model, response, options) {
	    			self.trigger("after:save", model);
	    		},
	    		error : function(model, xhr, options) {
	    			self.trigger("error:save", model);
	    		}
	    	});

            self.trigger("complete:save", this.model);
	    },
	    setModel : function(model) {
	    	//console.warn(this, model);
	    	this.model = model;
	    	this.render(model);
	    }
  	});

	this.baseFormClass = this.baseClass.extend({
		//tagName : "form",
		oForm : null,
	    events : function() {
	    	return {
		    	"change :input"			: "update",
		    	"click .save-action" 	: "submit"
	    	};
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
                    $(element).trigger("error.validate");
                },
                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },
                success: function (label, element) {
                    label
                        .addClass('valid').addClass('help-block') // mark the current input as valid and display OK icon
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    $(element).trigger("success.validate");
                },
				submitHandler : function(f) {
					self.save();
				}
			});
		},
		onRender: function(model) {
	    	console.info('views/baseFormClass::onRender');

	    	if (_.isNull(this.oForm)) {
		    	if (this.$el.is("form") || this.$("form").size() > 0) {
		    		if (this.$el.is("form")) {
		    			this.oForm = this.$el;
		    		} else {
		    			this.oForm = this.$("form");
		    		}

		    		this.handleValidation();
		    	}
	    	}

	    },
	    submit : function(e) {
	    	console.info('views/baseFormClass::submit');
	    	this.oForm.submit();
	    }
  	});

    this.dialogViewClass = this.baseFormClass.extend({
        renderType : "byView",
        /*
        initialize : function() {
			this.baseFormClass.initialize.apply(this);
		},
		*/
        open : function() {
            this.$el.modal("show");
        },
        close : function() {
            this.$el.modal("hide");
            this.trigger("hide.dialog");
        }
    });

    this.listManagerCreatorViewClass = Backbone.View.extend({
        dialogModule : null,
        itemViewClass : null,
        modelClass : Backbone.Model,
        sortableOptions : {},
        events : {
            "click .add-item-action" : "addItem"
        },
        initialize : function() {
            this.listenToOnce(this.collection, 'sync', this.render.bind(this));
            //this.listenTo(this.collection, 'add', this.addOne.bind(this));
            //this.listenTo(this.collection, 'add', this.refreshCounters.bind(this));
            this.listenTo(this.collection, 'remove', this.refreshCounters.bind(this));

            this.initializeSortable();
        },
        initializeSortable : function() {
            var self = this;

            this.$(".list-group").sortable(_.extend({
                items: "li.list-file-item.draggable",
                opacity: 0.8,
                placeholder: 'list-file-item placeholder',
                dropOnEmpty : true,
                forceHelperSize : true,
                forcePlaceholderSize: true,
                tolerance: "intersect",
                update : function( event, ui ) {
                    var order = $(this).sortable("toArray", {attribute : "data-roadmap-grouping-id"});

                    self.collection.setOrder(order);

                    self.refreshCounters();
                },
                over : function( event, ui ) {
                    $(this).addClass("ui-sortable-hover");
                },
                out  : function( event, ui ) {
                    $(this).removeClass("ui-sortable-hover");
                }
            }, this.sortableOptions));
        },
        addItem : function() {
            var self = this;
            var itemModel = new this.modelClass();

            this.listenToOnce(itemModel, "sync", function(model) {
                self.addOne(model);
                self.refreshCounters();
            });

            if (!this.dialogModule.started) {
                this.dialogModule.start({
                    modelClass : this.modelClass
                });
            }
            this.dialogModule.getValue(function(item, model) {
            	self.collection.add(model);
                self.addOne(model);
                self.refreshCounters();
            });
        },
        addOne : function(model) {
            console.info('views/baseMultiListCreator::addOne');

            var self = this;

            var itemView = new this.itemViewClass({
                model : model
            });

            $(itemView.render().el).appendTo(this.$("ul.items-container"));
            itemView.start();

            this.listenTo(itemView, "grouping:updated", function(model) {
                self.refreshCounters();
            });
        },
        refreshCounters : function() {
            console.info('views/baseMultiListCreator::refreshCounters');
            var total = this.collection.size();
            this.$("ul.items-container > li.list-file-item .total").html(total);

            this.$("ul.items-container > li.list-file-item").each(function(i, item) {
                $(this).find(".counter").html(i+1);
            });
        },
        render: function() {
            console.info('view/baseMultiListCreator::render');

            var self = this;

            this.collection.each(function(model, i) {
                self.addOne(model, i);
            });
            //this.refreshCounters();
            app.module("ui").refresh( this.$("ul.items-container ") );

            this.refreshCounters();
        },
        remove : function(e) {
            var fileId = $(e.currentTarget).data("fileId");
            var fileObject = new mod.lessonFileModelClass();
            fileObject.set("id", fileId);
            fileObject.destroy();
            $(e.currentTarget).parents("li").remove();
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
				if (value !== null) {
					var date = new Date(value);
					// CORRETING TIMEZONE DIFF
					date.setTime(date.valueOf() + (date.getTimezoneOffset() * 60 * 1000));

					inputField.datepicker('setDate', date);
				}
			} else if (inputField.is("[type='radio']") || inputField.is("[type='checkbox']")) {
				if (value !== null) {
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

    this.baseDatatableViewClass = Backbone.View.extend({
		events : {
			"click .datatable-option-select" : "onSelectItem",
            "click .datatable-actionable" : "onAction",
			"confirmed.bs.confirmation .datatable-option-remove" : "onRemoveItem"
		},
    	initialize : function(opt) {
	        //this.oOptions = $.extend($.fn.dataTable.defaults, datatabledefaults, opt.datatable);
	        if (opt.datatable !== undefined) {
	        	this.oTable = this.$el.dataTable(opt.datatable);
            //    this.oTableApi = this.oTable.api();
	        } else {
	        	this.oTable = this.$el.dataTable();
	        }
            this.oTableApi = this.oTable.api();

	        this.$el.closest(".dataTables_wrapper").find('.dataTables_filter input').addClass("form-control input-medium"); // modify table search input
	        this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').addClass("form-control input-small"); // modify table per page dropdown
	        this.$el.closest(".dataTables_wrapper").find('.dataTables_length select').select2(); // initialize select2 dropdown

	        /*
	        $('#sample_2_column_toggler input[type="checkbox"]').change(function(){
	            var iCol = parseInt($(this).attr("data-column"));
	            var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	            oTable.fnSetColumnVis(iCol, (bVis ? false : true));
	        });
			*/
    	},
        refreshTable : function() {
            this.oTable.api().ajax.reload();
        },
		onRemoveItem : function(e) {
			e.preventDefault();
			var data = this.oTable._($(e.currentTarget).closest("tr"));
			var itemModelClass = app.module("crud.models").itemModelClass;

			var self = this;
			var model = new itemModelClass(data[0]);
			model.destroy({
				success : function() {
					// TODO REMOVE DATA FROM DATATABLE TOO
					self.oTable
						.api()
						.row( $(e.currentTarget).closest("tr") )
						.remove()
						.draw();
				}
			});
		},
		onSelectItem : function(e) {

		},
        onAction : function(e) {
            e.preventDefault();
            var item = $(e.currentTarget);
            if (item.data("actionUrl")) {
                var url = item.data("actionUrl");
                var method = "GET";
                if (item.data("actionMethod")) {
                    method = item.data("actionMethod");
                }

                $.ajax(url, { method : method } );
            } else {
                var data = this.oTable._($(e.currentTarget).closest("tr"));
                this.trigger("action.datatable", _.first(data), item);
            }
        }
    });
});
