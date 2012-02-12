# tag.coffee
# ----------
class arcs.models.Tag extends Backbone.Model
    urlRoot: arcs.baseURL + 'tags'

    validate: (attrs) ->
        # Check uniqueness (as far as we know)
        if arcs.tagView?
            tags = arcs.tagView.collection.pluck 'tag'
            tags.push attrs.tag
            if _.uniq(tags).length != tags.length
                # Don't do anything for now.
                arcs.log 'non unique'
