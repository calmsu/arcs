(function() {
  var _base,
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  if ((_base = arcs.views).admin == null) _base.admin = {};

  arcs.views.admin.Jobs = (function(_super) {

    __extends(Jobs, _super);

    function Jobs() {
      Jobs.__super__.constructor.apply(this, arguments);
    }

    Jobs.prototype.JOB_STATUSES = {
      done: 0,
      pending: 1,
      failing: 2,
      failed: 3,
      interrupted: 4
    };

    Jobs.prototype.options = {
      autoUpdate: false,
      updateEvery: 15000
    };

    Jobs.prototype.initialize = function() {
      this.collection.on('add remove change reset sync', this.render, this);
      this.collection.url = arcs.baseURL + 'jobs';
      this.filterJobs();
      this.lastUpdated = new Date();
      setInterval(_.bind(this.render, this), 5000);
      return setInterval(_.bind(this.update, this), this.options.updateEvery);
    };

    Jobs.prototype.events = {
      'keyup #filter-input': 'filterJobs',
      'change #auto-update': 'setUpdate',
      'click #retry-btn': 'retryJob',
      'click #delete-btn': 'deleteJob',
      'click #release-btn': 'releaseJob',
      'click #show-btn': 'showJob'
    };

    Jobs.prototype.showJob = function(e) {
      var job;
      job = this.collection.get($(e.currentTarget).data('id'));
      return arcs.prompt("Job " + job.id, arcs.tmpl('admin/show_job', job.toJSON()));
    };

    Jobs.prototype.retryJob = function(e) {
      var job,
        _this = this;
      job = this.collection.get($(e.currentTarget).data('id'));
      return arcs.confirm("Are you sure you want to retry this job?", "Job <b>" + job.id + "</b> will be set to <b>pending</b>.", function() {
        job.set({
          status: '1',
          failed_at: null,
          error: null
        });
        arcs.loader.show();
        return job.save({}, {
          success: function() {
            return arcs.loader.hide();
          }
        });
      });
    };

    Jobs.prototype.deleteJob = function(e) {
      var job,
        _this = this;
      job = this.collection.get($(e.currentTarget).data('id'));
      return arcs.confirm("Are you sure you want to delete this job?", "Job <b>" + job.id + "</b> will be deleted.", function() {
        arcs.loader.show();
        return job.destroy({
          success: function() {
            return arcs.loader.hide();
          }
        });
      });
    };

    Jobs.prototype.releaseJob = function(e) {
      var job,
        _this = this;
      job = this.collection.get($(e.currentTarget).data('id'));
      return arcs.confirm("Are you sure you want to release this job?", "Job <b>" + job.id + "</b> will be released.", function() {
        arcs.loader.show();
        return job.save({
          locked_by: null,
          locked_at: null
        }, {
          success: function() {
            return arcs.loader.hide();
          }
        });
      });
    };

    Jobs.prototype.filterJobs = function() {
      var val;
      val = this.$('#filter-input').val();
      if (this.JOB_STATUSES[val] != null) val = this.JOB_STATUSES[val];
      this.filter = new RegExp(val, 'i');
      return this.render();
    };

    Jobs.prototype.setUpdate = function() {
      return this.options.autoUpdate = !this.options.autoUpdate;
    };

    Jobs.prototype.update = function() {
      var _this = this;
      if (!this.options.autoUpdate) return;
      return this.collection.fetch({
        success: function() {
          return _this.lastUpdated = new Date();
        }
      });
    };

    Jobs.prototype.render = function() {
      var filtered, m,
        _this = this;
      filtered = this.collection.filter(function(m) {
        return m.get('status').match(_this.filter) || m.get('name').match(_this.filter);
      });
      this.$('#jobs').html(arcs.tmpl('admin/jobs', {
        jobs: (function() {
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
      $('.popover').hide();
      this.$('.has-error').popover();
      return this;
    };

    return Jobs;

  })(Backbone.View);

}).call(this);
