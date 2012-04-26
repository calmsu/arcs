<style>
.cake-sql-log {
  background:#fff;
  font-family:monospace;
  display:none;
  position:fixed;
  top:-20px;
  width:100%;
  z-index:1041;
}
</style>
<?php echo $this->element('sql_dump') ?>
<div class="modal-backdrop" style="display:none"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $('.cake-sql-log').addClass('table table-striped table-bordered');
    $(document).keydown(function(e) {
      if (e.which != 117) return;
      $('.cake-sql-log').toggle();
      $('.modal-backdrop').toggle();
    });
  });
</script>
