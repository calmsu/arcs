# result_set.coffee
# -----------------
class arcs.collections.ResultSet extends Backbone.Collection
  model: arcs.models.Resource

  url: -> 
    arcs.baseURL + 'resources/search'

  # Returns selected results
  selected: ->
    @filter (result) ->
      result.get 'selected'

  # Returns unselected results
  notSelected: ->
    @reject (result) ->
      result.get 'selected'

  # Returns true if any results are selected
  anySelected: ->
    @any (result) ->
      result.get 'selected'

  # Returns the number of selected results
  numSelected: ->
    @selected().length

  # Selects a single result or array of results, given by id.
  select: (result) ->
    @_eachSetSelected result, -> 
      true

  # Toggles selection on a single result or array of results, given by id.
  toggle: (result) ->
    @_eachSetSelected result, (m) -> 
      not m.get 'selected'

  # Unselects a single result or array of results, given by id.
  unselect: (result) ->
    @_eachSetSelected result, -> 
      false
  
  unselectAll: ->
    @each (result) ->
      result.set 'selected', false, {silent: true}

  # Given one or more result ids, set the `selected` property of each
  # of the resolved results to the return of the given function, which 
  # is called with the result.
  _eachSetSelected: (result, func) ->
    result = [result] unless _.isArray(result)
    for id in result
      model = @get(id)
      continue unless model
      model.set 'selected', func(model), {silent: true}

  parse: (response) ->
    @metadata = response
    response.results
