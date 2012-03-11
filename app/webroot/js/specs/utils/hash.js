(function() {

  describe('arcs.utils.hash', function() {
    afterEach(function() {
      return document.location.hash = '';
    });
    it('can set the hash', function() {
      arcs.utils.hash.set('test');
      return expect(document.location.hash.slice(1)).toBe('test');
    });
    it('can get the hash', function() {
      document.location.hash = 'test';
      return expect(arcs.utils.hash.get()).toBe('test');
    });
    it('can rewind the hash', function() {
      arcs.utils.hash.set('1');
      arcs.utils.hash.set('2');
      arcs.utils.hash.rewind();
      return expect(document.location.hash.slice(1)).toBe('1');
    });
    return it('stores hash history', function() {
      arcs.utils.hash.set('1');
      arcs.utils.hash.set('2');
      return expect(arcs.utils.hash.history.pop()).toBe('1');
    });
  });

}).call(this);
