# keyword.coffee
# --------------
# Keyword view
#
# Display existing keywords and add new ones.
class arcs.views.Keyword extends Backbone.View

  events:
    'keydown #keyword-btn': 'saveKeyword'
    'click .keyword-remove-btn': 'deleteKeyword'

  initialize: ->
    @collection = new arcs.collections.KeywordList

    arcs.bus.on 'indexChange', => @collection.fetch()
    @collection.on 'add remove reset sync', @render, @

    arcs.utils.autocomplete 
      sel: '#keyword-btn'
      source: arcs.complete 'keywords/complete'

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

  deleteKeyword: (e) ->
    $keyword = $(e.target).parent().find('.keyword-link')
    keyword = @collection.get $keyword.data 'id'
    return unless keyword
    arcs.confirm 'Are you sure?', 
      "This keyword will be deleted.", =>
        keyword.destroy()

  render: ->
    @$('#keywords-wrapper').html arcs.tmpl 'viewer/keywords', 
      keywords: @collection.toJSON()
    @
