(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.DiscussionTab = (function(_super) {

    __extends(DiscussionTab, _super);

    function DiscussionTab() {
      DiscussionTab.__super__.constructor.apply(this, arguments);
    }

    DiscussionTab.prototype.events = {
      'click #comment-btn': 'saveComment'
    };

    DiscussionTab.prototype.initialize = function() {
      var _this = this;
      this.collection = new arcs.collections.Discussion;
      this.collection.on('add remove reset', this.render, this);
      this.$tab = $('#discussion-btn');
      this.$tab.on('click', function() {
        return _this.update();
      });
      arcs.bus.on('indexChange', function() {
        if (_this.isActive()) return _this.update();
      });
      if (this.isActive()) return this.update();
    };

    DiscussionTab.prototype.activate = function() {
      $('#discussion-btn a').tab('show');
      return this.collection.fetch();
    };

    DiscussionTab.prototype.update = function() {
      var _this = this;
      this.$el.parent().css('opacity', '0.2');
      return this.collection.fetch({
        success: function() {
          return _this.$el.parent().css('opacity', '1.0');
        },
        error: function() {
          return arcs.notify('An error occurred while loading comments', 'error');
        }
      });
    };

    DiscussionTab.prototype.isActive = function() {
      return $('#discussion-btn').hasClass('active');
    };

    DiscussionTab.prototype.saveComment = function() {
      var $textarea, comment;
      $textarea = this.$el.find('textarea#content');
      if (!$textarea.val()) return arcs.notify('Enter a comment.');
      comment = new arcs.models.Comment({
        resource_id: arcs.resource.id,
        content: $textarea.val()
      });
      $textarea.val('');
      comment.save();
      comment.set({
        created: new Date,
        username: arcs.user.get('username'),
        name: arcs.user.get('name')
      });
      return this.collection.add(comment);
    };

    DiscussionTab.prototype.render = function() {
      $('#comment-wrapper').html(arcs.tmpl('viewer/discussion', {
        comments: this.collection.toJSON()
      }));
      return this;
    };

    return DiscussionTab;

  })(Backbone.View);

}).call(this);
