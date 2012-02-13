# app.coffee
# ----------
# Namespace ARCS and basic setup.

# Attach arcs to the window object.
window.arcs = {}
arcs.views = {}
arcs.models = {}
arcs.collections = {}
arcs.events = {}
arcs.utils = {}
arcs.templates = {}

# Few settings.
arcs.debug = CAKE_DEBUG > 0
arcs.version = "0.8.0"
arcs.baseURL = '/'

# Logs messages with an ARCS prefix if debug is on and a console is 
# available (there's no console in older IE's).
arcs.log = (msg...) ->
    if arcs.debug and console?.log?
        console.log '[ARCS]:', msg...

# We'll bind app-wide events to the arcs object.
_.extend(arcs, Backbone.Events)
