# dev.coffee
# ----------
# Development mode settings

# Set the baseURL so that Backbone.sync works with our port 8080 dev installs.
# This part is on, for now, in production mode to test the built assets.
if document.location.href.match /:8080\/~[a-z0-9]+\//
  arcs.baseURL += document.location.href.match /~[a-z0-9]+\//
  arcs.baseURL += 'arcs/'

if arcs.mode > 0
  arcs.dev = {}

  # Automatically reload stylesheets?
  arcs.dev.reload = false  
  # Reload them every n msecs
  arcs.dev.reloadAt = 2000

  # Inject some logging into Backbone.sync
  _sync = Backbone.sync
  Backbone.sync = (method, model, options) ->
    arcs.log 'Backbone.sync:', method, model, options
    _sync(method, model, options)

  # Stylesheet reloader
  arcs.dev.reloadStylesheets = ->
    query = '?reload=' + new Date().getTime()
    $('link[rel="stylesheet"]').each ->
      @href = @href.replace /\?.*|$/, query

  # Run the stylesheet reloader on an interval, if requested.
  if arcs.dev.reload 
    setInterval arcs.dev.reloadStylesheets, arcs.dev.reloadAt
