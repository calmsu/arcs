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

  # Returns true if all elements of the array are identical.
  twins: (array) ->
    _.uniq(array).length == 1

  # Take elements surrounding and including a given index, up to the given
  # length. This is useful for pagination.
  surrounding: (array, index, len) ->
    before = _.first(array, index)
    after = _.rest(array, index + 1)
    result = [array[index]]
    alt = true
    while len > result.length and (before.length or after.length)
      if alt and before.length
        result.unshift(before.pop())
      else if after.length
        result.push(after.shift())
      alt = !alt
    result
