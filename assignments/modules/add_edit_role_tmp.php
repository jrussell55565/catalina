<?php if(!isset($RUN)) { exit(); } ?>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
<?php echo $val->DrowJsArrays(); ?>

<form method="post" name="form1" enctype="multipart/form-data">

<table >
    <tr>
        <td width="150px"><?php echo ROLE_NAME ?> : </td>
        <td><input class='form-control' <?php echo $disabled ?> type="text" id="txtName" name="txtName"  value="<?php echo util::GetData("txtName") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo ROLE_DESC ?> : </td>
        <td><input <?php echo $disabled ?> type="text" class='form-control' id="txtDesc" name="txtDesc" value="<?php echo util::GetData("txtDesc") ?>" /></td>
    </tr>
 

 
    <tr>
        <td><br></td>
    </tr>
    <?php if($disabled=="") { ?>
    <tr>
        <td colspan="2" align="center">
            <input class="btn green" type="submit" name="btnSave" value="<?php echo SAVE ?>" id="btnSave" onclick="return validate();"  />
            <input class="btn green" type="button" name="btnCancel" value="<?php echo CANCEL ?>" id="btnCancel" onclick="javascript:window.location.href='?module=roles'" />
        </td>
    </tr>
    <?php } ?>
</table>
    
</form>
