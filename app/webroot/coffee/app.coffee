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

# Development mode?
arcs.mode = CAKE_DEBUG
# Debug will be on by default in dev mode. Set the var from the 
# console to turn it off.
arcs.debug = arcs.mode > 0
arcs.version = "0.9.0"
arcs.baseURL = '/'

# Logs messages with an ARCS prefix if debug is on and a console is 
# available (there's no console in older IE's).
arcs.log = (msg...) ->
    if arcs.debug and console?.log?
        console.log '[ARCS]:', msg...

# We'll bind app-wide events to the arcs object.
_.extend(arcs, Backbone.Events)
