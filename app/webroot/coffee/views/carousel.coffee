# carousel.coffee
# ---------------
class arcs.views.Carousel extends Backbone.View

  options:
    index: 0
    nthumbs: 30 

  initialize: ->
    arcs.on 'arcs:indexchange', @slideTo, @
    arcs.on 'arcs:indexchange', @setSelected, @

    @render()

    @$el.parent().elastislide
      imageW: 100
      onClick: ($item) ->
        arcs.trigger 'arcs:indexchange', $item.index(), noSlide: true
      onSlide: (first, last) =>
        unless _.isNaN(last)
          if last > @options.nthumbs - 10
            @_addThumbs()

    @slideTo @options.index

  events:
    'click li': 'onClick'

  onClick: (e) ->
    arcs.trigger 'arcs:indexchange', $(e.target).parent().index(), noSlide: true

  slideTo: (index, options={}) ->
    if @$('li').length < index
      @_addThumbs index + 1 - @$('li').length
    @$el.parent().elastislide('slideToIndex', index) unless options.noSlide

  setSelected: (index) ->
    @$('.thumb.selected').removeClass 'selected'
    img = @$('.thumb').get index
    $(img).addClass 'selected'

  _addThumbs: (n=30) ->
    additions = @collection.models.slice(@options.nthumbs, @options.nthumbs + n)
    $thumbs = $ @_tmpl 
      resources: (m.toJSON() for m in additions)
      offset: @options.nthumbs
    @$el.parent().elastislide 'add', $thumbs.filter 'li'
    @options.nthumbs += n
    @delegateEvents()

  render: ->
    @$('ul').html @_tmpl 
      resources: _.first(@collection.toJSON(), @options.nthumbs)
      offset: 0

  _tmpl: (data) -> arcs.tmpl 'resource/carousel', data, _.template
