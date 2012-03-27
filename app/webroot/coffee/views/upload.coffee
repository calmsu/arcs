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

  loading: false

  initialize: ->
    @uploads = new arcs.collections.UploadSet
    @setupFileupload()
    @$uploads = @$el.find('#uploads-container')
    @pending = 0
    @progress = 0

  events:
    'click #upload-btn': 'upload'

  setupFileupload: ->
    @fileupload = @$el.find('#fileupload')
    @fileupload.fileupload
      dataType: 'json'
      url: arcs.baseURL + 'uploads/add_files'

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

      progress: (e, data) =>
        arcs.log 'progress', data.files
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
        arcs.log 'done', data
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
        @checkForErrors()
        @render()

  upload: ->
    unless @pending == 0
      return arcs.notify('Downloads are still pending.', 'error')
    unless @uploads.length
      return arcs.notify('Choose a file to upload.', 'error')

    @uploads.each (u) =>
      $u = @$uploads.find(".upload[data-id=#{u.cid}]")
      u.set 'title', $u.find('#upload-title').val()
      u.set 'identifier', $u.find('#upload-identifier').val()

    $.ajax
      url: arcs.baseURL + 'uploads/batch'
      data: JSON.stringify @uploads
      type: 'POST'
      contentType: 'application/json'
      success: (data) =>
        location.href = arcs.baseURL + 'search/'

  checkForErrors: ->
    for upload in @uploads.models
      error = upload.get 'error'

      # No error, ok. Move on.
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
        "<a href='#{arcs.baseURL + 'docs/uploading'}'>Uploading " +
        "documentation.</a>"
      arcs.notify msg, 'error', false

      arcs.log upload, error

      # Disable the uploader.
      @disable()

  disable: ->
    @$el.addClass('disabled');
    @$el.find('a, button').addClass('disabled');

  render: ->
    for upload in @uploads.models
      $u = @$uploads.find(".upload[data-id=#{upload.cid}]")
      if upload.get('progress') < 100
        $u.find('.bar').css 'width', upload.get('progress') + '%'
      else
        $u.find('.progress').hide()
        $u.find('span#progress-done').show()
    if @progress == 100 or @pending == 0
      msg = "<i class='icon-ok'></i> All Done" 
    else
      msg = "#{@progress.toFixed(2)}% (#{@pending} pending)"
    @$('span#progress-all').html msg
    @
