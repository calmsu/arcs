(function() {

  if (!('open' in document.createElement('details'))) {
    document.documentElement.className += ' no-details';
  }

  $(function() {
    $('body').on('focus', 'input[placeholder]', function(e) {
      var $el;
      $el = $(e.currentTarget);
      if ($el.val() === $el.attr('placeholder')) {
        $el.val('');
        return $el.removeClass('unfocused');
      }
    });
    $('body').on('blur', 'input[placeholder]', function(e) {
      var $el;
      $el = $(e.currentTarget);
      if ($el.val() === '') {
        $el.val($el.attr('placeholder'));
        return $el.addClass('unfocused');
      }
    });
    $('body').tooltip({
      selector: '[rel=tooltip]'
    });
    $('body').popover({
      selector: '[rel=popover]'
    });
    return $('body').delegate('input[type="text"][id*="date"]', 'focus', function(e) {
      return $(e.currentTarget).datepicker({
        format: 'yyyy/mm/dd'
      });
    });
  });

}).call(this);
