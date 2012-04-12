(function() {

  $(function() {
    $('body').delegate('input[placeholder]', 'focus', function(e) {
      var $el;
      $el = $(e.currentTarget);
      if ($el.val() === $el.attr('placeholder')) {
        $el.val('');
        return $el.removeClass('unfocused');
      }
    });
    $('body').delegate('input[placeholder]', 'blur', function(e) {
      var $el;
      $el = $(e.currentTarget);
      if ($el.val() === '') {
        $el.val($el.attr('placeholder'));
        return $el.addClass('unfocused');
      }
    });
    $('[rel=tooltip]').tooltip({
      placement: 'bottom'
    });
    $('[rel=popover]').popover();
    return $('body').delegate('input[type="text"][id*="date"]', 'focus', function(e) {
      return $(e.currentTarget).datepicker({
        format: 'dd/mm/yyyy'
      });
    });
  });

}).call(this);
