# collections.coffee
# ------------------
class arcs.views.CollectionList extends Backbone.View

  initialize: ->
    @search = new arcs.utils.Search
      container: $('.search-wrapper')
      run: false
      onSearch: =>
        location.href = arcs.url 'search', @search.query
    @render()
    @$('details.open').each (i, el) =>
      @renderDetails $(el)

  events:
    'click summary': 'onClick'
    'click details.closed': 'onClick'
    'click #delete-btn': 'deleteCollection'

  onClick: (e) ->
    if e.currentTarget.tagName == 'DETAILS'
      $el = $(e.currentTarget)
    else
      $el = $(e.currentTarget).parent()
    console.log($el)
    $el.toggleAttr('open')
    $el.toggleClass('closed').toggleClass('open')
    @renderDetails $el
    # Recent versions of webkit will toggle <details> automatically. 
    # Instead of checking for support, we'll just stop it from bubbling up, 
    # since we've just toggled it ourselves.
    if (e.srcElement.tagName not in ['SPAN', 'BUTTON', 'I', 'A'])
      e.preventDefault()
      false

  deleteCollection: (e) ->
    e.preventDefault()
    $parent = $(e.currentTarget).parents 'details'
    id = $parent.data 'id'
    model = @collection.get id
    
    arcs.confirm "Are you sure you want to delete this collection?",
      "<p>Collection <b>#{model.get('title')}</b> will be " +
      "deleted. <p><b>N.B.</b> Resources within the collection will not be " +
      "deleted. They may still be accessed from other collections to which they " +
      "belong.", =>
        arcs.loader.show()
        $.ajax
          url: arcs.url 'collections', 'delete', model.id
          type: 'DELETE'
          success: =>
            @collection.remove(model, silent: true)
            @render()
            arcs.loader.hide()

  render: ->
    @$el.html arcs.tmpl 'collections/list',
      collections: @collection.toJSON()
    @

  renderDetails: ($el) ->
    id = $el.data 'id'
    query = encodeURIComponent('collection_id:"' + id + '"')
    $.getJSON arcs.baseURL + "resources/search?n=12&q=#{query}", (response) ->
      $el.children('.results').html arcs.tmpl 'home/details', 
        resources: response.results
        searchURL: arcs.baseURL + "collection/#{id}"
