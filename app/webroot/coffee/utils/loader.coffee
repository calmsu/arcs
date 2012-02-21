# loader.coffee
# -------------
arcs.utils.loader = 
    show: ->
        unless $('#arcs-loader').length
            $('body').append arcs.templates.loader
        $('#arcs-loader').show()
    hide: ->
        $('#arcs-loader').hide()
