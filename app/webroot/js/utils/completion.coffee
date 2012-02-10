arcs.utils.complete =

    default: (url) ->
        result = []
        $.ajax
            url: arcs.baseURL + url
            async: false
            dataType: 'json'
            success: (data) ->
                arcs.log data
                result = _.without(_.uniq(_.values(data)), null)
        return result

    users: ->
        arcs.utils.complete.default 'users/complete'

    tags: ->
        arcs.utils.complete.default 'tags/complete'

    titles: ->
        arcs.utils.complete.default 'resources/complete'
