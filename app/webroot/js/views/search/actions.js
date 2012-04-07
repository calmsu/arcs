(function() {
  var _base,
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  if ((_base = arcs.views).search == null) _base.search = {};

  arcs.views.search.Actions = (function(_super) {

    __extends(Actions, _super);

    function Actions() {
      Actions.__super__.constructor.apply(this, arguments);
    }

    Actions.prototype.initialize = function() {
      this.results = this.collection;
      this.ctxMenu = new arcs.views.ContextMenu({
        el: $(document),
        filter: 'img',
        options: this._getContextOptions(),
        onShow: function(e) {
          $(e.currentTarget).parents('.result').addClass('selected');
          return arcs.trigger('arcs:selection');
        },
        context: this
      });
      arcs.keys.add('o', true, this.openSelected, this);
      return arcs.keys.add('space', false, this.previewSelected, this);
    };

    Actions.prototype.events = {
      'dblclick img': 'openResult',
      'click #open-btn': 'openSelected',
      'click #open-colview-btn': 'collectionFromSelected',
      'click #collection-btn': 'namedCollectionFromSelected',
      'click #attribute-btn': 'editSelected',
      'click #flag-btn': 'flagSelected',
      'click #delete-btn': 'deleteSelected',
      'click #bookmark-btn': 'bookmarkSelected',
      'click #keyword-btn': 'keywordSelected',
      'click #download-btn': 'downloadSelected',
      'click #zipped-btn': 'zippedDownloadSelected',
      'click #rethumb-btn': 'rethumbSelected',
      'click #split-btn': 'splitSelected'
    };

    Actions.prototype.openResult = function(result) {
      arcs.log(result);
      result = this._modelFromRef(result);
      return window.open(arcs.baseURL + 'resource/' + result.id);
    };

    Actions.prototype.keywordResult = function(result, string) {
      var keyword;
      result.set('keywords', result.get('keywords').concat(string));
      keyword = new arcs.models.Keyword({
        resource_id: result.id,
        keyword: string
      });
      return keyword.save();
    };

    Actions.prototype.flagResult = function(result, reason, explanation) {
      var flag;
      flag = new arcs.models.Flag({
        resource_id: result.id,
        reason: reason,
        explanation: explanation
      });
      return flag.save();
    };

    Actions.prototype.editResult = function(result, metadata) {
      result.set('metadata', _.extend(result.get('metadata'), metadata));
      return $.ajax({
        url: arcs.baseURL + 'resources/metadata/' + result.id,
        type: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(metadata)
      });
    };

    Actions.prototype.bookmarkResult = function(result, note) {
      var bkmk;
      bkmk = new arcs.models.Bookmark({
        resource_id: result.id,
        description: note
      });
      return bkmk.save();
    };

    Actions.prototype.deleteSelected = function() {
      var n,
        _this = this;
      if (!this.results.anySelected()) return;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Delete Selected',
        subtitle: ("" + n + " " + (arcs.inflector.pluralize('resource', n)) + " will be ") + "permanently deleted.",
        buttons: {
          "delete": {
            "class": 'btn btn-danger',
            callback: function() {
              var result, _i, _len, _ref;
              _ref = _this.results.selected();
              for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                result = _ref[_i];
                result.destroy();
              }
              return _this._notify('deleted', n);
            }
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.keywordSelected = function() {
      var n,
        _this = this;
      if (!this.results.anySelected()) return;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Keyword Selected',
        subtitle: ("The keyword will be applied to " + n + " ") + ("" + (arcs.inflector.pluralize('resource', n)) + "."),
        backdrop: true,
        inputs: {
          keyword: {
            label: false,
            complete: arcs.utils.complete.keyword,
            focused: true,
            required: true
          }
        },
        buttons: {
          save: {
            "class": 'btn btn-success',
            validate: true,
            callback: function(vals) {
              var result, _i, _len, _ref;
              _ref = _this.results.selected();
              for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                result = _ref[_i];
                _this.keywordResult(result, vals.keyword);
              }
              return _this._notify('keyworded');
            }
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.rethumbSelected = function() {
      var result, _i, _len, _ref, _results;
      _ref = this.results.selected();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        _results.push($.post(arcs.baseURL + 'resources/rethumb/' + result.id, function() {
          return arcs.notify('Resource successfully queued for re-thumbnail.');
        }));
      }
      return _results;
    };

    Actions.prototype.splitSelected = function() {
      var result, _i, _len, _ref, _results;
      _ref = this.results.selected();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        _results.push($.post(arcs.baseURL + 'resources/split_pdf/' + result.id, function() {
          return arcs.notify('Resource successfully queued for split.');
        }));
      }
      return _results;
    };

    Actions.prototype.flagSelected = function() {
      var n,
        _this = this;
      if (!this.results.anySelected()) return;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Flag Selected',
        subtitle: "" + n + " " + (arcs.inflector.pluralize('resource', n)) + " will be flagged.",
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
            "class": 'btn btn-success',
            callback: function(vals) {
              var result, _i, _len, _ref;
              _ref = _this.results.selected();
              for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                result = _ref[_i];
                _this.flagResult(result, vals.reason, vals.explain);
              }
              return _this._notify('flagged');
            }
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.editSelected = function() {
      var field, fields, inputs, metadata, result, _i, _len, _ref,
        _this = this;
      if (!this.results.anySelected()) return;
      if (this.results.numSelected() > 1) return this.batchEditSelected();
      result = this.results.selected()[0];
      inputs = {};
      metadata = result.get('metadata');
      fields = result.MODIFIABLE.sort();
      for (_i = 0, _len = fields.length; _i < _len; _i++) {
        field = fields[_i];
        inputs[field] = {
          value: (_ref = metadata[field]) != null ? _ref : ''
        };
      }
      return new arcs.views.Modal({
        title: 'Edit Info',
        subtitle: '',
        template: 'ui/modal_columned',
        inputs: inputs,
        buttons: {
          save: {
            "class": 'btn btn-success',
            callback: function(values) {
              if (_.isEqual(metadata, values)) return;
              return _this.editResult(result, values);
            }
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.batchEditSelected = function() {
      var batchFields, checked, field, inputs, results, value, values, _i, _len, _original, _ref,
        _this = this;
      inputs = {};
      results = this.results.selected();
      _original = {};
      batchFields = _.difference(results[0].MODIFIABLE, results[0].SINGULAR).sort();
      for (_i = 0, _len = batchFields.length; _i < _len; _i++) {
        field = batchFields[_i];
        values = _.map(results, function(r) {
          return r.get('metadata')[field];
        });
        _ref = [false, ''], checked = _ref[0], value = _ref[1];
        if (_.unique(values).length === 1 && values[0]) {
          checked = true;
          value = values[0];
        }
        inputs[field] = {
          checkbox: checked,
          value: value != null ? value : ''
        };
        _original[field] = value != null ? value : '';
      }
      return new arcs.views.BatchEditModal({
        title: 'Edit Info (Multiple)',
        subtitle: "The values of checked fields will be applied to all " + "of the selected results, even when blank.",
        template: 'ui/modal_columned',
        inputs: inputs,
        buttons: {
          save: {
            "class": 'btn btn-success',
            callback: function(metadata) {
              var changed, k, r, v, _j, _len2, _ref2, _results;
              changed = false;
              for (k in metadata) {
                v = metadata[k];
                if (_original[k] !== v) changed = true;
              }
              if (!changed) return;
              _ref2 = _this.results.selected();
              _results = [];
              for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
                r = _ref2[_j];
                _results.push(_this.editResult(r, metadata));
              }
              return _results;
            }
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.namedCollectionFromSelected = function() {
      var n;
      if (!this.results.anySelected()) return;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Create a Collection',
        subtitle: ("A collection with " + n + " " + (arcs.inflector.pluralize('resource', n)) + " ") + "will be created.",
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
            "class": 'btn btn-success',
            callback: this.collectionFromSelected,
            context: this
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.collectionFromSelected = function(vals) {
      var collection, _ref, _ref2,
        _this = this;
      if (!this.results.anySelected()) return;
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

    Actions.prototype.previewSelected = function() {
      if (!this.results.anySelected()) return;
      if ((this.preview != null) && $('#modal').is(':visible')) {
        this.preview.remove();
        return this.preview = null;
      }
      return this.preview = new arcs.views.Preview({
        collection: new arcs.collections.ResultSet(this.results.selected())
      });
    };

    Actions.prototype.downloadSelected = function() {
      var iframe, result, _i, _len, _ref, _results;
      _ref = this.results.selected();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        iframe = this.make('iframe', {
          style: 'display:none',
          id: "downloader-for-" + result.id
        });
        $('body').append(iframe);
        _results.push(iframe.src = arcs.baseURL + 'resources/download/' + result.id);
      }
      return _results;
    };

    Actions.prototype.zippedDownloadSelected = function() {
      var data,
        _this = this;
      if (!(this.results.numSelected() > 1)) {
        return arcs.notify('To download resources zipped, select at least 2.');
      }
      data = {
        resources: _.pluck(this.results.selected(), 'id')
      };
      return $.ajax({
        url: arcs.baseURL + 'resources/zipped',
        type: 'POST',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(data),
        success: function(data) {
          var iframe;
          if (data.url != null) {
            arcs.log(data);
            iframe = _this.make('iframe', {
              style: 'display:none'
            });
            $('body').append(iframe);
            return iframe.src = data.url;
          }
        }
      });
    };

    Actions.prototype.bookmarkSelected = function() {
      var result, _i, _len, _ref;
      _ref = this.results.selected();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        this.bookmarkResult(result);
      }
      if (this.results.anySelected()) return this._notify('bookmarked');
    };

    Actions.prototype.openSelected = function() {
      var result, _i, _len, _ref;
      _ref = this.results.selected();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        this.openResult(result);
      }
      if (this.results.anySelected()) return this._notify('opened');
    };

    Actions.prototype._notify = function(verb, n) {
      var msg;
      if (verb == null) verb = 'affected';
      if (n == null) n = this.results.numSelected();
      msg = ("" + n + " " + (arcs.inflector.pluralize('resource', n)) + " ") + ("" + (arcs.inflector.conjugate('was', n)) + " " + verb + ".");
      return arcs.notify(msg, 'success');
    };

    Actions.prototype._modelFromRef = function(ref) {
      var id;
      if (ref instanceof arcs.models.Resource) return ref;
      if (ref instanceof jQuery.Event) {
        ref.preventDefault();
        ref = $(ref.currentTarget).parents('.result');
        arcs.log(ref);
      }
      id = $(ref).data('id');
      return this.results.get(id);
    };

    Actions.prototype._getContextOptions = function() {
      var admin, public, restricted;
      public = {
        'Open': 'openSelected',
        'Preview': 'previewSelected',
        'Download': 'downloadSelected'
      };
      restricted = {
        'Info': 'editSelected',
        'Flag': 'flagSelected'
      };
      admin = {
        'Delete': 'deleteSelected'
      };
      if (arcs.user.isAdmin()) return _.extend(public, restricted, admin);
      if (arcs.user.isLoggedIn()) return _.extend(public, restricted);
      return public;
    };

    return Actions;

  })(Backbone.View);

}).call(this);
