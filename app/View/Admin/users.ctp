<?php echo $this->element('admin_nav', array('active' => 'users')) ?>
<div id="admin-users">
    <button id="new-btn" class="btn">
        <i class="icon-plus"></i> New User
    </button>
    <button id="invite-btn" class="btn">
        <i class="icon-gift"></i> Send Invite
    </button>
    <br><br>
    <div id="users"></div>
</div>
<script type="text/javascript">
  arcs.adminView = new arcs.views.admin.Users({
      el: $('#admin-users'),
      collection: new arcs.collections.UserList(<?php echo json_encode($users) ?>)
  });
</script>
