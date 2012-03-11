describe 'arcs.utils.search', ->

    search = null

    beforeEach ->
        search = new arcs.utils.Search
            query: ''
            run: false
            success: ->
            error: ->

    it 'can be constructed', ->
        expect(arcs.utils.Search).toBeDefined()
        expect(search instanceof arcs.utils.Search).toBe true

    it 'inits Visual Search', ->
        expect(search.vs).toBeTruthy()

    it 'can be run, returns results', ->
        search.run()
        expect(search.results).toBeDefined()
        expect(search.results.length).toBeTruthy()
        expect

    it 'has a ResultSet object', ->
        search.run()
        expect(search.results instanceof arcs.collections.ResultSet).toBe true

    it 'will not error on an empty search', ->
        spyOn search, 'run'
        callback = jasmine.createSpy()
        search.run(error=callback)
        expect(callback).not.toHaveBeenCalled()
