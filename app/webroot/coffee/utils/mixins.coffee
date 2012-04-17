_.mixin

  # Determine if a value is numeric.
  isNumeric: (val) ->
    !isNaN(parseFloat(val)) and isFinite(val)

  # Flip the keys and values of an object. Values should be strings, or readily 
  # coercible.
  inverse: (object) ->
    result = {}
    result[v] = k for k, v of object
    result
