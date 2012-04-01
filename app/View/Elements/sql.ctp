<style>
.cake-sql-log {
  font-family:monospace;
  display:none;
  position:fixed;
  bottom:0px;
  width:100%;
}
#sql-toggle {
  position:fixed;
  bottom:0px;
  right:0px;
  background:#ccc;
  cursor:pointer;
  padding:5px;
  border:1px solid #aaa;
}
</style>
<?php echo $this->element('sql_dump') ?>
<div id="sql-toggle">+ SQL</div>
<script type="text/javascript">
  $(document).ready(function() {
    $('.cake-sql-log').addClass('table table-striped table-bordered');
    $('#sql-toggle').click(function() {
      $('.cake-sql-log').toggle();
    });
  });
</script>
