# tag_list.coffee
# ---------------
class arcs.collections.TagList extends Backbone.Collection

    model: arcs.models.Tag

    url: ->
        arcs.baseURL + "resources/tags/" + arcs.resource.id

    parse: (response) ->
        tags = (r.Tag for r in response)
        for t in tags
            t.link = arcs.baseURL + "search/#" + 
                encodeURIComponent("tag: '#{t.tag}'")
        return tags
