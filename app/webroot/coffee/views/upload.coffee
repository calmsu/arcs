# upload.coffee
# -------------
# Upload view
class arcs.views.Upload extends Backbone.View

  # PHP $_FILES errors
  UPLOAD_ERR_OK       : 0
  UPLOAD_ERR_INI_SIZE : 1
  UPLOAD_ERR_FORM_SIZE: 2
  UPLOAD_ERR_PARTIAL  : 3
  UPLOAD_ERR_NO_FILE  : 4

  # Track our progress
  pending : 0
  progress: 0
  allDone : false

  # Make a collection, set things up.
  initialize: ->
    @uploads = new arcs.collections.UploadSet
    @setupFileupload()
    @$uploads = @$el.find('#uploads-container')
    @uploads.on 'add remove change', @render, @

  events:
    'click #upload-btn': 'upload'
    'click .remove'    : 'remove'

  # Fire up jQuery-File-Upload (https://github.com/blueimp/jQuery-File-Upload)
  # and attach event handlers.
  setupFileupload: ->
    @fileupload = @$el.find('#fileupload')
    @fileupload.fileupload
      dataType: 'json'
      url: arcs.url 'uploads/add_files'

      add: (e, data) =>
        maybeUploads = (new arcs.models.Upload(f) for f in data.files)
        existing = @uploads.pluck 'name'
        uploads = _.reject maybeUploads, (u) -> 
          name = u.get('name')
          if name in existing
            arcs.notify "You tried to add '#{name}', but a file with that " +
              "name is already being uploaded, so we couldn't add it. If it is " +
              "not the same file, change the name or upload it in another " +
              "batch.", 'error', false
            return true
          false
        return false if not uploads.length

        @uploads.add uploads

        for u in uploads
          upload = u.toJSON()
          upload.cid = u.cid
          @$uploads.append arcs.tmpl 'upload/list', upload
        @$('span#drop-msg').remove()
        @$('#upload-btn').addClass 'disabled'
        data.submit()
        @pending += 1

      fail: (e, data)  =>
        arcs.needsLogin() if data.errorThrown is 'Forbidden'

      progress: (e, data) =>
        progress = parseInt(data.loaded / data.total * 100, 10)
        for f in data.files
          model = @uploads.find (m) ->
            m.get('name') == f.name
          model.set 'progress', progress
        @render()

      progressall: (e, data) =>
        @progress = data.loaded / data.total * 100
        @render()

      done: (e, data) =>
        for f in data.files
          model = @uploads.find (m) ->
            m.get('name') == f.name
          response = _.find data.result, (r) ->
            r.name == f.name
          model.set 'progress', 100
          model.set 'sha', response.sha
          model.set 'error', response.error
        @pending -= 1
        if not @pending
          @$el.find('#upload-btn').removeClass('disabled')
          @allDone = true
        @checkForErrors() and @render()

  # Finalize the upload and redirect to the search.
  upload: ->
    unless @pending == 0
      return arcs.notify 'Downloads are still pending.', 'error'
    unless @uploads.length
      return arcs.notify 'Choose a file to upload.', 'error'

    # Attach the user inputs to the models.
    @uploads.each (u) =>
      $u = @$uploads.find(".upload[data-id=#{u.cid}]")
      u.set 'title', $u.find('#upload-title').val()
      u.set 'identifier', $u.find('#upload-identifier').val()
      u.set 'rtype', $u.find('#upload-type').val()
      # Don't use the placeholder.
      u.set('identifier', '') if u.get('identifier') == 'Identifier'

    # POST our uploads. 
    #
    # The server already has the files, it's given us back their SHA1s. Now 
    # we're telling it that we want resources connected to those files, using 
    # the inputs we just collected.
    $.postJSON arcs.baseURL + 'uploads/batch', @uploads.toJSON(), (data) ->
      location.href = arcs.url 'search/'

  # Scan the backbone models for errors (each maintains an `error` prop).
  # If an error is found, shut things down and report it.
  checkForErrors: ->
    for upload in @uploads.models
      error = upload.get 'error'

      # No error. Ok, move on.
      continue if error == @UPLOAD_ERR_OK

      # Is the error file size related?
      if error == @UPLOAD_ERR_INI_SIZE or error == @UPLOAD_ERR_FORM_SIZE
        msg = "The file '#{upload.get 'name'}' was too large. If " +
          "possible, split the file into pieces."
      # Was file only partially uploaded?
      else if error == @UPLOAD_ERR_PARTIAL
        msg = "The file '#{upload.get 'name'}' was only partially " +
          "uploaded. Please refresh the page and try again."
      # Something else? (This category holds the server-side errors)
      else
        msg = "Something went wrong. Please refresh the page and " +
          "try again. If the problem persists, contact the system " +
          "administrator."

      # Tack on a help link and notify the user.
      msg += " For more information, see our " +
        "<a href='#{arcs.url 'help/uploading'}'>Uploading " +
        "documentation.</a>"
      arcs.notify msg, 'error', false

      # Disable the uploader.
      @disable()

  remove: (e) ->
    $upload = @$(e.currentTarget).parents '.upload'
    @uploads.remove @uploads.getByCid $upload.data 'id'
    $upload.remove()
    @$('#upload-btn').addClass('disabled') unless @uploads.length

  # Disable the uploader (visually) in an error scenario.
  disable: ->
    @$el.addClass('disabled');
    @$el.find('a, button').addClass('disabled');

  render: ->
    # Unfortunately we can't just render the whole thing from a template
    # because the user inputs need to stay untouched.
    for upload in @uploads.models
      $u = @$uploads.find(".upload[data-id=#{upload.cid}]")
      if upload.get('progress') < 100
        $u.find('.bar').css 'width', upload.get('progress') + '%'
      else
        $u.find('.progress').hide()
        $u.find('span#progress-done').show()
    # No pending and all requests are 'done'
    if @allDone
      msg = "<i class='icon-ok'></i> All Done" 
    # Progress for all is 100%, but we're still waiting on a response.
    else if @progress == 100 or @pending == 0
      msg = "<i class='icon-time'></i> Waiting on server..." 
    # Display # pending.
    else
      msg = "#{@progress.toFixed(2)}% (#{@pending} pending)"
    @$('span#progress-all').html msg
    @
