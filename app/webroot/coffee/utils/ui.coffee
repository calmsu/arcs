# ui.coffee
# ---------
# Miscellaneous UI logic (e.g. placeholder support for older browsers).

$('[placeholder]').live 'focus', ->
  $el = $(@)
  if $el.val() == $el.attr('placeholder')
    $el.val ''
    $el.removeClass 'unfocused'

$('[placeholder]').live 'blur', ->
  $el = $(@)
  if $el.val() == ''
    $el.val $el.attr('placeholder')
    $el.addClass 'unfocused'

$('[rel=tooltip]').tooltip
  placement: 'bottom'
