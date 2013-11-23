(function($){
  // MODELS
  var Folder = Backbone.Model.extend({});
  var Message = Backbone.Model.extend({});

  // COLLECTIONS
  var MessagesCollection = Backbone.Collection.extend({
    url : "/module/messages/data/messages",
    model: Message,
    comparator: 'order'
  });
  var messagesCollection = new MessagesCollection;

  var FoldersCollection = Backbone.Collection.extend({
    url : "/module/messages/data/folders",
    model: Folder,
    comparator: 'order'
  });
  var foldersCollection = new FoldersCollection;

  // VIEWS
  var MessageView = Backbone.View.extend({

    // Instead of generating a new element, bind to the existing skeleton of
    // the App already present in the HTML.
    //el: $('#inbox-container'),
    template: _.template($('#message-template').html()),
    // Delegated events for creating new items, and clearing completed ones.
    
    initialize: function() {
      this.listenTo(this.model, 'change', this.render);
    },
    // Re-rendering the App just means refreshing the statistics -- the rest
    // of the app doesn't change.
    render: function() {
      this.setElement(
        jQuery(this.template(this.model.toJSON()))
      );

      return this;
    }
  });

  var InboxView = Backbone.View.extend({

    // Instead of generating a new element, bind to the existing skeleton of
    // the App already present in the HTML.
    el: $('#inbox-container'),
    messages: messagesCollection,
    folders: foldersCollection,
    folderTemplate: _.template($('#folder-template').html()),
    activeFolder: null,
    events: {
      "click .folders-list a": "changeFolder"
    },
    
    
    initialize: function() {
      this.listenTo(this.folders, 'sync', this.renderFolders);
      this.listenTo(this.messages, 'sync', this.renderMessages);
      //this.listenTo(this.messages, 'change', this.renderMessagesAgain);

      this.refresh();
    },
    refresh: function() {
      this.folders.fetch();
      this.messages.fetch();
    },
    changeFolder : function(evt) {
      var clickedItem = jQuery(evt.currentTarget);
      this.$(".folders-list a").removeClass("active");
      this.$(".folders-list a .folder-icon").removeClass("icon-folder-open").addClass("icon-folder-close");

      this.activeFolder = jQuery(evt.currentTarget).addClass("active").attr("data-folder-id");
      jQuery(evt.currentTarget).find(".folder-icon").addClass("icon-folder-open");
      this.$(".inbox-header h1").html(clickedItem.data("title"));
      this.renderMessages();
    },
    renderFolders: function(collection, data, xhr) {
      //console.log(d,e,f);
      this.$(".folders-list").empty();

      if (this.activeFolder == null) {
        this.activeFolder = this.folders.at(0).get("id");
      }

      var self = this;
      
      this.folders.each(function(model,i) {
        if (self.activeFolder == model.get("id")) {
          //console.log(model.get("pathname"));
          self.$(".inbox-header h1").html(model.get("pathname"));
          
          var vars = jQuery.extend(model.toJSON(), {active : true});
        } else {
          var vars = jQuery.extend(model.toJSON(), {active : false});
        }
        self.$(".folders-list").append(self.folderTemplate(vars));

        //
        //folderTemplate
        
        /*
        model.get("pathname")
        <li class="trash">
          <a class="btn" href="javascript:;" data-title="Trash">Trash</a><span class="badge bagde-info"></span>
        </li>
        */
      });
      //this.renderMessages();
    },
    // Re-rendering the App just means refreshing the statistics -- the rest
    // of the app doesn't change.
    render: function() {
    },
    renderMessages: function() {
      if (this.activeFolder != null) {
        this.$("#messages-container tbody").empty();

        this.messages.where({f_folders_ID: this.activeFolder}).map(function(model) {
          var messageView = new MessageView({model: model});
          this.$("#messages-container tbody").append(messageView.render().el);
        });

        this.updateUnreads();
      }
    },
    updateUnreads : function() {
      var self = this;
      this.folders.each(function(model,i) {
        var totalUnread = self.messages.where({f_folders_ID: model.get("id"), viewed: "0"}).length;
        self.$(".folders-list [data-folder-id='" + model.get("id") + "'] .message-count").html(totalUnread);
      });
    }
  });
  var inboxView = new InboxView();

})(jQuery);
