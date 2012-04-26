(function() {

  describe('arcs.utils.keys', function() {
    return it('binds to the document object', function() {
      spyOn(arcs.utils.keys, 'delegate');
      $(document).trigger('keydown');
      return expect(arcs.utils.keys.delegate).toHaveBeenCalled();
    });
  });

}).call(this);
