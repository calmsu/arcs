
arcs.focusHelper = function($input) {
  var original;
  original = $input.val();
  $input.live('focus', function() {
    if ($input.val() === original) {
      $input.val('');
      return $input.removeClass('unfocused');
    }
  });
  return $input.live('blur', function() {
    if ($input.val() === '') {
      $input.val(original);
      return $input.addClass('unfocused');
    }
  });
};
