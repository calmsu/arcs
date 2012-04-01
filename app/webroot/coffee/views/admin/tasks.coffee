# tasks.coffee
# ------------
arcs.views.admin ?= {}
class arcs.views.admin.Tasks extends Backbone.View

  TASK_STATUSES:
    done    : 0
    pending : 1
    error   : 2

  initialize: ->
    @collection.on 'add remove change sync', @render, @
    @filterTasks()

  events:
    'keyup #filter-input': 'filterTasks'

  filterTasks: ->
    val = @$('#filter-input').val()
    val = @TASK_STATUSES[val] if @TASK_STATUSES[val]?
    @filter = (new RegExp(val, 'i'))
    @render()

  render: ->
    filtered = @collection.filter (m) =>
      m.get('status').match(@filter) or m.get('job').match(@filter)
    @$('#tasks').html arcs.tmpl 'admin/tasks', 
      tasks: (m.toJSON() for m in filtered)
    @
