<?php if(!isset($RUN)) { exit(); } ?>
<div id="div_search"><?php echo $search_html ?></div>
<div id="div_grid"><?php echo $grid_html ?></div>
    
    
    <hr />
    
<?php if(access::has("add_local_user")) { ?>
    <a class='btn btn-primary' href="?module=add_edit_user"><?php echo NEW_USER ?></a>
<?php } ?>

    
<script language='javascript'>    
    $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>

