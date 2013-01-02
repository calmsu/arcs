# jobs.coffee
# ------------
# Monitor and manage our job queue.

arcs.views.admin ?= {}
class arcs.views.admin.Jobs extends Backbone.View

  JOB_STATUSES:
    done        : 0
    pending     : 1
    failing     : 2
    failed      : 3
    interrupted : 4

  options:
    autoUpdate: false
    updateEvery: 15000

  initialize: ->
    @collection.on 'add remove change reset sync', @render, @
    @collection.url = arcs.baseURL + 'jobs'
    @filterJobs()
    @lastUpdated = new Date()
    setInterval _.bind(@render, @), 5000
    setInterval _.bind(@update, @), @options.updateEvery

  events:
    'keyup #filter-input': 'filterJobs'
    'change #auto-update': 'setUpdate'
    'click #retry-btn'   : 'retryJob'
    'click #delete-btn'  : 'deleteJob'
    'click #release-btn' : 'releaseJob'
    'click #show-btn'    : 'showJob'

  showJob: (e) ->
    job = @collection.get $(e.currentTarget).data('id')
    arcs.prompt "Job #{job.id}",
      arcs.tmpl 'admin/show_job', job.toJSON()

  retryJob: (e) ->
    job = @collection.get $(e.currentTarget).data('id')
    arcs.confirm "Are you sure you want to retry this job?", 
      "Job <b>#{job.id}</b> will be set to <b>pending</b>.", =>
        job.set 
          status: '1'
          failed_at: null
          error: null
        arcs.loader.show()
        job.save {}
          success: ->
            arcs.loader.hide()

  deleteJob: (e) ->
    job = @collection.get $(e.currentTarget).data('id')
    arcs.confirm "Are you sure you want to delete this job?", 
      "Job <b>#{job.id}</b> will be deleted.", =>
        arcs.loader.show()
        job.destroy
          success: ->
            arcs.loader.hide()

  releaseJob: (e) ->
    job = @collection.get $(e.currentTarget).data('id')
    arcs.confirm "Are you sure you want to release this job?", 
      "Job <b>#{job.id}</b> will be released.", =>
        arcs.loader.show()
        job.save {locked_by: null, locked_at: null}
          success: ->
            arcs.loader.hide()

  filterJobs: ->
    val = @$('#filter-input').val()
    val = @JOB_STATUSES[val] if @JOB_STATUSES[val]?
    @filter = (new RegExp(val, 'i'))
    @render()

  setUpdate: ->
    @options.autoUpdate = !@options.autoUpdate

  update: ->
    return unless @options.autoUpdate
    @collection.fetch
      success: =>
        @lastUpdated = new Date()

  render: ->
    filtered = @collection.filter (m) =>
      m.get('status').match(@filter) or m.get('name').match(@filter)
    @$('#jobs').html arcs.tmpl 'admin/jobs', 
      jobs: (m.toJSON() for m in filtered)
    @$('#time').html relativeDate @lastUpdated
    $('.popover').hide()
    @$('.has-error').popover()
    @
