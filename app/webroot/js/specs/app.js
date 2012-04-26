(function() {

  describe('arcs', function() {
    it('can log', function() {
      return expect(arcs.log).toBeDefined();
    });
    return it('can bind events', function() {
      var triggered,
        _this = this;
      triggered = false;
      arcs.bind('testBind', function() {
        return triggered = true;
      });
      arcs.trigger('testBind');
      return expect(triggered).toBe(true);
    });
  });

}).call(this);
