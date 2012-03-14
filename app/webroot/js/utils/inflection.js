(function() {
  var CONJUGATIONS, PLURALS;

  PLURALS = {
    '(m)an$': '$1en',
    '(pe)rson$': '$1ople',
    '(child)$': '$1ren',
    '^(ox)$': '$1en',
    '(ax|test)is$': '$1es',
    '(octop|vir)us$': '$1i',
    '(alias|status)$': '$1es',
    '(bu)s$': '$1ses',
    '(buffal|tomat|potat)o$': '$1oes',
    '([ti])um$': '$1a',
    'sis$': 'ses',
    '(?:([^f])fe|([lr])f)$': '$1$2ves',
    '(hive)$': '$1s',
    '([^aeiouy]|qu)y$': '$1ies',
    '(x|ch|ss|sh)$': '$1es',
    '(matr|vert|ind)ix|ex$': '$1ices',
    '([m|l])ouse$': '$1ice',
    '(quiz)$': '$1zes',
    's$': 's',
    '$': 's'
  };

  CONJUGATIONS = {
    'was': 'were',
    'is': 'are',
    'am': 'are',
    'has': 'have'
  };

  arcs.pluralize = function(noun, n) {
    var exp, regex, repl;
    if (n == null) n = 0;
    if (!(noun.length && n !== 1)) return noun;
    for (exp in PLURALS) {
      repl = PLURALS[exp];
      regex = new RegExp(exp, 'gi');
      if (noun.match(regex)) {
        noun = noun.replace(regex, repl);
        break;
      }
    }
    return noun;
  };

  arcs.conjugate = function(verb, n) {
    if (n == null) n = 0;
    if (n === 1) return verb;
    return CONJUGATIONS[verb];
  };

}).call(this);
