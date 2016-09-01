<?php if(!isset($RUN)) { exit(); } ?>

<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>

<?php echo $val->DrowJsArrays(); ?>


<form method="post" name="form1" >
<table class="desc_text" border="0" width="100%">
    <tr>
        <td width="120px">
            <?php echo CAT ?> :
        </td>
        <td>
            <select class="form-control input-medium" id="drpCats" name="drpCats" class="st_txtbox">
                <?php echo $cat_options ?>
            </select>
        </td>
    </tr>
    <tr>
        <td><?php echo NAME ?> :</td>
        <td><input class="form-control input-medium" type="text" id="txtName" name="txtName" value="<?php echo util::GetData("txtName") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo DESC ?> :</td>
        <td><input class="form-control input-medium" type="text" id="txtDesc" name="txtDesc" value="<?php echo util::GetData("txtDesc") ?>" /></td>
    </tr>
    <tr>
        <td>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="2" align="left"><input class="btn green" style="width:100px" type="submit" id="btnSubmit" name="btnSubmit" value="<?php echo SAVE ?>" onclick="return validate();" />
        <input class="btn green" type="button" style="width:100px" id="btnCancel" value="<?php echo CANCEL ?>" onclick="javascript:window.location.href='?module=quizzes'" />
        </td>
    </tr>
</table>
</form>
