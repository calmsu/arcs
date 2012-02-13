# hash.coffee
# -----------
# Manipulate and track the location hash
arcs.utils.hash = 

    # Set the hash, record the change.
    set: (val) ->
        @history.push document.location.hash[1..]
        document.location.hash = val

    # Get the hash, minus the actual hash.
    get: ->
        document.location.hash[1..]

    # Set the hash to the most recent history item.
    rewind: ->
        @set @history.pop()

    # Store the previous (n-1) hash values.
    history: []
