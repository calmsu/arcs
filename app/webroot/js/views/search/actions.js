(function() {
  var _base,
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; },
    __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

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
          return arcs.bus.trigger('selection');
        },
        context: this
      });
      return arcs.keys.map(this, {
        'ctrl+o': this.openSelected,
        'ctrl+e': arcs.user.isLoggedIn() ? this.editSelected : function() {},
        space: this.previewSelected
      });
    };

    Actions.prototype.events = {
      'dblclick img': 'openResource',
      'click #open-btn': 'openSelected',
      'click #open-colview-btn': 'collectionFromSelected',
      'click #collection-btn': 'namedCollectionFromSelected',
      'click #collection-add-btn': 'addToCollection',
      'click #attribute-btn': 'editSelected',
      'click #flag-btn': 'flagSelected',
      'click #delete-btn': 'deleteSelected',
      'click #bookmark-btn': 'bookmarkSelected',
      'click #keyword-btn': 'keywordSelected',
      'click #download-btn': 'downloadSelected',
      'click #zipped-btn': 'zippedDownloadSelected',
      'click #rethumb-btn': 'rethumbSelected',
      'click #repreview-btn': 'repreviewSelected',
      'click #split-btn': 'splitSelected',
      'click #access-btn': 'setAccessForSelected',
      'click #solr-btn': 'indexSelected'
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
            complete: arcs.complete('keywords/complete'),
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
                _this.keywordResource(result, vals.keyword);
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
        _results.push(this.rethumbResource(result));
      }
      return _results;
    };

    Actions.prototype.splitSelected = function() {
      var result, _i, _len, _ref, _results;
      _ref = this.results.selected();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        _results.push(this.splitResource(result));
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
            options: _.inverse(arcs.config.flags)
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
                _this.flagResource(result, vals.reason, vals.explain);
              }
              return _this._notify('flagged');
            }
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.editSelected = function() {
      var field, fields, help, inputs, metadata, result, _ref,
        _this = this;
      if (!this.results.anySelected()) return;
      if (this.results.numSelected() > 1) return this.batchEditSelected();
      result = this.results.selected()[0];
      inputs = {
        title: {
          value: result.get('title')
        },
        type: {
          type: 'select',
          options: _.keys(arcs.config.types),
          value: result.get('type')
        }
      };
      metadata = result.get('metadata');
      fields = arcs.config.metadata;
      for (field in fields) {
        help = fields[field];
        inputs[field] = {
          value: (_ref = metadata.get(field)) != null ? _ref : '',
          help: help
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
              return _this.editResource(result, values);
            }
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.batchEditSelected = function() {
      var checked, field, help, inputs, results, types, value, values, _ref,
        _this = this;
      results = this.results.selected();
      types = _.map(results, function(r) {
        return r.get('type');
      });
      inputs = {
        type: {
          type: 'select',
          options: _.keys(arcs.config.types),
          value: _.twins(types) ? results[0].get('type') : '',
          checkbox: _.twins(types) && results[0].get('type')
        }
      };
      _ref = arcs.config.metadata;
      for (field in _ref) {
        help = _ref[field];
        if (__indexOf.call(arcs.config.metadataSingular, field) >= 0) continue;
        values = _.map(results, function(r) {
          return r.get('metadata').get(field);
        });
        checked = _.twins(values) && values[0];
        value = _.twins(values) && values[0] ? values[0] : void 0;
        inputs[field] = {
          checkbox: !!checked,
          value: value != null ? value : '',
          help: help
        };
      }
      return new arcs.views.BatchEditModal({
        title: 'Edit Info (Multiple)',
        subtitle: "The values of checked fields will be applied to all " + "of the selected results, even when blank.",
        template: 'ui/modal_columned',
        inputs: inputs,
        buttons: {
          save: {
            "class": 'btn btn-success',
            callback: function(values) {
              var r, _i, _len, _ref2, _results;
              _ref2 = _this.results.selected();
              _results = [];
              for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
                r = _ref2[_i];
                _results.push(_this.editResource(r, values));
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
          return window.open(arcs.url('collection', newCol.id));
        },
        error: function() {
          return arcs.notify('An error occurred.', 'error');
        }
      });
    };

    Actions.prototype.addToCollection = function() {
      var n,
        _this = this;
      n = this.results.numSelected();
      return new arcs.views.Modal({
        title: 'Add to existing collection',
        subtitle: "" + n + " " + (arcs.inflector.pluralize('resource', n)) + " will be added        to the selected collection.",
        inputs: {
          collection: {
            type: 'select',
            options: this.getCollections()
          }
        },
        buttons: {
          add: {
            "class": 'btn btn-success',
            callback: function(vals) {
              var data, url;
              url = arcs.url('collections/append/', vals.collection);
              data = {
                members: _.map(_this.results.selected(), function(r) {
                  return r.get('id');
                })
              };
              return $.postJSON(url, data, function() {
                return _this._notify('added');
              });
            }
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.getCollections = function() {
      var result;
      result = [];
      $.ajax({
        url: arcs.baseURL + 'collections/titles',
        async: false,
        dataType: 'json',
        success: function(data) {
          return result = data;
        }
      });
      return _.inverse(result);
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
      var result, _i, _len, _ref, _results;
      _ref = this.results.selected();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        _results.push(this.downloadResource(result));
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
      $.postJSON(arcs.baseURL + 'resources/zipped', data, function(response) {
        var iframe;
        if (response.url != null) {
          iframe = _this.make('iframe', {
            style: 'display:none'
          });
          $('body').append(iframe);
          return iframe.src = response.url;
        }
      });
      return arcs.notify("Hold tight. We're building your zipfile. " + "Your download will start in a moment", 'success');
    };

    Actions.prototype.bookmarkSelected = function() {
      var result, _i, _len, _ref;
      _ref = this.results.selected();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        this.bookmarkResource(result);
      }
      if (this.results.anySelected()) return this._notify('bookmarked');
    };

    Actions.prototype.openSelected = function() {
      var result, _i, _len, _ref;
      _ref = this.results.selected();
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        this.openResource(result);
      }
      if (this.results.anySelected()) return this._notify('opened');
    };

    Actions.prototype.setAccessForSelected = function() {
      var n, settings, value,
        _this = this;
      n = this.results.numSelected();
      settings = _.map(this.results.selected(), function(r) {
        return r.get('public');
      });
      if (!_.twins(settings)) {
        value = '';
      } else {
        value = settings[0] ? 'Public' : 'Private';
      }
      return new arcs.views.Modal({
        title: 'Set Access',
        subtitle: "<b>Private</b> resources may only be viewed by ARCS users. " + "<b>Public</b> resources may be viewed by the general public.",
        inputs: {
          access: {
            type: 'select',
            options: ['', 'Public', 'Private'],
            value: value
          }
        },
        buttons: {
          save: {
            "class": 'btn btn-success',
            callback: function(vals) {
              var result, _i, _len, _ref, _results;
              if (!vals.access) return;
              _ref = _this.results.selected();
              _results = [];
              for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                result = _ref[_i];
                result.set('public', vals.access === 'Public');
                _results.push(result.save());
              }
              return _results;
            }
          },
          cancel: function() {}
        }
      });
    };

    Actions.prototype.indexSelected = function() {
      var result, _i, _len, _ref, _results;
      _ref = this.results.selected();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        _results.push(this.indexResource(result));
      }
      return _results;
    };

    Actions.prototype.repreviewSelected = function() {
      var result, _i, _len, _ref, _results;
      _ref = this.results.selected();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        result = _ref[_i];
        _results.push(this.repreviewResource(result));
      }
      return _results;
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
        'Edit...': 'editSelected',
        'Flag...': 'flagSelected'
      };
      admin = {
        'Delete...': 'deleteSelected'
      };
      if (arcs.user.isAdmin()) return _.extend(public, restricted, admin);
      if (arcs.user.isLoggedIn()) return _.extend(public, restricted);
      return public;
    };

    return Actions;

  })(arcs.views.BaseActions);

}).call(this);
