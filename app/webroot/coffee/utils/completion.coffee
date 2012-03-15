# completion.coffee
# -----------------
# Helpers for completing input fields.

# The complete object provides methods for retrieving completion values
# from the server.
arcs.utils.complete =

  user: ->
    @_get 'users/complete'

  tag: ->
    @_get 'tags/complete'

  title: ->
    @_get 'resources/complete/title'

  type: ->
    @_get 'resources/complete/type'

  created: ->
    @_date 'resources/complete/created'

  modified: ->
    @_date 'resources/complete/modified'

  collection: ->
    @_get 'collections/complete'

  # We'll store completion values by url and timestamp them.
  # The _get method may choose to use the cache or not.
  _cache: {}

  # Get a url from the server (synchronously), and clean up the results.
  _get: (url, fresh=false) ->

    if _.has(@_cache, url)
      [data, ts] = @_cache[url]
      return data unless fresh or (Date.now() - ts > 10000)

    result = []
    $.ajax
      url: arcs.baseURL + url
      async: false
      dataType: 'json'
      success: (data) ->
        result = _.without(_.uniq(_.values(data)), null)

    @_cache[url] = [result, Date.now()]
    return result

  # Get an array of dates from the server, reformat them, and add a few
  # aliases. Helper method for date-type facets.
  _date: (url) ->
    raw_dates = @_get url
    fmt = 'MM-DD-YYYY'
    parse_fmt = 'YYYY-MM-DD HH:mm:ss'
    dates = (moment(d, parse_fmt).format(fmt) for d in raw_dates)
    aliases = [
      {label:'today', value: moment().format(fmt)},
      {label:'yesterday', value: moment().subtract('days', 1).format(fmt)}
    ]
    _.uniq _.union dates, aliases

# Make sure arcs.utils.complete methods are called with that context.
_.bindAll(arcs.utils.complete)


# The autocomplete method wraps jQueryUI's autocomplete and ensures
# that the input field remains in focus. It can also handle multiple
# value autocompletion (i.e. comma-separated values).
#
# options
#   source   - array of completion values, or function that returns one.
#   multiple - handle multiple value completion.
#   sel:     - input selectior (e.g. #tag-field or .some-input)
arcs.utils.autocomplete = (opts) ->

  # Set our defaults
  defaults = 
    source: []
    multiple: false
    sel: null
  options = _.extend(defaults, opts)

  $el = $(options.sel)

  # These are adapted from the jQueryUI docs.
  split = (val) ->
    val.split /,\s*/ 
  getLast = (term) ->
    split(term).pop()
  addTerm = (val, appendage) ->
    terms = split val
    terms.pop()
    terms.push appendage, ''
    return terms.join ', '

  # Just call functions that appear to be missing the (request, response)
  # params.
  if _.isFunction(options.source) and options.source.length == 0 
    [options.source, options._source] = [options.source(), options.source]

  # If the multiple option is on, we'll do some extra work.
  if options.multiple

    # Wrap the source option in a function that matches the last term.
    options._source = options.source
    options.source = (request, response) ->
      filter = $.ui.autocomplete.filter
      response filter(options._source, getLast(request.term))

    # Selecting an item should append it to the input.
    select = (event, ui) ->
      @value = addTerm(@value, ui.item.value)
      false

    focus = -> false

  # Set up jQueryUI autocomplete
  $el.autocomplete 
    source: options.source
    autoFocus: true
    focus: focus ? ->
    minLength: 0
    select: select ? ->

  # Never navigate away on tab.
  $el.on 'keydown', (e) ->
    if e.keyCode == 9
      e.preventDefault()
      false
