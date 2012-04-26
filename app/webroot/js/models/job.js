(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Job = (function(_super) {

    __extends(Job, _super);

    function Job() {
      Job.__super__.constructor.apply(this, arguments);
    }

    Job.prototype.urlRoot = arcs.baseURL + 'jobs';

    return Job;

  })(Backbone.Model);

}).call(this);
