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
      done: 0,
      pending: 1,
      error: 2
    };

    Tasks.prototype.initialize = function() {
      this.collection.on('add remove change reset sync', this.render, this);
      this.collection.url = arcs.baseURL + 'tasks';
      this.filterTasks();
      this.lastUpdated = new Date();
      this.autoUpdate = false;
      setInterval(_.bind(this.render, this), 5000);
      return setInterval(_.bind(this.update, this), 15000);
    };

    Tasks.prototype.events = {
      'keyup #filter-input': 'filterTasks',
      'change #auto-update': 'setUpdate',
      'click #rerun-btn': 'rerunTask',
      'click #delete-btn': 'deleteTask'
    };

    Tasks.prototype.rerunTask = function(e) {
      var task,
        _this = this;
      task = this.collection.get($(e.currentTarget).data('id'));
      return arcs.confirm("Are you sure you want to re-run this task?", "Task <b>" + task.id + "</b> will be queued.", function() {
        task.set('status', '1');
        return task.save({
          success: function() {
            return arcs.notify("Task successfully queued.");
          }
        });
      });
    };

    Tasks.prototype.deleteTask = function(e) {
      var task,
        _this = this;
      task = this.collection.get($(e.currentTarget).data('id'));
      return arcs.confirm("Are you sure you want to delete this task?", "Task <b>" + task.id + "</b> will be deleted.", function() {
        return task.destroy({
          success: function() {
            return arcs.notify("Task successfully deleted.");
          }
        });
      });
    };

    Tasks.prototype.filterTasks = function() {
      var val;
      val = this.$('#filter-input').val();
      if (this.TASK_STATUSES[val] != null) val = this.TASK_STATUSES[val];
      this.filter = new RegExp(val, 'i');
      return this.render();
    };

    Tasks.prototype.setUpdate = function() {
      return this.autoUpdate = !this.autoUpdate;
    };

    Tasks.prototype.update = function() {
      if (!this.autoUpdate) return;
      this.collection.fetch();
      return this.lastUpdated = new Date();
    };

    Tasks.prototype.render = function() {
      var filtered, m,
        _this = this;
      filtered = this.collection.filter(function(m) {
        return m.get('status').match(_this.filter) || m.get('job').match(_this.filter);
      });
      this.$('#tasks').html(arcs.tmpl('admin/tasks', {
        tasks: (function() {
          var _i, _len, _results;
          _results = [];
          for (_i = 0, _len = filtered.length; _i < _len; _i++) {
            m = filtered[_i];
            _results.push(m.toJSON());
          }
          return _results;
        })()
      }));
      this.$('#time').html(relativeDate(this.lastUpdated));
      return this;
    };

    return Tasks;

  })(Backbone.View);

}).call(this);
