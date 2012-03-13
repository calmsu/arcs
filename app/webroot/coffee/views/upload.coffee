# upload.coffee
# -------------
# Upload view
class arcs.views.Upload extends Backbone.View

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
        @pending -= 1
        if not @pending
          @$el.find('#upload-btn').removeClass('disabled')
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
