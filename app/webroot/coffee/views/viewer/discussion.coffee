# discussion.coffee
# -----------------
# Discussion view
#
# Display existing comments and save new ones.
class arcs.views.Discussion extends Backbone.View

  events:
    'click #comment-btn': 'saveComment'

  initialize: ->
    @collection = new arcs.collections.Discussion

    arcs.on 'arcs:indexChange', => @collection.fetch()
    @collection.on 'add remove reset', @render, @

    @collection.fetch()

  saveComment: ->
    $textarea = @$el.find('textarea#content')
    comment = new arcs.models.Comment
      resource_id: arcs.resource.id
      content: $textarea.val()
    $textarea.val ''
    comment.save()
    comment.set 
      name: 'You' 
      created: 'just now'
    @collection.add(comment)

  render: ->
    $('#comment-wrapper').html arcs.tmpl 'resource/discussion', 
      comments: @collection.toJSON()
    @
