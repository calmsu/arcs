describe 'arcs', ->

  it 'can log', ->
    expect(arcs.log).toBeDefined()

  it 'can bind events', ->
    triggered = false
    arcs.bind 'testBind', =>
      triggered = true
    arcs.trigger 'testBind'
    expect(triggered).toBe true
