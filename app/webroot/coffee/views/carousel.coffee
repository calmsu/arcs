# carousel.coffee
# ---------------
# View for the thumbnail carousel.
#
# The view is responsible for watching our global 'indexchange' event and
# sliding the carousel and activating thumbnails as necessary. 
#
# By default the Carousel view doesn't render all the thumbnails it is given,
# only 30. It's smart enough to fetch the rest when they're needed.
class arcs.views.Carousel extends Backbone.View

  options:
    index: 0
    nthumbs: 30 

  initialize: ->
    # Bind to the indexchange event.
    arcs.on 'arcs:indexchange', @slideTo, @
    arcs.on 'arcs:indexchange', @setSelected, @

    @render()

    # Setup the elastislide plugin on the parent wrapper div.
    @$el.elastislide
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
    @$el.elastislide('slideToIndex', index) unless options.noSlide

  setSelected: (index) ->
    @$('.thumb.selected').removeClass 'selected'
    img = @$('.thumb').get index
    $(img).addClass 'selected'

  _addThumbs: (n=30) ->
    additions = @collection.models.slice(@options.nthumbs, @options.nthumbs + n)
    $thumbs = $ @_tmpl 
      resources: (m.toJSON() for m in additions)
      offset: @options.nthumbs
    @$el.elastislide 'add', $thumbs.filter 'li'
    @options.nthumbs += n
    @delegateEvents()

  render: ->
    @$('ul').html @_tmpl 
      resources: _.first(@collection.toJSON(), @options.nthumbs)
      offset: 0

  _tmpl: (data) -> arcs.tmpl 'resource/carousel', data, _.template
