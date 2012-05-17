# base_actions.coffee
# -------------------
# Sets up common actions on Resource model objects. Ripe for extending.
# Currently the `SearchActions` and `ViewerActions` views extend this.
class arcs.views.BaseActions extends Backbone.View

  # Open a resource in the viewer.
  openResource: (resource) ->
    # If the view provides a _modelFromRef method, we'll use it to resolve
    # the resource.
    resource = @_modelFromRef(resource) if @_modelFromRef?
    window.open arcs.baseURL + 'resource/' + resource.id

  # Creates a new Keyword
  keywordResource: (resource, string) -> 
    resource.set 'keywords', resource.get('keywords').concat(string)
    keyword = new arcs.models.Keyword
      resource_id: resource.id
      keyword: string
    keyword.save()

  # Creates a new Flag
  flagResource: (resource, reason, explanation) ->
    flag = new arcs.models.Flag
      resource_id: resource.id
      reason: reason
      explanation: explanation
    flag.save()

  # Applies form values to resource and its metadata, performs a comparison,
  # and syncs any changes to the server.
  editResource: (resource, attributes) ->
    metadata = resource.get 'metadata'
    _resource = _.clone resource.attributes
    _metadata = _.clone metadata.attributes
    for key, value of attributes
      if key in _.keys resource.attributes
        resource.set key, value
      else if metadata.get(key) or value
        metadata.set key, value
    resource.save() unless _.isEqual(resource.attributes, _resource)
    unless _.isEqual(metadata.attributes, _metadata)
      metadata.save()
      resource.trigger 'change'

  # Create a new Bookmark, given a resource and optionally a note string.
  bookmarkResource: (resource, note) ->
    bkmk = new arcs.models.Bookmark
      resource_id: resource.id
      description: note 
    bkmk.save()

  # Make a hidden iframe and load the resource into it. The server should be
  # setting a MIME type that will cause it to download.
  downloadResource: (resource) ->
    iframe = @make 'iframe',
      style: 'display: none'
      id: "downloader-for-#{resource.id}"
    $('body').append iframe
    iframe.src = arcs.baseURL + 'resources/download/' + resource.id

  # Request a PDF split of the resource.
  splitResource: (resource) ->
    return unless resource.get('mime_type') == 'application/pdf'
    $.post arcs.baseURL + 'resources/split_pdf/' + resource.id, ->
      arcs.notify 'Resource successfully queued for split.'

  # Request a re-thumbnailing of the resource.
  rethumbResource: (resource) ->
    $.post arcs.baseURL + 'resources/rethumb/' + resource.id, ->
      arcs.notify 'Resource successfully queued for re-thumbnail.'

  repreviewResource: (resource) ->
    $.post arcs.baseURL + 'resources/repreview/' + resource.id, ->
      arcs.notify 'Resource successfully queued for re-preview.'

  indexResource: (resource) ->
    $.post arcs.baseURL + 'resources/solr/' + resource.id, ->
      arcs.notify 'Resource successfully queued for SOLR index.'
