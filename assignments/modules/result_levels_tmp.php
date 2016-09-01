<?php if(!isset($RUN)) { exit(); } ?>
<div id="div_search"><?php echo $search_html ?></div>
<div id="div_grid"><?php echo $grid_html ?></div>
    <br>
    <hr />    
    <a class="btn btn-primary" href="?module=add_edit_level_template&t_id=<?php echo $ID ?>"><?php echo NEW_LEVEL_TEMP ?></a>
    
<br><br>
    <a href="?module=qresult_templates"><?php echo BACK ?></a>