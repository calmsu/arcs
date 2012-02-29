# discussion.coffee
# -----------------
# Discussion view
#
# Display existing comments and save new ones.
class arcs.views.Discussion extends Backbone.View

    events:
        'click #comment-button': 'saveComment'

    initialize: ->
        @collection = new arcs.collections.Discussion

        arcs.on 'resourceChange', @update, @
        @collection.on 'add remove', @render, @

        @update()

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

    update: ->
        @collection.fetch
            success: =>
                @render()

    render: ->
        $discussion = $('#comment-wrapper')
        $discussion.html arcs.tmpl 'discussion', comments: @collection.toJSON()
        @
