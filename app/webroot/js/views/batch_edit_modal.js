(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.BatchEditModal = (function(_super) {

    __extends(BatchEditModal, _super);

    function BatchEditModal() {
      BatchEditModal.__super__.constructor.apply(this, arguments);
    }

    BatchEditModal.prototype.initialize = function() {
      BatchEditModal.__super__.initialize.call(this);
      return this.$('input[type=text][id^=modal]').keydown(function() {
        var ckbox, id, name, _ref;
        _ref = $(this).attr('id').match(/modal-([\w-]+)-input/), id = _ref[0], name = _ref[1];
        ckbox = $("input#modal-" + name + "-checkbox");
        if (!$(this).val()) return ckbox.prop('checked', false);
        return ckbox.prop('checked', true);
      });
    };

    BatchEditModal.prototype.getValues = function() {
      var name, values;
      values = {};
      for (name in this.options.inputs) {
        if (this.$("input#modal-" + name + "-checkbox").is(':checked')) {
          values[name] = this.$("#modal-" + name + "-input").val();
        }
      }
      return values;
    };

    return BatchEditModal;

  })(arcs.views.Modal);

}).call(this);
