# location.coffee
# ---------------
# Utilities for manipulating and parsing the location object.

# Set, get, and track the location hash
arcs.utils.hash = 

    # Set the hash, record the change.
    #
    # val - string value to set the hash with.
    # uri - URI encode val before setting.
    set: (val, uri=false) ->
        @history.push document.location.hash
        if uri
            val = encodeURIComponent val
        document.location.hash = val

    # Get the hash, minus the actual hash.
    #
    # uri - URI decode the hash before returning.
    get: (uri=false) ->
        hash = document.location.hash[1..]
        if uri
            return decodeURIComponent hash
        return hash

    # Set the hash to the most recent history item.
    rewind: ->
        @set @history.pop()

    # Store the previous (n-1) hash values.
    history: []


# Get url paramaters by name.
arcs.utils.params = 
    # Pull apart location.search and look for a the value of the given name.
    get: (name) ->
        params = document.location.search[1..].split '&' 
        for p in params
            pair = p.split '='
            if pair[0] == name
                return decodeURIComponent unescape(pair[1])
