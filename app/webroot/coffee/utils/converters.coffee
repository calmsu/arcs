# converters.coffee
# -----------------

# Convert a file size given in bytes to a more human-readable format.
arcs.utils.convertBytes = (bytes) ->
  bytes = parseInt(bytes, 10)
  return 'unknown size' unless isFinite(bytes)
  sizes = ['B', 'KB', 'MB', 'GB', 'TB']
  until bytes < 1024
    bytes /= 1024
    sizes.shift()
  Math.round(bytes) + sizes.shift()
