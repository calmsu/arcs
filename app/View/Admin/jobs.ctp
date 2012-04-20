<?php echo $this->element('admin_nav', array('active' => 'jobs')) ?>
<div id="admin-jobs">
    <input id="filter-input" type="text" name="filter" placeholder="Filter jobs..."/>
    &nbsp;Last updated <strong><span id="time">just now</span></strong>
    &nbsp;Auto-update? <input type="checkbox" id="auto-update"/>
    <div id="jobs" style="max-height:80%; overflow:auto;"></div>
</div>
<script type="text/javascript">
  arcs.adminView = new arcs.views.admin.Jobs({
      el: $('#admin-jobs'),
      collection: new arcs.collections.JobList(<?php echo json_encode($jobs) ?>)
  });
</script>
