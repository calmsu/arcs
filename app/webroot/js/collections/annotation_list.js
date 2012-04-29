(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.collections.AnnotationList = (function(_super) {

    __extends(AnnotationList, _super);

    function AnnotationList() {
      AnnotationList.__super__.constructor.apply(this, arguments);
    }

    AnnotationList.prototype.model = arcs.models.Annotation;

    AnnotationList.prototype.url = function() {
      return arcs.baseURL + "resources/annotations/" + arcs.resource.id;
    };

    AnnotationList.prototype.parse = function(r) {
      if (r.relations != null) {
        this.relations = new arcs.collections.Collection(r.relations);
      }
      return r.annotations;
    };

    return AnnotationList;

  })(Backbone.Collection);

}).call(this);
