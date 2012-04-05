# home.coffee
# -----------
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
    order = _.shuffle(['title', 'modified']).pop()
    $.getJSON arcs.baseURL + "resources/search?n=12&order=#{order}", (data) =>
      el.children('div').html arcs.tmpl 'home/details', 
        resources: data
