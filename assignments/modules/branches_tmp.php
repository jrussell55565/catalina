<?php if(!isset($RUN)) { exit(); } ?>
<div id="div_search"><?php echo $search_html ?></div>
<div id="div_grid"><?php echo $grid_html ?></div>
    <br>
    <hr />
    <?php if(access::has("add_branch")) { ?>
    <a class="btn btn-primary" href="?module=add_edit_branch"><?php echo NEW_BRANCH ?></a>
    <?php } ?>