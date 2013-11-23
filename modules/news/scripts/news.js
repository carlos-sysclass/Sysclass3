(function($){

  var object = {};

  _.extend(object, Backbone.Events);

  // MODELS
  var News = Backbone.Model.extend({
    /*
    // Default attributes for the todo item.
    defaults: function() {
      return {
        id          : 0,
        xscope_id   : 0,
        xentify_id  : 0,
        title       : "",
        data        : "",
        timestamp   : "",
        expire      : "",
        lessons_ID  : 0,
        classe_id   : 0,
        users_LOGIN : ""
      };
    },
    */
  });
  // COLLECTIONS
  var NewsCollection = Backbone.Collection.extend({
    url : "/module/news/data",
    model: News,
    comparator: 'order'
  });
  var newsCollection = new NewsCollection;
  // VIEWS
  var NewsFeedView = Backbone.View.extend({

    // Instead of generating a new element, bind to the existing skeleton of
    // the App already present in the HTML.
    el: $('#news-lastest'),

    newsFullScreenTemplate: _.template($('#news-item-template').html()),
    noDataFoundTemplate: _.template($('#news-nofound-template').html()),

    loaded : false,
    data_size: -1,
    collection_index: 0,
    collection: newsCollection,
    // Delegated events for creating new items, and clearing completed ones.
    
    events: {
      "click .tools .reload": "refresh",
      "click .tools .fullscreen": "handleFullScreen",
      "click .tools .normalscreen": "handleNormalScreen",
      "click #news-links a": "selectNews",
      "click .portlet-tools-search-btn": "doSearch",
      "submit form": "doSearch"
    },

    // At initialization we bind to the relevant events on the `Todos`
    // collection, when items are added or changed. Kick things off by
    // loading any preexisting todos that might be saved in *localStorage*.
    initialize: function() {
      //this.listenTo(newsCollection, 'add', this.render2);
      var self = this;
      this.$el.bind('portlet.search', function(e, search) {
        self.doSearch(search);
        return false;
      });
      this.listenTo(this.collection, 'sync', this.render);
      this.refresh();
    },
    refresh: function() {
      this.collection.fetch();
    },
    handleFullScreen : function(e) {
      this.$("#news-links,.slimScrollDiv").css({
        'height': 720
      });
    },
    handleNormalScreen : function() {
      this.$("#news-links,.slimScrollDiv").css({
        'height': 200
      });
    },
    doSearch : function(search) {
      console.log("searching for:" + search);
      search = search.toUpperCase();

      if (search.length == 0) {
        this.render(this.collection);
      } else {
        this.render(
          new Backbone.Collection(
            this.collection.filter(function(model, i, collection) {
              return (model.get("title").toUpperCase().indexOf(search) != -1);
            })
          )
        );
      }
    },
    selectNews : function(e) {
      // Get collection index from id
      var newsID = jQuery(e.currentTarget).data("news-id");
      var model = this.collection.get(newsID);

      this.$(".news-title").html(model.get('title'));
      this.$(".news-data").html(model.get('data'));
    },
    // Re-rendering the App just means refreshing the statistics -- the rest
    // of the app doesn't change.
    render: function(collection) {
      console.log(collection);
      this.$("#news-links").empty();

      if (collection.size() == 0) {
        /** @todo PUT HERE A JS TRANSLATOR MODULE */
        //$("#lastest-news-content").html("Ops! There's no news published yet!");
        this.$("#news-links").append(this.noDataFoundTemplate());
      } else {
        var threshold = 5;
        var self = this;
        
        collection.each(function(model,i) {
          console.log(self.newsFullScreenTemplate(model.toJSON()));  
          self.$("#news-links").append(
            self.newsFullScreenTemplate(model.toJSON())
          );
        });
      }
    }
  });


  var newsFeedView = new NewsFeedView();


})(jQuery);
