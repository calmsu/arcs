# upload.coffee
# -------------
# Upload view
class arcs.views.Upload extends Backbone.View

    initialize: ->
        @uploads = new arcs.collections.UploadSet
        @uploads.on 'add remove', @render, @
        @setupFileupload()

    setupFileupload: ->
        @fileupload = @$el.find('#fileupload')
        @fileupload.fileupload
            dataType: 'json'
            dropZone: @$el.find('#dropzone')
            url: arcs.baseURL + 'uploads/add'

        @fileupload.bind 'fileuploadadd', (e, data) =>
            arcs.log data
            arcs.log data.files
            @uploads.add data.files

        @fileupload.bind 'fileuploadprogress', (e, data) =>
            arcs.log data
            fname = _.first(data.files).fileName
            progress = parseInt(data.loaded / data.total * 100, 10)
            model = @uploads.find (m) ->
                m.get('fileName') == fname
            model.set 'progress', progress
            @render()

    render: ->
        $uploads = @$el.find('#uploads-container')
        uploads = @uploads.toJSON()
        _.each uploads, (u) ->
            u.fileSize = arcs.utils.convertBytes(u.fileSize)
        $uploads.html arcs.tmpl 'uploads', uploads: uploads
