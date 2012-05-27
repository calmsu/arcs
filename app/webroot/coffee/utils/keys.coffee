# keys.coffee
# -----------
# Keyboard shortcuts and what not.
#
# The Keys class provides an interface for mapping keys (or key combinations)
# to methods. A class instance will bind keydown events on the given element,
# which by default is the document object. When fired, the instance looks 
# for registered mappings matching the keydown. If any are found, the mapped 
# method is called and bubbling stops unless instructed otherwise.
class arcs.utils.Keys

  # Bind to the keydown event of the given DOM element object.
  #
  #   sel - DOM element selector (passed right off to $)
  constructor: (sel=document) ->
    $(sel).on 'keydown', @delegate
  
  # Inspect the event and see if any matching hotkeys are registered. If so,
  # fire the relevant callback.
  #
  #   e - a W3C-compliant event object.
  delegate: (e) =>
    # Is it a text field? We don't do those here. 
    # (borrowed from John Resig's jquery.hotkeys)
    if e.target.type == 'text' or /textarea|select/i.test e.target.nodeName
      # Let it bubble.
      return true

    # Is a modifier pressed? To keep it simple, we don't care which one.
    if e.ctrlKey or e.shiftKey or e.altKey or e.metaKey
      modifier = true
    else
      modifier = false

    # Get matching mappings
    mappings = @get e.which, modifier
    return true unless mappings.length

    # Iterate the mappings.
    # It's unlikely that there'd be more than one, but it's allowed.
    # We'll execute all of them, and afterwards bubble up if any request it.
    bubble = false
    for m in mappings
      bubble = true if m.bubble
      # Bind it to a context, or use the current one.
      if m.context
        callback = _.bind m.callback, m.context
      else
        callback = m.callback
      callback(e)

    # Bubble up or block.
    unless bubble
      e.preventDefault()
      return false
    return true

  # Register a mapping.
  #
  #   key      - single character.
  #   callback - function to call when the key (combination) is pressed.
  #   modifier - bool, one of [shift,ctl,meta,alt] must also be pressed
  #   context  - bind the callback to an object. It will default to the 
  #              DOM's document element (which the keydown is bound to).
  #   bubble   - bubble up the event. By default, we'll return false and
  #              block further action with preventDefault().
  add: (key, callback, context, bubble=false) ->
    @mappings.push
      key: key
      callback: callback
      context: context
      bubble: bubble

  map: (ctx, map) ->
    for key, cb of map
      @mappings.push
        key: key
        callback: cb
        context: ctx

  # Get a mapping.
  #
  #   keyCode  - value of e.which or e.keyCode
  #   modifier - matches must match this modifier setting.
  get: (keyCode, modifier=false) ->
    key = @humanize keyCode, modifier
    matches = _.filter @mappings, (map) =>
      map.key == key

  # Collection of key mapping objects here.
  mappings: []

  # Convert key codes to human. Our list of special keys is incomplete, so
  # this may fail for certain codes.
  #
  #   keyCode - value of e.which or e.keyCode
  humanize: (keyCode, modifier) ->
    key = if modifier then 'ctrl+' else ''
    # Special key?
    return key += @specialKeys[keyCode] if @specialKeys[keyCode]?
    # Nope, normal key.
    key += String.fromCharCode(keyCode).toLowerCase()

  # Partial list of special keys (just the ones we're likely to use).
  specialKeys: 
    8: "backspace"
    9: "tab"
    13: "return"
    16: "shift"
    17: "ctrl"
    18: "alt"
    27: "esc"
    32: "space"
    37: "left" 
    38: "up"
    39: "right"
    40: "down" 
    187: "+"
    189: "-"
    191: "?"

# Fire it up an instance.
arcs.keys = arcs.utils.keys = new arcs.utils.Keys
