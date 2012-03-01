var __hasProp = Object.prototype.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

arcs.views.Upload = (function(_super) {

  __extends(Upload, _super);

  function Upload() {
    Upload.__super__.constructor.apply(this, arguments);
  }

  Upload.prototype.initialize = function() {
    this.uploads = new arcs.collections.UploadSet;
    this.uploads.on('add remove', this.render, this);
    return this.setupFileupload();
  };

  Upload.prototype.setupFileupload = function() {
    var _this = this;
    this.fileupload = this.$el.find('#fileupload');
    this.fileupload.fileupload({
      dataType: 'json',
      dropZone: this.$el.find('#dropzone'),
      url: arcs.baseURL + 'uploads/add'
    });
    this.fileupload.bind('fileuploadadd', function(e, data) {
      arcs.log(data);
      arcs.log(data.files);
      return _this.uploads.add(data.files);
    });
    return this.fileupload.bind('fileuploadprogress', function(e, data) {
      var fname, model, progress;
      arcs.log(data);
      fname = _.first(data.files).fileName;
      progress = parseInt(data.loaded / data.total * 100, 10);
      model = _this.uploads.find(function(m) {
        return m.get('fileName') === fname;
      });
      model.set('progress', progress);
      return _this.render();
    });
  };

  Upload.prototype.render = function() {
    var $uploads, uploads;
    $uploads = this.$el.find('#uploads-container');
    uploads = this.uploads.toJSON();
    _.each(uploads, function(u) {
      return u.fileSize = arcs.utils.convertBytes(u.fileSize);
    });
    return $uploads.html(arcs.tmpl('uploads', {
      uploads: uploads
    }));
  };

  return Upload;

})(Backbone.View);
