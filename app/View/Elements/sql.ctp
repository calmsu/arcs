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
#sql-toggle {
  position:fixed;
  bottom:0px;
  right:0px;
  background:#ccc;
  cursor:pointer;
  padding:5px;
  border:1px solid #aaa;
  z-index:1041;
}
</style>
<?php echo $this->element('sql_dump') ?>
<div id="sql-toggle">+ SQL</div>
<div class="modal-backdrop" style="display:none"></div>
<script type="text/javascript">
  $(document).ready(function() {
    $('.cake-sql-log').addClass('table table-striped table-bordered');
    $('#sql-toggle').click(function() {
      $('.cake-sql-log').toggle();
      $('.modal-backdrop').toggle();
    });
  });
</script>
