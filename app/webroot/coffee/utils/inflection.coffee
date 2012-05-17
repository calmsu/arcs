# inflection.coffee
# -----------------
# Handles inflecting nouns, which is useful in generating dialogs. For example:
#   "12 resources were flagged" vs. "1 resource was flagged"

arcs.inflector = 

  PLURALS:
    '(m)an$'                 : '$1en'
    '(pe)rson$'              : '$1ople'
    '(child)$'               : '$1ren'
    '^(ox)$'                 : '$1en'
    '(ax|test)is$'           : '$1es'
    '(octop|vir)us$'         : '$1i'
    '(alias|status)$'        : '$1es'
    '(bu)s$'                 : '$1ses'
    '(buffal|tomat|potat)o$' : '$1oes'
    '([ti])um$'              : '$1a'
    'sis$'                   : 'ses'
    '(?:([^f])fe|([lr])f)$'  : '$1$2ves'
    '(hive)$'                : '$1s'
    '([^aeiouy]|qu)y$'       : '$1ies'
    '(x|ch|ss|sh)$'          : '$1es'
    '(matr|vert|ind)ix|ex$'  : '$1ices'
    '([m|l])ouse$'           : '$1ice'
    '(quiz)$'                : '$1zes'
    's$'                     : 's'
    '$'                      : 's'

  CONJUGATIONS:
    'was' : 'were'
    'is'  : 'are'
    'am'  : 'are'
    'has' : 'have'

  # Pluralizes an english noun.
  pluralize: (noun, n=0) ->
    return noun unless noun.length and n != 1
    for exp, repl of @PLURALS
      regex = new RegExp(exp, 'gi')
      if noun.match regex
        noun = noun.replace regex, repl
        break
    noun

  # Does some very simple verb conjugating, given the plurality of the noun
  # that the verb is acting on.
  conjugate: (verb, n=0) ->
    return verb unless n != 1
    @CONJUGATIONS[verb]

  # Truncate a string to the specified length.
  truncate: (text, length, ending='...') ->
    return '' if not text?
    return text if text.length < length
    text.substring(0, length) + ending

  # Takes a string and makes replacements as necessary so that it's suitable for
  # use as a CSS or JS identifier. 
  #
  # Identifiers are unique to the string. The same string will always return the 
  # same identifier, so the method can be used as a simple hash function.
  identifierize: (string) ->
    # For uniqueness, we're appending _.uniqueId. For same input => same output,
    # we just cache strings we've already processed.
    @_identifiers ?= {}
    return @_identifiers[string] if @_identifiers[string]?
    id = string.replace(/(\s|-)/g, '_').replace(/\W/g, '').toLowerCase()
    id = '_' + id if id.match /^\d/
    @_identifiers[string] = _.uniqueId id + '_'

  # Occasionally useful in javascript template files when escaping gets weird.
  enquote: (string, single=true) ->
    quote = if single then "'" else '"'
    quote + string + quote
