var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

arcs.utils.mime = {
  imageTypes: {
    'image/png': 'png',
    'image/jpeg': 'jpeg',
    'image/jpg': 'jpg',
    'image/gif': 'gif'
  },
  documentTypes: {
    'application/pdf': 'pdf',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'docx',
    'application/msword': 'doc',
    'text/plain': 'plaintext',
    'text/richtext': 'richtext',
    'text/rtf': 'rtf'
  },
  videoTypes: {
    'video/mpeg': 'mpeg',
    'video/msvideo': 'avi',
    'video/quicktime': 'mov'
  },
  types: function() {
    var that, types;
    that = arcs.utils.mime;
    types = _.keys(that.imageTypes).concat(_.keys(that.documentTypes).concat);
    return types.concat(_.keys(that.videoTypes));
  },
  getInfo: function(mime) {
    var result, type, types, undef, _i, _len, _ref;
    undef = {
      type: 'undefined',
      ext: null
    };
    types = {
      image: this.imageTypes,
      document: this.documentTypes,
      video: this.videoTypes
    };
    _ref = _.keys(types);
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      type = _ref[_i];
      if (__indexOf.call(_.keys(types[type]), mime) >= 0) {
        result = {
          type: type,
          ext: types[type][mime]
        };
      }
    }
    return result || undef;
  }
};
