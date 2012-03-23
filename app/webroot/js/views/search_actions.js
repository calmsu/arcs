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
      this.ctxMenu = new arcs.views.ContextMenu({
        el: $(document),
        filter: 'img',
        options: {
          'Open': 'openSelected',
          'Info': 'editSelected',
          'Flag': 'flagSelected',
          'Preview': 'previewSelected',
          'Download': 'downloadSelected'
        },
        onShow: function(e) {
          $(e.currentTarget).parents('.result').addClass('selected');
          return arcs.trigger('arcs:selection');
        },
        context: this
      });
      arcs.keys.add('o', true, this.openSelected, this);
      return arcs.keys.add('space', false, this.previewSelected, this);
    };

    SearchActions.prototype.events = {
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
      'click #rethumb-btn': 'rethumbSelected'
    };

    SearchActions.prototype.openResult = function(result) {
      result = this._modelFromRef(result);
      return window.open(arcs.baseURL + 'resource/' + result.id);
    };

    SearchActions.prototype.keywordResult = function(result, string) {
      var keyword;
      keyword = new arcs.models.Keyword({
        resource_id: result.id,
        keyword: string
      });
      return keyword.save();
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

    SearchActions.prototype.deleteSelected = function() {
      var n,
        _this = this;
      if (!this.results.anySelected()) return;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Delete Selected',
        subtitle: ("" + n + " " + (arcs.pluralize('resource', n)) + " will be ") + "permanently deleted.",
        buttons: {
          "delete": {
            "class": 'btn danger',
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

    SearchActions.prototype.keywordSelected = function() {
      var n,
        _this = this;
      if (!this.results.anySelected()) return;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Keyword Selected',
        subtitle: ("The keyword will be applied to " + n + " ") + ("" + (arcs.pluralize('resource', n)) + "."),
        backdrop: true,
        inputs: {
          keyword: {
            label: false,
            complete: arcs.utils.complete.keyword,
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
                _this.keywordResult(result, vals.keyword);
              }
              return _this._notify('keyworded');
            }
          },
          cancel: function() {}
        }
      });
    };

    SearchActions.prototype.rethumbSelected = function() {
      var result, _i, _len, _ref, _results;
      if (!this.results.anySelected()) return;
      _ref = this.results.selected();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        _results.push($.get(arcs.baseURL + 'resources/rethumb/' + result.id));
      }
      return _results;
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
                _this.flagResult(result, vals.reason, vals.explain);
              }
              return _this._notify('flagged');
            }
          },
          cancel: function() {}
        }
      });
    };

    SearchActions.prototype.editSelected = function() {
      var field, inputs, result, _i, _len, _ref, _ref2;
      if (!this.results.anySelected()) return;
      if (this.results.numSelected() > 1) return this.batchEditSelected();
      result = this.results.selected()[0];
      inputs = {};
      _ref = result.MODIFIABLE;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        field = _ref[_i];
        inputs[field] = {
          value: (_ref2 = result.get(field)) != null ? _ref2 : ''
        };
      }
      return new arcs.views.Modal({
        title: 'Edit Attributes',
        subtitle: '',
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
      if (!this.results.anySelected()) return;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Create a Collection',
        subtitle: ("A collection with " + n + " " + (arcs.pluralize('resource', n)) + " ") + "will be created.",
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

    SearchActions.prototype.previewSelected = function() {
      if (!this.results.anySelected()) return;
      if ((this.preview != null) && $('#modal').is(':visible')) {
        this.preview.remove();
        return this.preview = null;
      }
      return this.preview = new arcs.views.Preview({
        collection: new arcs.collections.ResultSet(this.results.selected())
      });
    };

    SearchActions.prototype.downloadSelected = function() {
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

    SearchActions.prototype.zippedDownloadSelected = function() {
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
      var result, _i, _len, _ref;
      _ref = this.results.selected();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        this.openResult(result);
      }
      return this._notify('opened');
    };

    SearchActions.prototype._notify = function(verb, n) {
      var msg;
      if (verb == null) verb = 'affected';
      if (n == null) n = this.results.numSelected();
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
