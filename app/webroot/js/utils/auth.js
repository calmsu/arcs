
arcs.utils.auth = {};

arcs.utils.auth.canView = function(user, resource) {
  if (user == null) user = null;
  if (resource == null) resource = null;
  if (user.role < 3) return true;
  return false;
};

arcs.utils.auth.canMeta = function(user, resource) {
  var exclusive;
  if (user == null) user = null;
  if (resource == null) resource = null;
  if (user.role <= 1) return true;
  exclusive = resource.get('exclusive');
  if (user.role === 2 && !exclusive) return true;
  return false;
};

arcs.utils.auth.canDelete = function(user, resource) {
  if (user == null) user = null;
  if (resource == null) resource = null;
};

arcs.utils.auth.canResolve = function(user, resource) {
  if (user == null) user = null;
  if (resource == null) resource = null;
  if (user.role <= 1) return true;
};
