(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.ViewerActions = (function(_super) {

    __extends(ViewerActions, _super);

    function ViewerActions() {
      ViewerActions.__super__.constructor.apply(this, arguments);
    }

    ViewerActions.prototype.initialize = function() {
      var _this = this;
      this.viewer = this.options.viewer;
      arcs.bus.on('indexChange', function() {
        _this.updateNav(arguments[0]);
        return _this.checkNav();
      });
      this.onNavKeyup = _.debounce(this.setNav, 1000);
      return arcs.keys.map(this, {
        'ctrl+e': this.edit,
        p: this.onNavClick
      });
    };

    ViewerActions.prototype.events = {
      'click .page-nav input': 'onNavClick',
      'keyup .page-nav input': 'onNavKeyup',
      'keydown .page-nav input': 'onNavKeydown',
      'keydown .collection-search': 'search',
      'click #thumbs-btn': 'openInSearch',
      'click #mini-next-btn': 'next',
      'click #mini-prev-btn': 'prev',
      'click #edit-btn': 'edit',
      'click #flag-btn': 'flag',
      'click #full-screen-btn': 'fullScreen',
      'click #delete-btn': 'delete',
      'click #delete-col-btn': 'deleteCollection',
      'click #split-btn': 'split',
      'click #rethumb-btn': 'rethumb',
      'click #download-btn': 'download'
    };

    ViewerActions.prototype.onNavClick = function() {
      return this.$('.page-nav input').select();
    };

    ViewerActions.prototype.onNavKeyup = function(e) {
      if (e.which !== 13) return this.setNav();
    };

    ViewerActions.prototype.onNavKeydown = function(e) {
      if (e.which === 13) return this.setNav();
    };

    ViewerActions.prototype.setNav = function() {
      if (!this.viewer.set(this.$('.page-nav input').val() - 1, {
        trigger: true
      })) {
        if (this.$('.page-nav input').val() !== '') {
          return this.$('.page-nav input').val(this.viewer.index + 1);
        }
      }
    };

    ViewerActions.prototype.updateNav = function(index) {
      return this.$('.page-nav input').val(index + 1);
    };

    ViewerActions.prototype.checkNav = function() {
      if (this.viewer.collection.length === this.viewer.index + 1) {
        this.$('#mini-next-btn').addClass('disabled');
      } else {
        this.$('#mini-next-btn').removeClass('disabled');
      }
      if (this.viewer.index === 0) {
        return this.$('#mini-prev-btn').addClass('disabled');
      } else {
        return this.$('#mini-prev-btn').removeClass('disabled');
      }
    };

    ViewerActions.prototype.search = function(e) {
      var facets, query;
      if (e.which !== 13) return;
      query = this.$('.collection-search').val();
      facets = "collection:'" + (arcs.collectionModel.get('title')) + "' text:'" + query + "'";
      return location.href = arcs.baseURL + "search/" + encodeURIComponent(facets);
    };

    ViewerActions.prototype.openInSearch = function() {
      return location.href = arcs.baseURL + "search/" + encodeURIComponent("collection:'" + (arcs.collectionModel.get('title')) + "'");
    };

    ViewerActions.prototype.next = function() {
      return this.viewer.next();
    };

    ViewerActions.prototype.prev = function() {
      return this.viewer.prev();
    };

    ViewerActions.prototype.flag = function() {
      var _this = this;
      return new arcs.views.Modal({
        title: 'Flag',
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
              return _this.flagResource(_this.viewer.model, vals.reason, vals.explain);
            }
          },
          cancel: function() {}
        }
      });
    };

    ViewerActions.prototype.edit = function() {
      var field, fields, help, inputs, metadata, _ref,
        _this = this;
      inputs = {
        title: {
          value: this.viewer.model.get('title')
        },
        type: {
          type: 'select',
          options: _.keys(arcs.config.types),
          value: this.viewer.model.get('type')
        }
      };
      metadata = this.viewer.model.get('metadata');
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
              return _this.editResource(_this.viewer.model, values);
            }
          },
          cancel: function() {}
        }
      });
    };

    ViewerActions.prototype["delete"] = function() {
      var _this = this;
      return arcs.confirm("Are you sure you want to delete this resource?", "<b>" + (this.viewer.model.get('title')) + "</b> will be permanently deleted.", function() {
        return _this.viewer.model.destroy();
      });
    };

    ViewerActions.prototype.deleteCollection = function() {
      var _this = this;
      return arcs.confirm("Are you sure you want to delete this collection?", ("<p>Collection <b>" + (this.viewer.collectionModel.get('title')) + "</b> will be ") + "deleted. <p><b>N.B.</b> Resources within the collection will not be " + "deleted. They may still be accessed from other collections to which they " + "belong.", function() {
        arcs.loader.show();
        return $.ajax({
          url: arcs.url('collections', 'delete', _this.viewer.collectionModel.id),
          type: 'DELETE',
          success: function() {
            return location.href = arcs.baseURL;
          }
        });
      });
    };

    ViewerActions.prototype.rethumb = function() {
      return this.rethumbResource(this.viewer.model);
    };

    ViewerActions.prototype.split = function() {
      return this.splitResource(this.viewer.model);
    };

    ViewerActions.prototype.download = function() {
      return this.downloadResource(this.viewer.model);
    };

    ViewerActions.prototype.fullScreen = function() {
      var docEl;
      if (this.$('#full-screen-btn i').hasClass('icon-resize-small')) {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        }
      } else {
        docEl = document.documentElement;
        if (docEl.requestFullScreen) {
          docEl.requestFullScreen();
        } else if (docEl.mozRequestFullScreen) {
          docEl.mozRequestFullScreen();
        } else if (docEl.webkitRequestFullScreen) {
          docEl.webkitRequestFullScreen();
        } else {
          return arcs.notify("We're unable to open screen for you. You can either " + "open it manually, or install the latest version of either Google " + "Chrome or Mozilla Firefox.");
        }
      }
      return this.$('#full-screen-btn i').toggleClass('icon-resize-full').toggleClass('icon-resize-small');
    };

    return ViewerActions;

  })(arcs.views.BaseActions);

}).call(this);
