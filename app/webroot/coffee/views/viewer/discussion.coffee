# discussion.coffee
# -----------------
# Display existing comments and save new ones.
class arcs.views.DiscussionTab extends Backbone.View

  events:
    'click #comment-btn': 'saveComment'

  initialize: ->
    @collection = new arcs.collections.Discussion
    @collection.on 'add remove reset', @render, @
    @$tab = $('#discussion-btn')
    @$tab.on 'click', => @update()
    arcs.bus.on 'indexChange', => 
      @update() if @isActive()
    @update() if @isActive()

  activate: ->
    $('#discussion-btn a').tab 'show'
    @collection.fetch()

  update: ->
    @$el.parent().css 'opacity', '0.2'
    @collection.fetch
      success: =>
        @$el.parent().css 'opacity', '1.0'
      error: ->
        arcs.notify 'An error occurred while loading comments', 'error'

  isActive: ->
    $('#discussion-btn').hasClass 'active'

  saveComment: ->
    $textarea = @$el.find('textarea#content')
    return arcs.notify 'Enter a comment.' unless $textarea.val()
    comment = new arcs.models.Comment
      resource_id: arcs.resource.id
      content: $textarea.val()
    $textarea.val ''
    comment.save()
    comment.set 
      created: new Date
      username: arcs.user.get 'username'
      name: arcs.user.get 'name'
    @collection.add comment

  render: ->
    $('#comment-wrapper').html arcs.tmpl 'viewer/discussion', 
      comments: @collection.toJSON()
    @
