# mime.coffee
# -----------
# Return information about mime types
arcs.utils.mime = 
  imageTypes:
    'image/png': 'png'
    'image/jpeg': 'jpeg'
    'image/jpg': 'jpg'
    'image/gif': 'gif'

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
