# annotation_list.coffee
# ----------------------
class arcs.collections.AnnotationList extends Backbone.Collection

  model: arcs.models.Annotation

  url: ->
    arcs.baseURL + "resources/annotations/" + arcs.resource.id

  parse: (r) ->
    @relations = new arcs.collections.Collection(r.relations) if r.relations?
    r.annotations
