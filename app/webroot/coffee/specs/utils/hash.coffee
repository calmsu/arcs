describe 'arcs.utils.hash', ->
    
    afterEach ->
        document.location.hash = ''

    it 'can set the hash', ->
        arcs.utils.hash.set 'test'
        expect(document.location.hash[1..]).toBe 'test'

    it 'can get the hash', ->
        document.location.hash = 'test'
        expect(arcs.utils.hash.get()).toBe 'test'

    it 'can rewind the hash', ->
        arcs.utils.hash.set '1'
        arcs.utils.hash.set '2'
        arcs.utils.hash.rewind()
        expect(document.location.hash[1..]).toBe '1'
        
    it 'stores hash history', ->    
        arcs.utils.hash.set '1'
        arcs.utils.hash.set '2'
        expect(arcs.utils.hash.history.pop()).toBe '1'
