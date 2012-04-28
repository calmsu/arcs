# batch_edit_modal.coffee
# -----------------------
# Extends the base Modal to mix in the 'Apply to all' checkboxes.
class arcs.views.BatchEditModal extends arcs.views.Modal

  # Add checkbox checking.
  initialize: ->
    super()
    # On keydown of an input, find the corresponding 'Apply to all' checkbox 
    # and check it, unless the input is empty.
    @$('input[type=text][id^=modal]').change (e) => 
      @_checkBox $ e.currentTarget
    @$('input[type=text][id^=modal]').keyup (e) => 
      @_checkBox $ e.currentTarget
    @$('select[id^=modal]').change (e) =>
      @_checkBox $ e.currentTarget

  _checkBox: ($el) ->
    [id, name] = $el.attr('id').match /modal-([\w-]+)-input/
    ckbox = $("input#modal-#{name}-checkbox")
    return ckbox.prop('checked', false) unless $el.val()
    ckbox.prop('checked', true)

  # Overrides `getValues` method to only return inputs with corresponding 
  # checked checkboxes..
  getValues: ->
    values = {}
    for name of @options.inputs
      if @$("input#modal-#{name}-checkbox").is(':checked')
        values[name] = @$("#modal-#{name}-input").val()
    values
