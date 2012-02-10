# Logic to remove and replace input values on focus/blur
# Does the same thing as input[placeholder] but will work in older browsers.
arcs.focusHelper = ($input) ->
    original = $input.val()
    $input.live 'focus', ->
        if $input.val() == original
            $input.val ''
            $input.removeClass 'unfocused'
    $input.live 'blur', ->
        if $input.val() == ''
            $input.val original
            $input.addClass 'unfocused'
