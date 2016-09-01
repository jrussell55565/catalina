<?php if(!isset($RUN)) { exit(); } ?>
<div id="div_grid"><?php echo $grid_html ?></div>
<br>
    <hr />

<?php if(access::has("add_page")) { ?>
<a class="btn btn-primary" href="?module=add_page<?php echo $id_url ?>"><?php echo ADD_PAGE ?></a>
<?php } ?>
