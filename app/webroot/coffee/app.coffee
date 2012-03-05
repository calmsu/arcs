# app.coffee
# ----------
# Namespace ARCS and basic setup.

# Attach arcs to the window object.
window.arcs = {}
arcs.views = {}
arcs.models = {}
arcs.collections = {}
arcs.utils = {}
arcs.routers = {}
arcs.templates = {}

# Development mode?
arcs.mode = CAKE_DEBUG

# Debug will be on by default in dev mode. Set the var from the console 
# to turn it off.
arcs.debug = arcs.mode > 0
arcs.version = "0.9.1"

# Base URL holds any prefix needed to link to pages relatively.
arcs.baseURL = '/'

# Logs messages with an ARCS prefix if debug is on and a console is 
# available (there's no console in older IE's).
#
#  msg -  variable number of object arguments to log.
arcs.log = (msg...) ->
  if arcs.debug and console?.log?
    console.log '[ARCS]:', msg...

# Convenience method for rendering templates.
# This should be the only place that a vendor templating function is 
# called (so they're easy to swap).
#
#  key  -  prop of window.JST or a template string
#  data -  object to interpolate. When missing, {} will be used
#  func -  template interpolation function. Defaults to Mustache.render
arcs.tmpl = (key, data, func) ->
  func ?= Mustache.render
  tmpl = if _.has(JST, key) then JST[key] else key
  func tmpl, (data ? {})

# We'll bind app-wide events to the arcs object.
_.extend arcs, Backbone.Events
