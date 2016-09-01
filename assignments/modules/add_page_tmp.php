<?php if(!isset($RUN)) { exit(); } ?>
<script src="ckeditor/ckeditor.js"></script>
<script src="ckeditor/adapters/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>

<?php echo $val->DrowJsArrays(); ?>

<script language="javascript">
function drpPageType_OnChange()
{
    var page_type = $("#drpPageType").val();
    if(page_type=="1")
    {
        $("#tr1").show();
        $("#tr2").hide();
    }
    else
    {
        $("#tr1").hide();
        $("#tr2").show();  
    }
}
</script>

<form method="post" name="form1" >
<table>
	<tr>
		<td width="100px">
			<?php echo MENU_NAME ?> : 
		</td>
		<td>
			<input class="st_txtbox" type="text" id="txtName" name="txtName" value="<?php echo util::GetData("txtName") ?>" />
		</td>
	</tr>
	<tr>
		<td width="100px">
			<?php echo PRIORITY ?> : 
		</td>
		<td>
			<input class="st_txtbox" type="text" id="txtPriority" name="txtPriority" value="<?php echo util::GetData("txtPriority") ?>" />
		</td>
	</tr>
        <tr>
            <td><?php echo TYPE ?></td>
            <td><select id="drpPageType" name="drpPageType" onchange="drpPageType_OnChange()" >
                    <?php echo $page_type_options ?>
                </select></td>
        </tr>
	<tr id="tr1">
		<td valign=top>
			<?php echo PAGE_CONTENT ?> : 
		</td>
		<td>			
                    <textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10" ><?php echo $txtPagecontent ?></textarea>
		</td>
	</tr>
        <tr id="tr2">
		<td valign=top>
			<?php echo LINK ?> : 
		</td>
		<td>
			<input class="st_txtbox" type="text" id="txtURL" name="txtURL" value="<?php echo util::GetData("txtURL") ?>" />
		</td>
	</tr>
   <tr>
        <td colspan=2>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input style="width:100px" type="submit" id="btnSubmit" name="btnSubmit" value="<?php echo SAVE ?>" onclick="return validate();">
        <input type="button" style="width:100px" id="btnCancel" value="<?php echo CANCEL ?>" onclick="javascript:window.location.href='?module=cms&id=0'">
        </td>
    </tr>
</table>
</form>
<script language="javascript">
drpPageType_OnChange();
</script>

<script language=javascript>
	var editor = CKEDITOR.replace('editor1',
        {

        filebrowserBrowseUrl: 'ckeditor/kcfinder/browse.php?type=files',
        filebrowserImageBrowseUrl: 'ckeditor/kcfinder/browse.php?type=images',
        filebrowserFlashBrowseUrl: 'ckeditor/kcfinder/browse.php?type=flash'

        });
</script>
