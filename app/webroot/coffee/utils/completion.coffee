# completion.coffee
# -----------------
# Helpers for completing input fields.

# The complete object provides methods for retrieving completion values
# from the server.
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

# The autocomplete func wraps jQueryUI's autocomplete and ensures
# that the input field remains in focus.
arcs.utils.autocomplete = (opts) ->
    $el = $(opts.sel)
    $el.autocomplete 
        source: opts.source
        autoFocus: true
    $el.on 'autocompleteselect', (event, ui) ->
        $el.val(ui.item.value)
        return false
