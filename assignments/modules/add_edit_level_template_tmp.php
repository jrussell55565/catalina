<?php if(!isset($RUN)) { exit(); } ?>
<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/adapters/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>


<form method="post" name="form1" enctype="multipart/form-data">

<table >
    <tr>
        <td width="180px"><?php echo NAME ?> : </td>
        <td><input type="text" id="txtName" name="txtName"  value="<?php echo util::GetData("txtName") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo MIN_POINT ?> : </td>
        <td><input type="text" id="txtMinPoint" name="txtMinPoint" value="<?php echo util::GetData("txtMinPoint") ?>" /></td>
    </tr>
 <tr>
        <td><?php echo MAX_POINT ?> : </td>
        <td><input type="text" id="txtMaxPoint" name="txtMaxPoint" value="<?php echo util::GetData("txtMaxPoint") ?>" /></td>
    </tr>
    
      <tr>
        <td>
            <?php echo LEVEL_MESSAGE ?>
        </td>
        <td>                  
            <textarea class="ckeditor" cols="80" id="txtSuccess" name="txtSuccess" rows="10" ><?php echo $txtSuccess ?></textarea>
        </td>
    </tr>    
    
          <tr>
        <td>
            <?php echo LEVEL ?>
        </td>
        <td>                  
            <select id ="drpLevel" name ="drpLevel">
                <?php echo $level_options ?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td></td>
        <td><input style="width:790px" type="text" readonly="true" value="[UserName],[Name],[Surname],[email],[url],[quiz_name],[start_date],[finish_date],[pass_score],[user_score],[level_name]"></td>
    </tr>
    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input class="btn" type="submit" name="btnSave" value="<?php echo SAVE ?>" id="btnSave" onclick="return validate();"  />
            <input class="btn" type="button" name="btnCancel" value="<?php echo CANCEL ?>" id="btnCancel" onclick="javascript:window.location.href='?module=qresult_templates'" />
        </td>
    </tr>
</table>
    
</form>

<script language=javascript>
	        
        var editor = CKEDITOR.replace('txtSuccess',
        {

        filebrowserBrowseUrl: 'ckeditor/kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: 'ckeditor/kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: 'ckeditor/kcfinder/browse.php?type=flash'

        });
</script>