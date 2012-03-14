(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.SearchActions = (function(_super) {

    __extends(SearchActions, _super);

    function SearchActions() {
      SearchActions.__super__.constructor.apply(this, arguments);
    }

    SearchActions.prototype.initialize = function() {
      this.results = this.collection;
      return arcs.keys.add('o', true, this.openSelected, this);
    };

    SearchActions.prototype.events = {
      'dblclick img': 'openResult',
      'click #open-btn': 'openSelected',
      'click #open-colview-btn': 'collectionFromSelected',
      'click #collection-btn': 'namedCollectionFromSelected',
      'click #attribute-btn': 'editSelected',
      'click #flag-btn': 'flagSelected',
      'click #bookmark-btn': 'bookmarkSelected',
      'click #tag-btn': 'tagSelected'
    };

    SearchActions.prototype.openResult = function(result) {
      result = this._modelFromRef(result);
      return window.open(arcs.baseURL + 'resource/' + result.id);
    };

    SearchActions.prototype.tagResult = function(result, keyword) {
      var tag;
      tag = new arcs.models.Tag({
        resource_id: result.id,
        tag: keyword
      });
      return tag.save();
    };

    SearchActions.prototype.flagResult = function(result, reason, explanation) {
      var flag;
      flag = new arcs.models.Flag({
        resource_id: result.id,
        reason: reason,
        explanation: explanation
      });
      return flag.save();
    };

    SearchActions.prototype.bookmarkResult = function(result, note) {
      var bkmk;
      bkmk = new arcs.models.Bookmark({
        resource_id: result.id,
        description: note
      });
      return bkmk.save();
    };

    SearchActions.prototype.tagSelected = function() {
      var n,
        _this = this;
      if (!this.results.anySelected()) return;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Tag Selected',
        subtitle: "" + n + " " + (arcs.pluralize('resource', n)) + " will be tagged.",
        backdrop: true,
        inputs: {
          tag: {
            label: false,
            complete: arcs.utils.complete.tag,
            focused: true
          }
        },
        buttons: {
          save: {
            "class": 'btn success',
            callback: function(vals) {
              var result, _i, _len, _ref;
              _ref = _this.results.selected();
              for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                result = _ref[_i];
                _this.tagResult(result, vals.tag);
              }
              return _this._notify('tagged');
            }
          },
          cancel: function() {}
        }
      });
    };

    SearchActions.prototype.flagSelected = function() {
      var n,
        _this = this;
      if (!this.results.anySelected()) return;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Flag Selected',
        subtitle: "" + n + " " + (arcs.pluralize('resource', n)) + " will be flagged.",
        inputs: {
          reason: {
            type: 'select',
            options: {
              'Incorrect attributes': 'incorrect',
              'Spam': 'spam',
              'Duplicate': 'duplicate',
              'Other': 'other'
            }
          },
          explain: {
            type: 'textarea'
          }
        },
        buttons: {
          save: {
            "class": 'btn success',
            callback: function(vals) {
              var result, _i, _len, _ref;
              _ref = _this.results.selected();
              for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                result = _ref[_i];
                _this.flagResult(result, vals.reason, vals.explanation);
              }
              return _this._notify('flagged');
            }
          },
          cancel: function() {}
        }
      });
    };

    SearchActions.prototype.editSelected = function() {
      if (!this.results.anySelected()) return arcs.notify("Select a result");
      if (this.results.numSelected() > 1) return this.batchEditSelected();
      return new arcs.views.Modal({
        title: 'Edit Attributes',
        subtitle: '',
        buttons: {
          save: {
            "class": 'btn success',
            callback: function() {}
          },
          cancel: function() {}
        }
      });
    };

    SearchActions.prototype.batchEditSelected = function() {
      var batchFields, checked, field, inputs, r, results, value, values, _i, _len, _ref;
      inputs = {};
      results = this.results.selected();
      batchFields = _.difference(results[0].MODIFIABLE, results[0].SINGULAR);
      for (_i = 0, _len = batchFields.length; _i < _len; _i++) {
        field = batchFields[_i];
        values = (function() {
          var _j, _len2, _results;
          _results = [];
          for (_j = 0, _len2 = results.length; _j < _len2; _j++) {
            r = results[_j];
            _results.push(r.get(field));
          }
          return _results;
        })();
        _ref = [false, ''], checked = _ref[0], value = _ref[1];
        if (_.unique(values).length === 1 && values[0] !== void 0) {
          checked = true;
          value = values[0];
        }
        inputs[field] = {
          checkbox: checked,
          value: value != null ? value : ''
        };
      }
      return new arcs.views.Modal({
        title: 'Edit Attributes (Multiple)',
        subtitle: "The values of checked fields will be applied to all " + "of the selected results, even when blank.",
        template: 'ui/modal_columned',
        inputs: inputs,
        buttons: {
          save: {
            "class": 'btn success',
            callback: function() {}
          },
          cancel: function() {}
        }
      });
    };

    SearchActions.prototype.namedCollectionFromSelected = function() {
      var n;
      if (!this.results.anySelected()) return arcs.notify("Select a result");
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Create a Collection',
        subtitle: ("A collection with " + n + " " + (arcs.pluralize('resource', n)) + " ") + "will  be created.",
        inputs: {
          title: {
            focused: true
          },
          description: {
            type: 'textarea'
          }
        },
        buttons: {
          save: {
            "class": 'btn success',
            callback: this.collectionFromSelected,
            context: this
          },
          cancel: function() {}
        }
      });
    };

    SearchActions.prototype.collectionFromSelected = function(vals) {
      var collection, _ref, _ref2,
        _this = this;
      if (!this.results.anySelected()) return arcs.notify("Select a result");
      collection = new arcs.models.Collection({
        title: (_ref = vals.title) != null ? _ref : "Temporary Collection",
        description: (_ref2 = vals.description) != null ? _ref2 : "Results from search",
        public: false,
        temporary: true,
        members: _.map(this.results.selected(), function(r) {
          return r.get('id');
        })
      });
      return collection.save({}, {
        success: function(newCol) {
          return window.open(arcs.baseURL + 'collection/' + newCol.id);
        },
        error: function() {
          return arcs.notify('An error occurred.', 'error');
        }
      });
    };

    SearchActions.prototype.bookmarkSelected = function() {
      var result, _i, _len, _ref;
      _ref = this.results.selected();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        this.bookmarkResult(result);
      }
      return this._notify('bookmarked');
    };

    SearchActions.prototype.openSelected = function() {
      return this._forSelected(this.openResult, 'opened');
    };

    SearchActions.prototype._notify = function(verb) {
      var msg, n;
      if (verb == null) verb = 'affected';
      n = this.results.numSelected();
      msg = "" + n + " " + (arcs.pluralize('resource', n)) + " " + (arcs.conjugate('was', n)) + " " + verb + ".";
      return arcs.notify(msg, 'success');
    };

    SearchActions.prototype._modelFromRef = function(ref) {
      var id;
      if (ref instanceof arcs.models.Resource) return ref;
      if (ref instanceof jQuery.Event) {
        ref.preventDefault();
        ref = $(ref.currentTarget).parent();
      }
      id = $(ref).find('img').attr('data-id');
      return this.results.get(id);
    };

    return SearchActions;

  })(Backbone.View);

}).call(this);
