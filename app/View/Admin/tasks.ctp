<?php echo $this->element('admin_nav', array('active' => 'tasks')) ?>
<div id="admin-tasks">
    <div id="tasks"></div>
</div>
<script type="text/javascript">
  arcs.adminView = new arcs.views.admin.Tasks({
      el: $('#admin-tasks'),
      collection: new Backbone.Collection(<?php echo json_encode($tasks) ?>)
  });
</script>
