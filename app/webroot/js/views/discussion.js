var __hasProp = Object.prototype.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

arcs.views.Discussion = (function(_super) {

  __extends(Discussion, _super);

  function Discussion() {
    Discussion.__super__.constructor.apply(this, arguments);
  }

  Discussion.prototype.events = {
    'click #comment-button': 'saveComment'
  };

  Discussion.prototype.initialize = function() {
    this.collection = new arcs.collections.Discussion;
    arcs.on('resourceChange', this.update, this);
    this.collection.on('add remove', this.render, this);
    return this.update();
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

  Discussion.prototype.update = function() {
    var _this = this;
    return this.collection.fetch({
      success: function() {
        return _this.render();
      }
    });
  };

  Discussion.prototype.render = function() {
    var $discussion;
    $discussion = $('#comment-wrapper');
    $discussion.html(arcs.tmpl('discussion', {
      comments: this.collection.toJSON()
    }));
    return this;
  };

  return Discussion;

})(Backbone.View);
