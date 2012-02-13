var __hasProp = Object.prototype.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

arcs.views.Toolbar = (function(_super) {

  __extends(Toolbar, _super);

  function Toolbar() {
    Toolbar.__super__.constructor.apply(this, arguments);
  }

  Toolbar.prototype.initialize = function() {
    var _this = this;
    arcs.bind('resourceChange', function() {
      return _this.buttonCheck();
    });
    return this.addButton({
      id: 'full-res',
      text: 'Full Resolution',
      "class": 'image'
    });
  };

  Toolbar.prototype.events = {
    'click .btn#full-res': 'openFullScreen',
    'click .btn#permalink': 'clipboardPermalink',
    'click .btn#split-pdf': 'splitPDF',
    'keyup #search': 'searchCheck'
  };

  Toolbar.prototype.openFullScreen = function() {
    return arcs.resourceView.openFullScreen();
  };

  Toolbar.prototype.searchCheck = function(e) {};

  Toolbar.prototype.clipboardPermalink = function() {};

  Toolbar.prototype.splitPDF = function() {};

  Toolbar.prototype.addButton = function(options) {
    return this.el.find('#nav-container').append(Mustache.render(arcs.templates.button, options));
  };

  Toolbar.prototype.hasButton = function(id) {
    return this.el.find('#nav-container').children("#" + id).length > 0;
  };

  Toolbar.prototype.removeButton = function(id) {
    return this.el.find('#nav-container').children("#" + id).remove();
  };

  Toolbar.prototype.buttonCheck = function() {
    if (arcs.utils.mime.getInfo(arcs.resource.get('mime_type')).ext === 'pdf') {
      return this.addButton({
        id: 'split-pdf',
        text: 'Split PDF',
        "class": 'image'
      });
    } else {
      return this.removeButton('split-pdf');
    }
  };

  return Toolbar;

})(Backbone.View);
