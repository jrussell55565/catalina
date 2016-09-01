<?php if(!isset($RUN)) { exit(); } ?>
<div id="div_search"><?php echo $search_html ?></div>
<div id="div_grid"><?php echo $grid_html ?></div>
    <br>
    <hr />
    
<?php if(access::has("add_fb_user")) { ?>
    <a class="btn btn-primary" href="?module=add_edit_fb_user"><?php echo NEW_USER ?></a>
<?php } ?>