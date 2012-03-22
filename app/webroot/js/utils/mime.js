(function() {
  var __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  arcs.utils.mime = {
    imageTypes: {
      'image/png': 'png',
      'image/jpeg': 'jpeg',
      'image/jpg': 'jpg',
      'image/gif': 'gif',
      'image/tiff': 'tiff'
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
      return _.extend(this.videoTypes, this.documentTypes, this.imageTypes);
    },
    getInfo: function(mime) {
      var obj, result, type, types, undef;
      undef = {
        type: 'undefined',
        ext: null
      };
      types = {
        image: this.imageTypes,
        document: this.documentTypes,
        video: this.videoTypes
      };
      for (type in types) {
        obj = types[type];
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

  _.bindAll(arcs.utils.mime);

}).call(this);
