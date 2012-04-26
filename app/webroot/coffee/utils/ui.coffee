# ui.coffee
# ---------
# Miscellaneous UI logic (e.g. placeholder support for older browsers).

$ ->
  $('body').on 'focus', 'input[placeholder]', (e) ->
    $el = $(e.currentTarget)
    if $el.val() == $el.attr('placeholder')
      $el.val ''
      $el.removeClass 'unfocused'

  $('body').on 'blur', 'input[placeholder]', (e) ->
    $el = $(e.currentTarget)
    if $el.val() == ''
      $el.val $el.attr('placeholder')
      $el.addClass 'unfocused'

  $('body').tooltip
    selector: '[rel=tooltip]'

  $('body').popover
    selector: '[rel=popover]'

  $('body').delegate 'input[type="text"][id*="date"]', 'focus', (e) ->
    $(e.currentTarget).datepicker
      format: 'dd/mm/yyyy'
