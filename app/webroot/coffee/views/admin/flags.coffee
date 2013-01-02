# flags.coffee
# ------------
# Manage flags.
arcs.views.admin ?= {}
class arcs.views.admin.Flags extends Backbone.View

  initialize: ->
    @collection.on 'add remove change sync', @render, @
    @render()

  render: ->
    @$('#flags').html arcs.tmpl 'admin/flags', 
      flags: @collection.toJSON()
    @
