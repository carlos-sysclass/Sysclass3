$SC.module("grades.form", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	//this.config = $SC.module("crud.config").getConfig();
	//var entity_id = mod.config.entity_id;
	//
	this.startWithParent = false;

	mod.on("start", function(formView) {

		var rangeSliderItemViewClass = Backbone.View.extend({
			events : {
				"change :input" : "setGrade",
				"confirmed.bs.confirmation .delete-item-action" : "delete",
			},
			template : _.template($("#rule-slider-template").html(), null, {variable : "model"}),
			tagName : "li",
			className : "list-file-item green-stripe",
			render : function() {
                this.$el.html(
                	this.template(this.model.toJSON())
                );

                return this;
			},
			getSlider : function() {
				return this.$(".noUi-control").get(0);
			},
			getPrevSlider : function() {
				return this.$el.prev().find(".noUi-control").get(0);
			},
			getNextSlider : function() {
				return this.$el.next().find(".noUi-control").get(0);
			},
			updateSlider : function() {
				app.module("ui").refresh(this.$el);

 				var handlesSlider = this.getSlider();
 				var startValue = 0;
 				var currentRange = this.model.get("range");
 				var self = this;

            	// CALCULATE PREVIOUS VALUE
            	if (!currentRange) {
                	var previousSlider = this.getPrevSlider();

                	if (!_.isUndefined(previousSlider) && previousSlider.noUiSlider) {

                		var previousValue = previousSlider.noUiSlider.get();

                		if (!_.isArray(previousValue)) {
	                		var newValue = previousValue / 2;
	                		previousSlider.noUiSlider.set(newValue);
	                	} else {
	                		var decrement = (previousValue[1] - previousValue[0]) / 2;
	                		var newValue = previousValue[1] - decrement - 1;
	                		previousSlider.noUiSlider.set([null, newValue]);
	                	}
	                	currentRange = [ newValue+1, 100 ];
	                } else {
	                	currentRange = [ 0, 100 ];
	                }
	            }
				noUiSlider.create(handlesSlider, {
					start: currentRange,
					connect : true,
					step: 1,
					range: {
						'min': [  0 ],
						'max': [ 100 ]
					}
				});

				// EVENT HANDLING
				handlesSlider.noUiSlider.on("slide", function(values,who,range) {
					var newValue = range[who];
					if (who == 0) {
						// MOVE THE PREVIOUS MAX SLIDER
            			var previousSlider = self.getPrevSlider();

            			if (!_.isUndefined(previousSlider) && previousSlider.noUiSlider) {
           					previousSlider.noUiSlider.set([null, newValue-1]);
            			} else {
							handlesSlider.noUiSlider.set([0, null]);
            			}
					} else {
            			var nextSlider = self.getNextSlider();

               			if (!_.isUndefined(nextSlider) && nextSlider.noUiSlider) {

							var nextValues = nextSlider.noUiSlider.get();
							if (newValue >= nextValues[1]) {
								// STOP CURRENT MOVING
								nextSlider.noUiSlider.set([newValue+1, newValue+2]);
							} else {
	           					nextSlider.noUiSlider.set([newValue+1, null]);
	               			}
	               		}

            			if (!_.isUndefined(nextSlider) && nextSlider.noUiSlider) {
           					nextSlider.noUiSlider.set([newValue+1, null]);
            			} else {
            				handlesSlider.noUiSlider.set([null, 100]);
            			}
					}
				});
				handlesSlider.noUiSlider.on("update", function(values,who,range) {
					if (who == 0) {
						self.$(".range-begin").html(range[who]);
					} else {
						self.$(".range-end").html(range[who]);
					}
					// UPDATE MODEL
					self.model.set("range", range);
				});
			},
			setGrade : function(e) {
				console.info('grades.form/rangeSliderItemViewClass::setGrade');
				this.model.set("grade", $(e.currentTarget).val());
			},
			delete : function() {
				var handlesSlider = this.getSlider();

				var values = handlesSlider.noUiSlider.get();

				var innerValue = values[1] - ((values[1] - values[0]) / 2);

				var prevSlider = this.getPrevSlider();
				var nextSlider = this.getNextSlider();

				this.model.destroy();
				this.remove();

				if (_.isUndefined(prevSlider) && !_.isUndefined(nextSlider)) {
					nextSlider.noUiSlider.set([values[0], null]);
				} else if (_.isUndefined(nextSlider)  && !_.isUndefined(prevSlider)) {
					prevSlider.noUiSlider.set([null, values[1]]);
				} else if (!_.isUndefined(prevSlider)  && !_.isUndefined(nextSlider)) {
					prevSlider.noUiSlider.set([null, innerValue]);
					nextSlider.noUiSlider.set([innerValue+1, null]);
				}
			}
		});

		this.rangeSlidersViewClass = Backbone.View.extend({
			events : {
				"click .add-rule-action" : "addChoice",
				"click .show-tips" : "showTips"
			},
			initialize : function() {
				this.listenToOnce(formView, "form:rendered", this.render.bind(this));
			},
			render : function() {
				this.$(".ranges-rules-container").empty();
				this.collection = new mod.collections.grades.ranges(this.model.get("grades"));

				this.listenTo(this.collection, "add", this.addOne.bind(this));
				this.listenTo(this.collection, "remove change", function() {
					// UPDATES INDEXES
					this.model.unset("grades");
					this.model.set("grades", this.collection.toJSON());
				}.bind(this));

				this.collection.each(this.addOne.bind(this));
			},
            addChoice : function(e) {
                console.info('grades.form/rangeSliderViewClass::addChoice');

                var model = new mod.models.grades.range({},{
                    collection : this.collection
                });

                this.collection.add(model);
            },
            addOne : function(model) {
                console.info('grades.form/rangeSliderViewClass::addOne');

                var rangeSliderItemView = new rangeSliderItemViewClass({
                	model : model
                });

                this.$(".ranges-rules-container").append(
                	rangeSliderItemView.render().el
                );

                rangeSliderItemView.updateSlider();
            },
            showTips : function(e) {
                e.preventDefault();
                $(e.currentTarget).hide();
                this.$(".tips-container").show(500);
            }
		});

		this.rangeSliderView = new this.rangeSlidersViewClass({
			model : formView.model,
			el : "#ranges-container"
		});
	});

	this.models = {
		grades : {
			range : Backbone.Model.extend({})
		}
	};

	this.collections = {
		grades : {
			ranges : Backbone.Collection.extend({
				model : mod.models.grades.range
			})
		}
	};

    $SC.module("crud.views.edit").on("start", function() {
    	if (!mod._isInitialized && this.getForm) {
           	mod.start(this.getForm());
        }
    });

    $SC.module("crud.views.add").on("start", function() {
    	if (!mod._isInitialized && this.getForm) {
    		mod.start(this.getForm());
    	}
    });
});
