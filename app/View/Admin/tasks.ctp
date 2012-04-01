<?php echo $this->element('admin_nav', array('active' => 'tasks')) ?>
<div id="admin-tasks">
    <input id="filter-input" name="filter" placeholder="Filter tasks..."/>
    <div id="tasks" style="max-height:80%; overflow:auto;"></div>
</div>
<script type="text/javascript">
  arcs.adminView = new arcs.views.admin.Tasks({
      el: $('#admin-tasks'),
      collection: new Backbone.Collection(<?php echo json_encode($tasks) ?>)
  });
</script>
