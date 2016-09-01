<?php if(!isset($RUN)) { exit(); } ?>
<div id="div_search"><?php echo $search_html ?></div>
<div id="div_grid"><?php echo $grid_html ?></div>
    <br>
    <hr />
    <?php if(access::has("add_results_template")) { ?>
    <a class="btn btn-primary" href="?module=add_edit_qresult_template"><?php echo NEW_QRESULT_TEMPLATE ?></a>
    <?php } ?>