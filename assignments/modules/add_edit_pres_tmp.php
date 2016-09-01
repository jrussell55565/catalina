<?php if(!isset($RUN)) { exit(); } ?>
<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/adapters/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
<?php echo $val->DrowJsArrays(); ?>

<form method="post" name="form1" >

<table >
    <tr>
        <td width="150px"><?php echo NAME ?> : </td>
        <td><input class="form-control" type="text" id="txtName" name="txtName"  value="<?php echo util::GetData("txtName") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo DESC ?> : </td>
        <td><input class="form-control" type="text" id="txtDesc" name="txtDesc" value="<?php echo util::GetData("txtDesc") ?>" /></td>
    </tr>
 
    <tr>
        <td valign="top">
            <?php echo PRES_TEXT ?> :
        </td>
        <td >
            <textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10" ><?php echo $txtPText ?></textarea>
        </td>
    </tr>
 
    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input class="btn green" type="submit" name="btnSave" value="<?php echo SAVE ?>" id="btnSave" onclick="return validate();"  />
            <input class="btn green" type="button" name="btnCancel" value="<?php echo CANCEL ?>" id="btnCancel" onclick="javascript:window.location.href='?module=pres_list'" />
        </td>
    </tr>
</table>

<script language=javascript>
	var editor = CKEDITOR.replace('editor1',
        {

        filebrowserBrowseUrl: 'ckeditor/kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: 'ckeditor/kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: 'ckeditor/kcfinder/browse.php?type=flash' ,
          width: "900px",
        height: "400px"

        });
</script>  
</form>
