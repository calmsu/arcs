describe 'arcs.utils.keys', ->

    it 'binds to the document object', ->
        spyOn arcs.utils.keys, 'delegate'
        $(document).trigger 'keydown'
        expect(arcs.utils.keys.delegate).toHaveBeenCalled()
