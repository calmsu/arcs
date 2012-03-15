# keyword.coffee
# --------------
# Keyword view
#
# Display existing keywords and add new ones.
class arcs.views.Keyword extends Backbone.View

  events:
    'keydown #keyword-btn': 'keydownDelegate'

  initialize: ->
    @collection = new arcs.collections.KeywordList

    arcs.bind 'resourceChange', =>
      @update()

    _.bindAll @, 'render'

    @collection.on 'add remove', @render, @

    arcs.utils.autocomplete 
      sel: '#keyword-btn'
      source: arcs.utils.complete.keyword()

    @update()

  keydownDelegate: (e) =>
    if e.keyCode == 13
      @saveKeyword()
      e.preventDefault()
      return false

  saveKeyword: ->
    $input = @$el.find('input#keyword-btn')
    keyword = new arcs.models.Keyword
      resource_id: arcs.resource.id
      keyword: $input.val()
    $input.val ''
    keyword.save()
    @collection.add(keyword)

  update: ->
    @collection.fetch
      success: =>
        @render()

  render: ->
    $keywords = $('#keywords-wrapper')
    $keywords.html arcs.tmpl 'resource/keywords', keywords: @collection.toJSON()
    @
