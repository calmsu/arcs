(function() {
  var _base,
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  if ((_base = arcs.views).admin == null) _base.admin = {};

  arcs.views.admin.Tasks = (function(_super) {

    __extends(Tasks, _super);

    function Tasks() {
      Tasks.__super__.constructor.apply(this, arguments);
    }

    Tasks.prototype.TASK_STATUSES = {
      '0': 'done',
      '1': 'pending',
      '2': 'error'
    };

    Tasks.prototype.initialize = function() {
      this.collection.on('add remove change sync', this.render, this);
      return this.render();
    };

    Tasks.prototype.render = function() {
      this.$('#tasks').html(arcs.tmpl('admin/tasks', {
        tasks: this.collection.toJSON()
      }));
      return this;
    };

    return Tasks;

  })(Backbone.View);

}).call(this);
