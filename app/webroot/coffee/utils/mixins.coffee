_.mixin
  isNumeric: (val) ->
    !isNaN(parseFloat(val)) and isFinite(val)
