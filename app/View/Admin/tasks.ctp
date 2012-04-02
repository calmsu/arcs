<?php echo $this->element('admin_nav', array('active' => 'tasks')) ?>
<div id="admin-tasks">
    <input id="filter-input" name="filter" placeholder="Filter tasks..."/>
    &nbsp;Last updated <strong><span id="time">just now</span></strong>
    &nbsp;Auto-update? <input type="checkbox" id="auto-update"/>
    <div id="tasks" style="max-height:80%; overflow:auto;"></div>
</div>
<script type="text/javascript">
  arcs.adminView = new arcs.views.admin.Tasks({
      el: $('#admin-tasks'),
      collection: new arcs.collections.TaskList(<?php echo json_encode($tasks) ?>)
  });
</script>
