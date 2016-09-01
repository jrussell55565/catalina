<?php if(!isset($RUN)) { exit(); } ?>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
<?php echo $val->DrowJsArrays(); ?>

<form method="post" name="form1" enctype="multipart/form-data">

<table >
    <tr>
        <td width="200px"><?php echo USER_NAME ?> : </td>
        <td><input class='form-control' type="text" id="txtName" name="txtName"  value="<?php echo util::GetData("txtName") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo USER_SURNAME ?> : </td>
        <td><input class='form-control' type="text" id="txtSurname" name="txtSurname" value="<?php echo util::GetData("txtSurname") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo EMAIL ?> : </td>
        <td><input class='form-control' type="text" id="txtEmail" name="txtEmail" value="<?php echo util::GetData("txtEmail") ?>"  /></td>
    </tr>
    <tr>
        <td><?php echo USER_TYPE ?> : </td>
        <td>
            <select class='form-control' id="drpUserType" name="drpUserType">
                <?php echo $user_type_options ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php echo LOGIN ?> : </td>
        <td><input class='form-control' <?php echo $login_disabled ?> type="text" id="txtLogin" name="txtLogin" value="<?php echo util::GetData("txtLogin") ?>" /></td>
    </tr>
     <tr>
        <td><?php echo PASSWORD ?> : </td>
        <td>
            <table border="0" cellpadding="2" cellspacing="2">
                <tr valign="center">
                    <td valign="center"><input class='form-control input-small' style="display:<?php echo $psw_display ?>" type="text" id="txtPassword" name="txtPassword" value="<?php echo util::GetData("txtPasswordValue") ?>" /></td>
                    <td valign="center"><label style="display:<?php echo $pswlbl_display ?>" id="lblPsw">******** </label></td>
                    <td valign="top"><input class='els' type="checkbox" name="chkEdit" id="chkEdit" onclick="ProcessPasswordField()" style="display:<?php echo $pswlbl_display ?>"  /></td>
                    <td valign="middle"><label style="display:<?php echo $pswlbl_display ?>" for="chkEdit"><?php echo EDIT ?></label></td>
                </tr>
            </table>    
        </td>
    </tr>
    <tr>
        <td><?php echo COUNTRY ?> :</td>
        <td>
            <select class='form-control' id="drpCountries" name="drpCountries">
                <?php echo $country_options ?>
            </select>
        </td>
    </tr>
       <tr>
        <td><?php echo BRANCH ?> :</td>
        <td>
            <select class='form-control' id="drpBranches" name="drpBranches">
                <?php echo $branch_options ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php echo GROUP_NAME ?> :</td>
        <td>
            <select class='form-control' id="drpGroup" name="drpGroup">
                <?php echo $user_group_options ?>
            </select>
        </td>
    </tr>
   <tr>
		<td align=left><?php echo R_ADDRESS ?> : </td>
		<td><input class='form-control'  type=text name=txtAddr value="<?php echo util::GetData("txtAddr") ?>" /></td>
	</tr>
	<tr>
		<td align=left><?php echo R_PHONE ?> : </td>
		<td><input class='form-control'  type=text name=txtPhone value="<?php echo util::GetData("txtPhone") ?>" /> </td>
	</tr>	
        <tr>
		<td align=left><?php echo COMMENTS ?> : </td>
		<td><input class='form-control'  type=text name=txtComments value="<?php echo util::GetData("txtComments") ?>" /> </td>
	</tr>
<tr>
		<td align=left><?php echo R_APPROVED ?> : </td>
		<td><input class=els type=checkbox name=chkApproved <?php echo util::GetData("chkApproved") ?>></td>
	</tr>
<tr>
		<td align=left><?php echo R_DISABLED ?> : </td>
		<td><input class=els type=checkbox name=chkDisabled <?php echo util::GetData("chkDisabled") ?> ></td>
	</tr>
        <tr>
		<td align=left><?php echo PHOTO ?> : </td>
		<td>
		<?php echo $photo_thumb ; ?><br />
<input name="userphoto" accept="image/jpeg" type="file"> </td>
	</tr>
    <tr>
        <td><br></td>
    </tr>
    <tr style="display:<?php echo $buttons_display ?>">
        <td colspan="2" align="center">
            <input type='hidden' name='btnSave' value='save' />
            <input class="btn green" type="button" name="btnSaveB" value="<?php echo SAVE ?>" id="btnSave" onclick="return checkform();" />
            <input class="btn green" type="button" name="btnCancel" value="<?php echo CANCEL ?>" id="btnCancel" onclick="javascript:window.location.href='?module=local_users'" />
        </td>
    </tr>
</table>
    <input type="hidden" id="hdnMode" value="<?php echo $mode ?>">
</form>
<script language="javascript">
function ProcessPasswordField()
{
    var checked = document.getElementById('chkEdit').checked ;
    if(checked)
    {
        document.getElementById('txtPassword').style.display="";
        document.getElementById('txtPassword').value="";
        document.getElementById('lblPsw').style.display="none";
    }
    else
    {
        document.getElementById('txtPassword').style.display="none";
        document.getElementById('txtPassword').value="********";
        document.getElementById('lblPsw').style.display="";
    }
}

function checkform()
{
    var mode = document.getElementById('hdnMode').value;

    if(mode=="edit")
    {
        var status=validate();
        if(status)
        {
            document.forms["form1"].submit();
        }
        else
        {
            return false;
        }
    }
    else
    {
        var user_name= document.getElementById('txtLogin').value
        var status=validate();
        if(status)
        {
             $.post("?module=add_edit_user", { login_to_check: user_name, ajax: "yes" },
             function(data){                        
                 if(data=="0" || data==0)
                 {
                     document.forms["form1"].submit();
                    //document.getElementById('btnSave').click();
                 }
                 else
                 {
                    alert('<?php echo LOGIN_ALREADY_EXISTS ?>');
                    return false;
                 }

            });
        }
        else
        {
            return false;
        }
    }
}
</script>
