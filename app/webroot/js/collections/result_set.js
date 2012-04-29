(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.collections.ResultSet = (function(_super) {

    __extends(ResultSet, _super);

    function ResultSet() {
      ResultSet.__super__.constructor.apply(this, arguments);
    }

    ResultSet.prototype.model = arcs.models.Resource;

    ResultSet.prototype.url = function() {
      return arcs.baseURL + 'resources/search';
    };

    ResultSet.prototype.selected = function() {
      return this.filter(function(result) {
        return result.get('selected');
      });
    };

    ResultSet.prototype.notSelected = function() {
      return this.reject(function(result) {
        return result.get('selected');
      });
    };

    ResultSet.prototype.anySelected = function() {
      return this.any(function(result) {
        return result.get('selected');
      });
    };

    ResultSet.prototype.numSelected = function() {
      return this.selected().length;
    };

    ResultSet.prototype.select = function(result) {
      return this._eachSetSelected(result, function() {
        return true;
      });
    };

    ResultSet.prototype.toggle = function(result) {
      return this._eachSetSelected(result, function(m) {
        return !m.get('selected');
      });
    };

    ResultSet.prototype.unselect = function(result) {
      return this._eachSetSelected(result, function() {
        return false;
      });
    };

    ResultSet.prototype.unselectAll = function() {
      return this.each(function(result) {
        return result.set('selected', false, {
          silent: true
        });
      });
    };

    ResultSet.prototype._eachSetSelected = function(result, func) {
      var id, model, _i, _len, _results;
      if (!_.isArray(result)) result = [result];
      _results = [];
      for (_i = 0, _len = result.length; _i < _len; _i++) {
        id = result[_i];
        model = this.get(id);
        if (!model) continue;
        _results.push(model.set('selected', func(model), {
          silent: true
        }));
      }
      return _results;
    };

    ResultSet.prototype.parse = function(response) {
      this.metadata = response;
      return response.results;
    };

    return ResultSet;

  })(Backbone.Collection);

}).call(this);
