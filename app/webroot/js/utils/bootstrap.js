$('.alert a.close').live('click', function() {
  return $(this).parent().remove();
});