<?php if(!isset($RUN)) { exit(); } ?>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
<?php echo $val->DrowJsArrays(); ?>

<form method="post" name="form1" enctype="multipart/form-data">

<table >
    <tr>
        <td width="150px"><?php echo BRANCH_NAME ?> : </td>
        <td><input class='form-control' type="text" id="txtName" name="txtName"  value="<?php echo util::GetData("txtName") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo BRANCH_DESC ?> : </td>
        <td><input class='form-control' type="text" id="txtDesc" name="txtDesc" value="<?php echo util::GetData("txtDesc") ?>" /></td>
    </tr>
    
     <tr style="display:<?php echo $self_display ?>">
        <td><?php echo ALLOW_SELF_REG ?> : </td>
        <td><input class='els' type="checkbox" id="chkSelfReg" name="chkSelfReg" <?php echo util::GetData("chkSelfReg") ?> /></td>
    </tr>
 

 
    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input class="btn green" type="submit" name="btnSave" value="<?php echo SAVE ?>" id="btnSave" onclick="return validate();"  />
            <input class="btn green" type="button" name="btnCancel" value="<?php echo CANCEL ?>" id="btnCancel" onclick="javascript:window.location.href='?module=branches'" />
        </td>
    </tr>
</table>
    
</form>
