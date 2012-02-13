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
    var _this = this;
    this.collection = new arcs.collections.Discussion;
    arcs.bind('resourceChange', function() {
      return _this.update();
    });
    _.bindAll(this, 'render');
    this.collection.bind('add', this.render, this);
    this.collection.bind('remove', this.render, this);
    return this.update();
  };

  Discussion.prototype.saveComment = function() {
    var $textarea, comment;
    $textarea = this.el.find('textarea#content');
    comment = new arcs.models.Comment({
      resource_id: arcs.resource.id,
      content: $textarea.val(),
      _name: 'You',
      _created: 'just now'
    });
    $textarea.val('');
    comment.save();
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
    $discussion.html(Mustache.render(arcs.templates.discussion, {
      comments: this.collection.toJSON()
    }));
    return this;
  };

  return Discussion;

})(Backbone.View);
