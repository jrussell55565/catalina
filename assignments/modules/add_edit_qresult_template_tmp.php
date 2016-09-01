<?php if(!isset($RUN)) { exit(); } ?>
<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/adapters/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
<?php echo $val->DrowJsArrays(); ?>

<form method="post" name="form1" enctype="multipart/form-data">

<table >
    <tr>
        <td width="180px"><?php echo NAME ?> : </td>
        <td><input type="text" id="txtName" name="txtName"  value="<?php echo util::GetData("txtName") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo DESC ?> : </td>
        <td><input type="text" id="txtDesc" name="txtDesc" value="<?php echo util::GetData("txtDesc") ?>" /></td>
    </tr>
 
    
      <tr>
        <td>
            <?php echo SUCCESS_TEMPLATE ?>
        </td>
        <td>                  
            <textarea class="ckeditor" cols="80" id="txtSuccess" name="txtSuccess" rows="10" ><?php echo $txtSuccess ?></textarea>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo SUCCESS_LEVEL ?>
        </td>
        <td>                  
            <select id ="drpSLevel" name ="drpSLevel">
                <?php echo $slevel_options ?>
            </select>
        </td>
    </tr>
      <tr>
        <td>
            <?php echo SUCCESS_FACEBOOK_MESSAGE ?>
        </td>
        <td><input style="width:790px" type="text" id="txtSFBMessage" name="txtSFBMessage" value="<?php echo util::GetData("txtSFBMessage") ?>" />
        </td>
    </tr>
       <tr>
        <td>
            <?php echo SUCCESS_FACEBOOK_LINK_NAME ?>
        </td>
        <td><input style="width:790px" type="text" id="txtSFBLinkName" name="txtSFBLinkName" value="<?php echo util::GetData("txtSFBLinkName") ?>" />
        </td>
    </tr>
       <tr>
        <td>
            <?php echo SUCCESS_FACEBOOK_LINK ?>
        </td>
        <td><input style="width:790px" type="text" id="txtSFBLink" name="txtSFBLink" value="<?php echo util::GetData("txtSFBLink") ?>" />
        </td>
    </tr>
    
    <tr>
        <td></td>
        <td><input style="width:790px" type="text" readonly="true" value="[UserName],[Name],[Surname],[email],[url],[quiz_name],[start_date],[finish_date],[pass_score],[user_score],[level_name]"></td>
    </tr>
    
    <tr>
        <td>
            <?php echo UNSUCCESS_TEMPLATE ?>
        </td>
          <td>                  
              <textarea class="ckeditor" cols="80" id="txtUnsuccess" name="txtUnsuccess" rows="10" ><?php echo $txtUnsuccess ?></textarea>
        </td>
    </tr>
    
        <tr>
        <td>
            <?php echo UNSUCCESS_LEVEL ?>
        </td>
        <td>                  
            <select id ="drpULevel" name ="drpULevel">
                <?php echo $flevel_options ?>
            </select>
        </td>
    </tr>
    
          <tr>
        <td>
            <?php echo UNSUCCESS_FACEBOOK_MESSAGE ?>
        </td>
        <td><input style="width:790px" type="text" id="txtUSFBMessage" name="txtUSFBMessage" value="<?php echo util::GetData("txtUSFBMessage") ?>" />
        </td>
    </tr>
       <tr>
        <td>
            <?php echo UNSUCCESS_FACEBOOK_LINK_NAME ?>
        </td>
        <td><input style="width:790px" type="text" id="txtUSFBLinkName" name="txtUSFBLinkName" value="<?php echo util::GetData("txtUSFBLinkName") ?>" />
        </td>
    </tr>
       <tr>
        <td>
            <?php echo UNSUCCESS_FACEBOOK_LINK ?>
        </td>
        <td><input style="width:790px" type="text" id="txtUSFBLink" name="txtUSFBLink" value="<?php echo util::GetData("txtUSFBLink") ?>" />
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
	var editor = CKEDITOR.replace('txtUnsuccess',
        {

        filebrowserBrowseUrl: 'ckeditor/kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: 'ckeditor/kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: 'ckeditor/kcfinder/browse.php?type=flash'

        });
        
        var editor = CKEDITOR.replace('txtSuccess',
        {

        filebrowserBrowseUrl: 'ckeditor/kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: 'ckeditor/kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: 'ckeditor/kcfinder/browse.php?type=flash'

        });
</script>