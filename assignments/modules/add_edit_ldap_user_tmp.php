<?php if(!isset($RUN)) { exit(); } ?>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
<?php echo $val->DrowJsArrays(); ?>

<form method="post" name="form1" enctype="multipart/form-data">

<table >
    <tr>
        <td width="200px"><?php echo USER_NAME ?> : </td>
        <td><input class='form-control' type="text" id="txtName"  name="txtName"  value="<?php echo util::GetData("txtName") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo USER_SURNAME ?> : </td>
        <td><input class='form-control' type="text" id="txtSurname"   name="txtSurname" value="<?php echo util::GetData("txtSurname") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo LOGIN ?> : </td>
        <td><input class='form-control' type="text" id="txtLogin"   name="txtLogin" value="<?php echo util::GetData("txtLogin") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo EMAIL ?> : </td>
        <td><input class='form-control' type="text" id="txtEmail" <?php echo $login_disabled ?> name="txtEmail" value="<?php echo util::GetData("txtEmail") ?>"  /></td>
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
        <td><?php echo BRANCH ?> :</td>
        <td>
            <select class='form-control' id="drpBranches" name="drpBranches">
                <?php echo $branch_options ?>
            </select>
        </td>
    </tr>
    <tr >
        <td><?php echo GROUP_NAME ?> :</td>
        <td>
            <select class='form-control' id="drpGroup" name="drpGroup">
                <?php echo $user_group_options ?>
            </select>
        </td>
    </tr>
	
        <tr>
		<td align=left><?php echo COMMENTS ?> : </td>
		<td><input class='form-control' type=text name=txtComments value="<?php echo util::GetData("txtComments") ?>" /> </td>
	</tr>
<tr>
		<td align=left><?php echo R_DISABLED ?> : </td>
		<td><input class='els' type=checkbox name=chkDisabled <?php echo util::GetData("chkDisabled") ?> ></td>
	</tr>
    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input class="btn green" type="submit" name="btnSave" value="<?php echo SAVE ?>" id="btnSave" onclick="return checkform();" />
            <input class="btn green" type="button" name="btnCancel" value="<?php echo CANCEL ?>" id="btnCancel" onclick="javascript:window.location.href='?module=ldap_users'" />
        </td>
    </tr>
</table>
    <input type="hidden" id="hdnMode" value="<?php echo $mode ?>">
</form>
<script language="javascript">


function checkform()
{
    var mode = document.getElementById('hdnMode').value;

    if(mode=="edit")
    {
        return validate();
    }
    else
    {
        var email= document.getElementById('txtEmail').value
        var status=validate();
        if(status)
        {
             $.post("?module=add_edit_ldap_user", { email_to_check: email, ajax: "yes" },
             function(data){
                 if(data=="0")
                 {
                     return true;
                 }
                 else
                 {
                    alert('<?php echo EMAIL_ALREADY_EXISTS ?>');
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
