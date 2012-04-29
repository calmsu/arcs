(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Annotation = (function(_super) {

    __extends(Annotation, _super);

    function Annotation() {
      Annotation.__super__.constructor.apply(this, arguments);
    }

    Annotation.prototype.url = function() {
      if (this.isNew()) return arcs.baseURL + 'annotations';
      return arcs.baseURL + ("annotations/" + this.id);
    };

    Annotation.prototype.setScaled = function(box, height, width) {
      return this.set({
        x1: box.x1 / width,
        y1: box.y1 / height,
        x2: box.x2 / width,
        y2: box.y2 / height
      });
    };

    Annotation.prototype.scaleTo = function(height, width) {
      return {
        x1: this.get('x1') * width,
        y1: this.get('y1') * height,
        x2: this.get('x2') * width,
        y2: this.get('y2') * height
      };
    };

    Annotation.prototype.getType = function() {
      if (this.get('relation')) return 'Relation';
      if (this.get('transcript')) return 'Transcription';
      if (this.get('url')) return 'URL';
    };

    Annotation.prototype.parse = function(response) {
      if (response.Annotation != null) response = response.Annotation;
      return response;
    };

    return Annotation;

  })(Backbone.Model);

}).call(this);
