(function() {

  describe('arcs.utils.search', function() {
    var search;
    search = null;
    beforeEach(function() {
      return search = new arcs.utils.Search({
        query: '',
        run: false,
        success: function() {},
        error: function() {}
      });
    });
    it('can be constructed', function() {
      expect(arcs.utils.Search).toBeDefined();
      return expect(search instanceof arcs.utils.Search).toBe(true);
    });
    it('inits Visual Search', function() {
      return expect(search.vs).toBeTruthy();
    });
    it('can be run, returns results', function() {
      search.run();
      expect(search.results).toBeDefined();
      expect(search.results.length).toBeTruthy();
      return expect;
    });
    it('has a ResultSet object', function() {
      search.run();
      return expect(search.results instanceof arcs.collections.ResultSet).toBe(true);
    });
    return it('will not error on an empty search', function() {
      var callback, error;
      spyOn(search, 'run');
      callback = jasmine.createSpy();
      search.run(error = callback);
      return expect(callback).not.toHaveBeenCalled();
    });
  });

}).call(this);
