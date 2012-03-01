# converters.coffee
# -----------------

# Convert a file size given in bytes to a more human-readable format.
arcs.utils.convertBytes = (bytes) ->
    unless _.isNumber bytes
        return 'unknown size'
    sizes = ['B', 'KB', 'MB', 'GB', 'TB']
    until bytes < 1024
        arcs.log bytes
        bytes /= 1024
        sizes.shift()
    Math.round(bytes) + sizes.shift()
