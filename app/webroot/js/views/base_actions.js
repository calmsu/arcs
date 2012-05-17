(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; },
    __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  arcs.views.BaseActions = (function(_super) {

    __extends(BaseActions, _super);

    function BaseActions() {
      BaseActions.__super__.constructor.apply(this, arguments);
    }

    BaseActions.prototype.openResource = function(resource) {
      if (this._modelFromRef != null) resource = this._modelFromRef(resource);
      return window.open(arcs.baseURL + 'resource/' + resource.id);
    };

    BaseActions.prototype.keywordResource = function(resource, string) {
      var keyword;
      resource.set('keywords', resource.get('keywords').concat(string));
      keyword = new arcs.models.Keyword({
        resource_id: resource.id,
        keyword: string
      });
      return keyword.save();
    };

    BaseActions.prototype.flagResource = function(resource, reason, explanation) {
      var flag;
      flag = new arcs.models.Flag({
        resource_id: resource.id,
        reason: reason,
        explanation: explanation
      });
      return flag.save();
    };

    BaseActions.prototype.editResource = function(resource, attributes) {
      var key, metadata, value, _metadata, _resource;
      metadata = resource.get('metadata');
      _resource = _.clone(resource.attributes);
      _metadata = _.clone(metadata.attributes);
      for (key in attributes) {
        value = attributes[key];
        if (__indexOf.call(_.keys(resource.attributes), key) >= 0) {
          resource.set(key, value);
        } else if (metadata.get(key) || value) {
          metadata.set(key, value);
        }
      }
      if (!_.isEqual(resource.attributes, _resource)) resource.save();
      if (!_.isEqual(metadata.attributes, _metadata)) {
        metadata.save();
        return resource.trigger('change');
      }
    };

    BaseActions.prototype.bookmarkResource = function(resource, note) {
      var bkmk;
      bkmk = new arcs.models.Bookmark({
        resource_id: resource.id,
        description: note
      });
      return bkmk.save();
    };

    BaseActions.prototype.downloadResource = function(resource) {
      var iframe;
      iframe = this.make('iframe', {
        style: 'display: none',
        id: "downloader-for-" + resource.id
      });
      $('body').append(iframe);
      return iframe.src = arcs.baseURL + 'resources/download/' + resource.id;
    };

    BaseActions.prototype.splitResource = function(resource) {
      if (resource.get('mime_type') !== 'application/pdf') return;
      return $.post(arcs.baseURL + 'resources/split_pdf/' + resource.id, function() {
        return arcs.notify('Resource successfully queued for split.');
      });
    };

    BaseActions.prototype.rethumbResource = function(resource) {
      return $.post(arcs.baseURL + 'resources/rethumb/' + resource.id, function() {
        return arcs.notify('Resource successfully queued for re-thumbnail.');
      });
    };

    BaseActions.prototype.repreviewResource = function(resource) {
      return $.post(arcs.baseURL + 'resources/repreview/' + resource.id, function() {
        return arcs.notify('Resource successfully queued for re-preview.');
      });
    };

    BaseActions.prototype.indexResource = function(resource) {
      return $.post(arcs.baseURL + 'resources/solr/' + resource.id, function() {
        return arcs.notify('Resource successfully queued for SOLR index.');
      });
    };

    return BaseActions;

  })(Backbone.View);

}).call(this);
