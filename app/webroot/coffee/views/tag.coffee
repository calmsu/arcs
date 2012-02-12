# tag.coffee
# ----------
# Tag view
#
# Display existing tags and add new ones.
class arcs.views.Tag extends Backbone.View

    events:
        'keydown #new-tag': 'keydownDelegate'

    initialize: ->
        @collection = new arcs.collections.TagList

        arcs.bind 'resourceChange', =>
            @.update()

        _.bindAll @, 'render'

        @collection.bind 'add', @.render, @
        @collection.bind 'remove', @.render, @

        @.update()

    keydownDelegate: (e) =>
        if e.keyCode == 13
            @.saveTag()
            e.preventDefault()
            return false

    saveTag: ->
        $input = @el.find('input#new-tag')
        tag = new arcs.models.Tag
            resource_id: arcs.resource.id
            tag: $input.val()
        $input.val ''
        tag.save()
        @collection.add(tag)

    update: ->
        @collection.fetch
            success: =>
                @.render()

    render: ->
        $tags = $('#tags-wrapper')
        $tags.html Mustache.render arcs.templates.tagList,
            tags: @collection.toJSON()
        @
