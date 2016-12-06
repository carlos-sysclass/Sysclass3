$SC.module("portlet.messages", function(mod, app, Backbone, Marionette, $, _) {
	// MODELS
	var baseModel = app.module("models").getBaseModel();

	mod.models = {
		messages : {
			message : baseModel.extend({
				response_type : "object",
				//idAttribute : "user_id",
				urlRoot : "/module/enroll/messages/me"
				/*
				urlRoot : function() {
					return "/module/enroll/item/users/" + this.get("role_id")
				} 
				*/
			})
		}
	};

	mod.on("start", function() {
		var parent = app.module("portlet");
		// TODO THINK ABOUT MOVE THIS CLASS INTO THE MAIN VIEW
		var baseClass = app.module("views").baseClass;
		var baseChildTabViewClass = app.module("views").baseChildTabViewClass;
		//var baseChildTabViewItemClass = app.module("views").baseChildTabViewItemClass;

		var tableViewClass = $SC.module("utils.datatables").tableViewClass;

		var messagesBodyViewClass = baseClass.extend({
			events : {
				"click button.reply-action" : "replyTo",
				"click button.close-action" : "close"
			},
			sendDialog : app.module("dialogs.messages.send"),
			renderType : "byView",
			show : function() {
				this.$el.show();
			},
			close : function() {
				this.$el.hide();
			},
			replyTo : function() {
				var model = new this.sendDialog.models.message();
				model.set("user_id.0.id", this.model.get("user_id"));
				model.set("reply_to", this.model.get("id"));
				model.set("subject", "fwd:" + this.model.get("subject"));
				//this.sendDialog
				this.sendDialog.dialogView.setMode("user");
				this.sendDialog.dialogView.setModel(model);
				this.sendDialog.dialogView.open();
			}
		});

		var messageTableViewClass = tableViewClass.extend({
			initialize : function() {
				console.info('portlet.messages/messageTableViewClass::initialize');

				tableViewClass.prototype.initialize.apply(this, arguments);

				var self = this;

				this.messagesBodyView = new messagesBodyViewClass({
					el: "#message-body-container"
				});
				/*
				this.listenTo(this, "cellclick.datatable", function(model data, el) {
					console.warn(model data, el);
				})
				*/
				/*
				this.$("tbody").on('click', 'td', function () {
					var tr = $(this).closest('tr');
        			var row = self.getApi().row( tr );

        			console.warn(tr, row, row.data());
        		});
        		*/
			},
			doSearch : function(e, text) {
			    this.getApi().search(
			        text,
			        false,
			        true
			    ).draw();
			},
			getTableItemModel : function(data) {
				return new mod.models.messages.message(data);
        	},
        	onCellClick : function(model, data, el) {
        		this.messagesBodyView.setModel(model);
        		this.messagesBodyView.show();
        	}
		});


		/*
		var navigationViewClass = Backbone.View.extend({
			events : {
				//"click .class-change-action"		: "goToClass",
				//"click .class-next-action"		: "nextClass",
				//"click .lesson-prev-action"		: "prevLesson",
				//"click .lesson-next-action" 	: "nextLesson",
				"click .nav-prev-action" 		: "prevItem",
				"click .nav-next-action" 		: "nextItem",
				//"shown.bs.tab > .nav-tabs [data-toggle='tab']"		: "refreshScroll",
				//"click .nav-next-action" 		: "searchItem"
			},
			pointer: 0,
			initialize : function(opt) {
				console.info('portlet.content/navigationViewClass::initialize');

				this.pointer = opt.pointer;
			},
			render : function() {
				this.$(".entity-count")
					.html(this.collection().size());
					//cnsole.warn(this.$(".entity-current"), this.pointer);
				this.$(".entity-current")
					.html(this.pointer() + 1);
			},
			prevItem : function(e) {
				console.info('portlet.content/navigationViewClass::prevItem');
				//this.pointer--;

				e.preventDefault();

				this.collection().prev();

				if (this.pointer() <= 0) {
					//this.pointer = 0;
					this.$(".nav-prev-action").addClass("btn-disabled");
				}
				this.render();
			},
			nextItem : function(e) {
				console.info('portlet.content/navigationViewClass::nextItem');
				//this.pointer++;

				e.preventDefault();

				this.collection().next();

				if (this.pointer() >= this.collection().size()) {
					//this.pointer = this.collection.size() - 1;
					this.$(".nav-next-action").addClass("btn-disabled");
				}
				this.render();
			},
		});

		var entityDropdownViewItemClass = baseChildTabViewItemClass.extend({
			tagName : "li",
			events : {
				"click a.select-item" : "selectItem"
			},
			template : _.template($("#dropdown_child-item-template").html(), null, {variable: "model"}),
			selectItem : function(e) {
				this.parentView.trigger("dropdown-item.selected", this.model);
				//app.userSettings.set("class_id", this.model.get("id"));
				//mod.programsCollection.moveToCourse(this.model.get("id"));

				//$("[href='#tab_course_units']").tab('show');
			},
		});
		var entityDropdownViewClass = baseChildTabViewClass.extend({
			nofoundTemplate : _.template($("#tab_courses_child-nofound-template").html()),
			childViewClass : entityDropdownViewItemClass,
			initialize: function(opt) {
				console.info('portlet.content/courseDropdownViewClass::initialize');

				if (_.has(opt, 'childViewClass')) {
					this.childViewClass = opt.childViewClass;
				}

				baseChildTabViewClass.prototype.initialize.apply(this, arguments);
			},
			setCollection : function(model) {
				baseChildTabViewClass.prototype.setCollection.apply(this, arguments);

				this.render();
			},
			makeCollection: function() {
				return this.collection;
			},
		});
		*/
		var messagesTabViewClass = baseChildTabViewClass.extend({
			//template : _.template($("#tab_program_description-template").html(), null, {variable : 'model'}),
			initialize: function() {
				console.info('portlet.messages/messagesTabViewClass::initialize');
				//this.listenTo(this.model, 'sync', this.render.bind(this));

				var message_context = $SC.getResource("messages_context");

    			// START DATATABLE HERE
    		    this.tableView =  new messageTableViewClass({
			        el : "#messages-table-messages",
			        datatable : {
			            "sAjaxSource": message_context.ajax_source,
			            "aoColumns": message_context.datatable_fields,
			            dom : "<t>",
			            //bScrollInfinite: true,
			            //bScrollCollapse: false,
			            //sScrollY: "274px",
			            sScrollY: true,  
			            paging: false
			        },
			        scrollY : true,
			        slimScroll : {
					    height: '274px',
					}
			    });
    			$SC.addTable("messages-table-messages", this.tableView);

				this.render();
			},
			doSearch : function(e, text) {
				this.tableView.doSearch(e, text);
			},
			render : function(e) {
				console.info('portlet.messages/messagesTabViewClass::render');
				//this.$(".program-description-content").empty().append(this.template(this.model.toJSON()));

				//this.navigationView.render();
				this.renderProgress();
			},
			renderProgress : function() {
				console.info('portlet.messages/messagesTabViewClass::renderProgress');
			  	
				var totalMessages = this.tableView.oTable.api().data().count();

				if (totalMessages > 0) {
					$(".messages-indicator span.counter").html(totalMessages).show();
				} else {
					$(".messages-indicator span.counter").hide();
				}

				if (totalMessages > 1) {
					$(".messages-indicator span.singular").hide();
					$(".messages-indicator span.plural").show();
				} else {
					$(".messages-indicator span.singular").show();
					$(".messages-indicator span.plural").hide();
				}
			},
		});


		/* CLASSES TABS VIEW CLASSES */
		this.widgetViewClass = parent.widgetViewClass.extend({
	    	events : function() {
	    		return {
		    		"click .dialogs-messages-search-action" : "showSearch",
		    		"blur .search-container input" : "hideSearch",
		    		"keyup .search-container input" : "doSearch",
		    	};
    		},
    		activeView : null,
			messagesTabView : null,
			forumTabView : null,
			faqTabView : null,
			start : function() {
				console.info('portlet.messages/widgetViewClass::start');
				Marionette.triggerMethodOn(this, "beforeStart");
				// CREATE SUB-VIEWS AND FETCH MODELS AND COLLECTIONS

				this.$(".portlet-title").height(26);

				this.startViews();
				//this.startCourseView();
				//this.startUnitView();

				Marionette.triggerMethodOn(this, "start");

				//this.listenTo(this.collection, "program.changed", this.renderProgram.bind(this));
				//this.listenTo(this.collection, "course.changed", this.renderCourse.bind(this));
				//this.listenTo(this.collection, "unit.changed", this.renderUnit.bind(this));

				//this.renderProgram();
				//this.renderCourse();
				//this.renderUnit();

				//this.listenTo(mod.progressCollection, "sync", this.renderProgress.bind(this));
			},
			showSearch : function() {
				// GET THE CURRENT ACTIVE TAB
				var selector = this.$(".widget-tabs .active [data-toggle='tab']").attr("href");

				if (selector == "#tab_messages_messages") {
					// ACTIVE TAB IS MESSAGE, SEARCH ON HIS DATATABLE
					this.activeView = this.messagesTabView;
				} else {
					this.activeView = null;
				}

				if (!_.isNull(this.activeView)) {

					// OPEN SEARCH INPUT
					this.$(".dialogs-messages-search-action").fadeOut(500, function() {
						this.$(".search-container").fadeIn(500);
						this.$(".search-container input").focus();
					}.bind(this));
				}
			},
			hideSearch : function() {
				this.$(".search-container").fadeOut(500, function() {
					this.$(".dialogs-messages-search-action").fadeIn(500);
				}.bind(this));
			},
			doSearch : function(e) {
				if (!_.isNull(this.activeView)) {
					this.activeView.doSearch(e, $(e.currentTarget).val());
				}
			},
			startViews : function() {
				console.info('portlet.content/widgetViewClass::startProgramView');
				if (_.isNull(this.messagesTabView)) {
					/*
					this.programTabView = new programTabViewClass({
						model : this.collection.getCurrentProgram(),
						//collection : this.collection,
						el : this.$("#program-tab"),
            			portlet : this.$el
					});
					*/
					/*
					this.programDropdownView = new entityDropdownViewClass({
						el : this.$(".program-dropdown")
					});

					this.courseDropdownView = new entityDropdownViewClass({
						el : this.$(".course-dropdown")
					});
					this.unitDropdownView = new entityDropdownViewClass({
						el : this.$(".unit-dropdown")
					});
					*/
					this.messagesTabView 	= new messagesTabViewClass({
						el : $("#tab_messages_messages")
						//model : this.collection.getCurrentProgram()
					});
					/*
					this.programCoursesTabView = new programCoursesTabViewClass({
						el : $("#tab_program_courses"),
						childContainer : "table.course-table tbody",
						model : this.collection.getCurrentProgram()
					});

					this.courseUnitsTabView = new courseUnitsTabViewClass({
						el : this.$("#tab_course_units"),
						childContainer : "table.unit-table tbody",
						model : this.model
					});

		            this.unitVideoTabView   = new unitVideoTabViewClass({
		                el : this.$("#unit-video-container"),
		                childContainer : ".popupcontent-body",
		                // model : this.model,
		            });
		            */
		            // MATERIALS
		            /*
		            this.unitMaterialsTabView   = new unitMaterialsTabViewClass({
		                el : this.$("#unit-material-container"),
		                childContainer : "table.unit-material-table tbody",
		                // model : this.model,
		            });
		            */

					this.messagesTabView.render();
					//this.programCoursesTabView.render();
					//this.courseUnitsTabView.render();

					/*
					this.listenTo(this.courseUnitsTabView, "watch:video", function(unitModel) {
						this.unitVideoTabView.setModel(unitModel);
						this.unitVideoTabView.render();

						//this.courseUnitsTabView.showContentArea();
					}.bind(this));

					this.listenTo(this.courseUnitsTabView, "list:materials", function(unitModel) {
						this.unitMaterialsTabView.setModel(unitModel);
						this.unitMaterialsTabView.render();

						//this.courseUnitsTabView.showContentArea();
						//this.courseUnitsTabView.showContentSidebar();
					}.bind(this));

					this.listenTo(this.programDropdownView, "dropdown-item.selected", function(programModel) {
						mod.programsCollection.moveToProgram(programModel.get("id"));
					}.bind(this));

					this.listenTo(this.courseDropdownView, "dropdown-item.selected", function(courseModel) {
						mod.programsCollection.moveToCourse(courseModel.get("id"));
					}.bind(this));

					this.listenTo(this.unitDropdownView, "dropdown-item.selected", function(unitModel) {
						mod.programsCollection.moveToUnit(unitModel.get("id"));
					}.bind(this));
					*/

					//this.programTabView.render();
				}
			},
		});
	});

	mod.on("start", function() {
		this.widgetView = new this.widgetViewClass({
			model : app.userSettings,
			//collection : mod.programsCollection,
			el: '#messages-widget'
		});

		console.warn(this.widgetView);
	});
});
