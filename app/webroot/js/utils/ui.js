
$('[placeholder]').live('focus', function() {
  var $el;
  $el = $(this);
  if ($el.val() === $el.attr('placeholder')) {
    $el.val('');
    return $el.removeClass('unfocused');
  }
});

$('[placeholder]').live('blur', function() {
  var $el;
  $el = $(this);
  if ($el.val() === '') {
    $el.val($el.attr('placeholder'));
    return $el.addClass('unfocused');
  }
});
