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

        arcs.bind 'resourceChange', =>
            @update()

        _.bindAll @, 'render'

        @collection.bind 'add', @render, @
        @collection.bind 'remove', @render, @

        @update()

    saveComment: ->
        $textarea = @el.find('textarea#content')
        comment = new arcs.models.Comment
            resource_id: arcs.resource.id
            content: $textarea.val()
            # These next two are just temporary values so we don't need to 
            # fetch the collection again to get them.
            _name: 'You'
            _created: 'just now'
        $textarea.val ''
        comment.save()
        @collection.add(comment)

    update: ->
        @collection.fetch
            success: =>
                @render()

    render: ->
        $discussion = $('#comment-wrapper')
        $discussion.html Mustache.render arcs.templates.discussion,
            comments: @collection.toJSON()
        @
