# keyword.coffee
# --------------
# Keyword view
#
# Display existing keywords and add new ones.
class arcs.views.Keyword extends Backbone.View

  events:
    'keydown #keyword-btn': 'saveKeyword'

  initialize: ->
    @collection = new arcs.collections.KeywordList

    arcs.bus.on 'indexChange', => @collection.fetch()
    @collection.on 'add remove reset', @render, @

    arcs.utils.autocomplete 
      sel: '#keyword-btn'
      source: arcs.utils.complete.keyword()

    @collection.fetch()

  saveKeyword: (e) ->
    return unless e.keyCode == 13
    e.preventDefault()

    $input = @$el.find('input#keyword-btn')
    keyword = new arcs.models.Keyword
      resource_id: arcs.resource.id
      keyword: $input.val()
    $input.val ''
    keyword.save()
    @collection.add(keyword)

    # Return false to ensure the keydown doesn't bubble up.
    return false

  render: ->
    @$('#keywords-wrapper').html arcs.tmpl 'viewer/keywords', 
      keywords: @collection.toJSON()
    @
