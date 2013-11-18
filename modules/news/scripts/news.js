(function($){
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

    loaded : false,
    data_size: -1,
    collection_index: 0,
    collection: newsCollection,
    // Delegated events for creating new items, and clearing completed ones.
    
    events: {
      "click .tools .reload": "refresh",
      "click .pager .previous": "go_to_previous",
      "click .pager .next": "go_to_next"
    },
    // At initialization we bind to the relevant events on the `Todos`
    // collection, when items are added or changed. Kick things off by
    // loading any preexisting todos that might be saved in *localStorage*.
    initialize: function() {
      //this.listenTo(newsCollection, 'add', this.render2);
      this.listenTo(this.collection, 'sync', this.render);
      this.refresh();
    },
    refresh: function() {
      newsCollection.fetch();
    },
    go_to_previous : function() {
      if (this.$(".pager .previous").is(".disabled")) {
        return;
      }
      this.collection_index--;
      this.render();
    },
    go_to_next : function(e) {
      if (this.$(".pager .next").is(".disabled")) {
        return;
      }
      this.collection_index++;
      this.render();
    },
    // Re-rendering the App just means refreshing the statistics -- the rest
    // of the app doesn't change.
    render: function() {
      if (this.data_size != this.collection.size()) {

        if (this.collection_index <= 0) {
          this.$(".pager .previous").addClass("disabled");
          this.collection_index = 0;
        } else {
          this.$(".pager .previous").removeClass("disabled");
        }
        if (this.collection_index >= this.collection.size()) {
          this.$(".pager .next").addClass("disabled");
          this.collection_index = Math.max(this.collection.size() - 1, 0);
        } else {
          this.$(".pager .next").removeClass("disabled");
        }

        if (this.collection.size() == 0) {
          /** @todo PUT HERE A JS TRANSLATOR MODULE */
          $("#lastest-news-content").html("Ops! There's no news published yet!");
        } else {
          var model = newsCollection.at(this.collection_index);
          this.$(".news-title").html(model.get('title'));
          this.$(".news-data").html(model.get('data'));
        }
      }
    }
  });
  var newsFeedView = new NewsFeedView();

})(jQuery);
