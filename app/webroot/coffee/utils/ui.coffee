# ui.coffee
# ---------
# Miscellaneous UI logic (e.g. placeholder support for older browsers).

$ ->
  $('body').delegate 'input[placeholder]', 'focus', (e) ->
    $el = $(e.currentTarget)
    if $el.val() == $el.attr('placeholder')
      $el.val ''
      $el.removeClass 'unfocused'

  $('body').delegate 'input[placeholder]', 'blur', (e) ->
    $el = $(e.currentTarget)
    if $el.val() == ''
      $el.val $el.attr('placeholder')
      $el.addClass 'unfocused'

  $('[rel=tooltip]').tooltip
    placement: 'bottom'

  $('[rel=popover]').popover()

  $('body').delegate 'input[type="text"][id*="date"]', 'focus', (e) ->
    $(e.currentTarget).datepicker
      format: 'dd/mm/yyyy'
