(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Discussion = (function(_super) {

    __extends(Discussion, _super);

    function Discussion() {
      Discussion.__super__.constructor.apply(this, arguments);
    }

    Discussion.prototype.events = {
      'click #comment-btn': 'saveComment'
    };

    Discussion.prototype.initialize = function() {
      var _this = this;
      this.collection = new arcs.collections.Discussion;
      arcs.on('arcs:indexChange', function() {
        return _this.collection.fetch();
      });
      this.collection.on('add remove reset', this.render, this);
      return this.collection.fetch();
    };

    Discussion.prototype.saveComment = function() {
      var $textarea, comment;
      $textarea = this.$el.find('textarea#content');
      comment = new arcs.models.Comment({
        resource_id: arcs.resource.id,
        content: $textarea.val()
      });
      $textarea.val('');
      comment.save();
      comment.set({
        name: 'You',
        created: 'just now'
      });
      return this.collection.add(comment);
    };

    Discussion.prototype.render = function() {
      $('#comment-wrapper').html(arcs.tmpl('viewer/discussion', {
        comments: this.collection.toJSON()
      }));
      return this;
    };

    return Discussion;

  })(Backbone.View);

}).call(this);
