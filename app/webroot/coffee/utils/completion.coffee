# completion.coffee
# -----------------

# Return a memoized version of a function.  The expire parameter is the
# time in milliseconds after which the cache will be considered expired.
memoize = (f, expire) ->
  cache = {}
  ->
    args = _.toArray(arguments)
    key = args.join('')  # naive but suitable for ajax urls
    time = Date.now()
    if key of cache and (time - cache[key][0] < expire)
      return cache[key][1]
    res = f.apply(@, args)
    cache[key] = [time, res]
    res


completeFacet = (category, query='') ->
  result = []
  $.ajax
    url: arcs.baseURL + "resources/complete?cat=#{category}&q=#{query}"
    async: false
    dataType: 'json'
    success: (data) ->
      result = _.compact _.uniq _.values(data)
  result


complete = (url) ->
  result = []
  $.ajax
    url: arcs.baseURL + url
    async: false
    dataType: 'json'
    success: (data) ->
      result = _.compact _.uniq _.values(data)
  result


arcs.completeFacet = memoize completeFacet, 30000
arcs.complete = memoize complete, 30000


# Get an array of dates from the server, reformat them, and add a few
# aliases. Helper method for date-type facets.
arcs.completeDate = (url) ->
  raw = arcs.complete url
  [fmt, parseFmt] = ['DD-MM-YYYY', 'YYYY-MM-DD HH:mm:ss']
  dates = (moment(d, parseFmt).format(fmt) for d in raw)
  aliases = [
    {label:'today', value: moment().format(fmt)},
    {label:'yesterday', value: moment().subtract('days', 1).format(fmt)}
  ]
  _.union dates, aliases


# The autocomplete method wraps jQueryUI's autocomplete and ensures
# that the input field remains in focus. It can also handle multiple
# value autocompletion (i.e. comma-separated values).
#
# options
#   source   - array of completion values, or function that returns one.
#   multiple - handle multiple value completion.
#   sel:     - input selectior (e.g. #keyword-input or .title-input)
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
