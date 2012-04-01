# tasks.coffee
# ------------
arcs.views.admin ?= {}
class arcs.views.admin.Tasks extends Backbone.View

  TASK_STATUSES:
    '0': 'done'
    '1': 'pending'
    '2': 'error'

  initialize: ->
    @collection.on 'add remove change sync', @render, @
    @render()

  render: ->
    @$('#tasks').html arcs.tmpl 'admin/tasks', 
      tasks: @collection.toJSON()
    @
