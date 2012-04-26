# mime.coffee
# -----------
# Return information about mime types
arcs.utils.mime = arcs.mime = 
  imageTypes:
    'image/png': 'png'
    'image/jpeg': 'jpeg'
    'image/jpg': 'jpg'
    'image/gif': 'gif'
    'image/tiff': 'tiff'

  documentTypes:
    'application/pdf': 'pdf'
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'docx'
    'application/msword': 'doc'
    'text/plain': 'plaintext'
    'text/richtext': 'richtext'
    'text/rtf': 'rtf'

  videoTypes:
    'video/mpeg': 'mpeg'
    'video/msvideo': 'avi'
    'video/quicktime': 'mov'
    'video/mp4': 'mp4'

  isDocument: (mime) ->
    mime in _.keys @documentTypes

  isImage: (mime) ->
    mime in _.keys @imageTypes

  isVideo: (mime) ->
    mime in _.keys @videoTypes

  types: ->
    _.extend @videoTypes, @documentTypes, @imageTypes

  getInfo: (mime) ->
    undef =
      type: 'undefined'
      ext: null

    types =
      image: @imageTypes
      document: @documentTypes
      video: @videoTypes

    for type, obj of types
      if mime in _.keys(types[type])
        result = 
          type: type
          ext: types[type][mime]

    result or undef 

_.bindAll arcs.utils.mime
