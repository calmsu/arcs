<?php echo $this->element('admin_nav', array('active' => 'flags')) ?>
<div id="admin-flags">
    <div id="flags"></div>
</div>
<script type="text/javascript">
  arcs.adminView = new arcs.views.admin.Flags({
      el: $('#admin-flags'),
      collection: new arcs.collections.FlagList(<?php echo json_encode($flags) ?>)
  });
</script>
