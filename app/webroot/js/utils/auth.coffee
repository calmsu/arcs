# auth.coffee
# -----------
# Utilities for determining what the user is allowed to do, given
# a user permissions object and a Resource model.
#
# Of course, the server has the ultimate say as to what is permitted.
# Unauthorized actions will yield a 401 or 403 status regardless. The
# goal here is to stop the user *before* they send an unauthorized 
# request.

arcs.utils.auth = {}

arcs.utils.auth.canView = (user=null, resource=null) ->
    if user.role < 3
        return true
    false

arcs.utils.auth.canMeta = (user=null, resource=null) ->
    if user.role <= 1
        return true
    exclusive = resource.get 'exclusive'
    if user.role == 2 and not exclusive
        return true
    false

arcs.utils.auth.canDelete = (user=null, resource=null) ->

arcs.utils.auth.canResolve = (user=null, resource=null) ->
    if user.role <= 1
        return true
