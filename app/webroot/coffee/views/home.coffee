# home.coffee
# -----------
#
class arcs.views.Home extends Backbone.View

  initialize: ->
    @search = new arcs.utils.Search
      container: $('.search-wrapper')
      run: false
      success: =>
        location.href = arcs.baseURL + 'search/' + @search.query
    @renderDetails $('details:first')

  events:
    'click summary': 'onClick'

  onClick: (e) ->
    @renderDetails $(e.currentTarget).parent()

  renderDetails: (el) ->
    type = el.data('type') 
    data = [
      'category': 'type'
      'value': type
    ]
    $.ajax
      type: 'POST'
      url: arcs.baseURL + 'resources/search?n=12'
      contentType: 'application/json'
      data: JSON.stringify data
      success: (data) ->
        el.children('div').html arcs.tmpl 'home/details', 
          resources: data
