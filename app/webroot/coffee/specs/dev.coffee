describe 'dev', ->

    it "doesn't reload stylesheets by default", ->
        expect(arcs.dev.reload).toBe false
