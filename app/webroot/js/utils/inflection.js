(function() {

  arcs.inflector = {
    PLURALS: {
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
    },
    CONJUGATIONS: {
      'was': 'were',
      'is': 'are',
      'am': 'are',
      'has': 'have'
    },
    pluralize: function(noun, n) {
      var exp, regex, repl, _ref;
      if (n == null) n = 0;
      if (!(noun.length && n !== 1)) return noun;
      _ref = this.PLURALS;
      for (exp in _ref) {
        repl = _ref[exp];
        regex = new RegExp(exp, 'gi');
        if (noun.match(regex)) {
          noun = noun.replace(regex, repl);
          break;
        }
      }
      return noun;
    },
    conjugate: function(verb, n) {
      if (n == null) n = 0;
      if (n === 1) return verb;
      return this.CONJUGATIONS[verb];
    },
    truncate: function(text, length, ending) {
      if (ending == null) ending = '...';
      if (!(text != null)) return '';
      if (text.length < length) return text;
      return text.substring(0, length) + ending;
    },
    identifierize: function(string) {
      var id;
      if (this._identifiers == null) this._identifiers = {};
      if (this._identifiers[string] != null) return this._identifiers[string];
      id = string.replace(/(\s|-)/g, '_').replace(/\W/g, '').toLowerCase();
      if (id.match(/^\d/)) id = '_' + id;
      return this._identifiers[string] = _.uniqueId(id + '_');
    },
    enquote: function(string, single) {
      var quote;
      if (single == null) single = true;
      quote = single ? "'" : '"';
      return quote + string + quote;
    }
  };

}).call(this);
