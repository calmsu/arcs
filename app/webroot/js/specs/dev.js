(function() {

  describe('dev', function() {
    return it("doesn't reload stylesheets by default", function() {
      return expect(arcs.dev.reload).toBe(false);
    });
  });

}).call(this);
