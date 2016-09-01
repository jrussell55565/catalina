<?php if(!isset($RUN)) { exit(); } ?>

<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>

<?php echo $val->DrowJsArrays(); ?>

<form method="post" enctype='multipart/form-data'>
    <?php echo $c->chtml ?>
</form>
