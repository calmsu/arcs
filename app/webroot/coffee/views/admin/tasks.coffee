# tasks.coffee
# ------------
arcs.views.admin ?= {}
class arcs.views.admin.Tasks extends Backbone.View

  TASK_STATUSES:
    done    : 0
    pending : 1
    error   : 2

  initialize: ->
    @collection.on 'add remove change reset sync', @render, @
    @collection.url = arcs.baseURL + 'tasks'
    @filterTasks()
    @lastUpdated = new Date()
    @autoUpdate = false
    setInterval _.bind(@render, @), 5000
    setInterval _.bind(@update, @), 15000

  events:
    'keyup #filter-input': 'filterTasks'
    'change #auto-update': 'setUpdate'
    'click #rerun-btn'   : 'rerunTask'
    'click #delete-btn'  : 'deleteTask'

  rerunTask: (e) ->
    task = @collection.get $(e.currentTarget).data('id')
    arcs.confirm "Are you sure you want to re-run this task?", 
      "Task <b>#{task.id}</b> will be queued.", =>
        task.set 'status', '1'
        task.save
          success: =>
            arcs.notify "Task successfully queued."

  deleteTask: (e) ->
    task = @collection.get $(e.currentTarget).data('id')
    arcs.confirm "Are you sure you want to delete this task?", 
      "Task <b>#{task.id}</b> will be deleted.", =>
        task.destroy
          success: =>
            arcs.notify "Task successfully deleted."

  filterTasks: ->
    val = @$('#filter-input').val()
    val = @TASK_STATUSES[val] if @TASK_STATUSES[val]?
    @filter = (new RegExp(val, 'i'))
    @render()

  setUpdate: ->
    @autoUpdate = !@autoUpdate

  update: ->
    return unless @autoUpdate
    @collection.fetch()
    @lastUpdated = new Date()

  render: ->
    filtered = @collection.filter (m) =>
      m.get('status').match(@filter) or m.get('job').match(@filter)
    @$('#tasks').html arcs.tmpl 'admin/tasks', 
      tasks: (m.toJSON() for m in filtered)
    @$('#time').html relativeDate @lastUpdated
    @
