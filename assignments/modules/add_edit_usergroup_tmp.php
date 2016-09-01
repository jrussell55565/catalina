<?php if(!isset($RUN)) { exit(); } ?>
<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>
<link rel="stylesheet" type="text/css" href="lib2/datepicker2/jquery.datetimepicker.css"/>
<?php echo $val->DrowJsArrays(); ?>

<form method="post" name="form1" enctype="multipart/form-data">

<table >
    <tr>
        <td width="250px"><?php echo GROUP_NAME ?> : </td>
        <td><input class='form-control' type="text" id="txtName" name="txtName"  value="<?php echo util::GetData("txtName") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo GROUP_DESC ?> : </td>
        <td><input class='form-control' type="text" id="txtDesc" name="txtDesc" value="<?php echo util::GetData("txtDesc") ?>" /></td>
    </tr>
    <tr>
        <td><?php echo DEFAULT_FOR_NEW_USERS ?> : </td>
        <td><input class='els' type="checkbox" id="chkDefault" name="chkDefault" <?php echo util::GetData("chkDefault") ?> /></td>
    </tr>
     
    <tr >
        <td><?php echo L_START_DATE ?> : </td>
        <td><input class="form-control" value="<?php echo util::GetData("txtStartDate") ?>"  type="text"  id="datetimepicker_start" name="datetimepicker_start" /></td>
    </tr>   
    
     <tr >
        <td><?php echo L_STUDY_YEARS ?> : </td>
        <td><input class='form-control' onkeypress="return onlyNumbers(event)" type="text" id="txtYears" name="txtYears" data-toggle="tooltip" data-placement="top" title='<?php echo L_ENTER_ZERO_COURSE ?>'  value="<?php echo util::GetData("txtSTYears") ?>" />  </td>
    </tr>
    
     <tr >
        <td><?php echo L_SHOW_INLIST ?> : </td>
        <td><input class='els' type="checkbox" id="chkShowInList" name="chkShowInList" <?php echo util::GetData("chkShowInList") ?> /></td>
    </tr>
    
    <tr>
        <td><br></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input class="btn green" type="submit" name="btnSave" onclick="return validate();" value="<?php echo SAVE ?>" id="btnSave"  />
            <input class="btn green" type="button" name="btnCancel" value="<?php echo CANCEL ?>" id="btnCancel" onclick="javascript:window.location.href='?module=user_groups'" />
        </td>
    </tr>
</table>
    
</form>

<script type="text/javascript" src="lib2/datepicker2/jquery.datetimepicker.js"></script>
<script type="text/javascript">    
//$('#datetimepicker').datetimepicker() .datetimepicker({value:'2015/04/15',step:10});

$('#datetimepicker_start').datetimepicker({    
       format:'Y/m/d',
	formatDate:'Y/m/d',
       dayOfWeekStart : 1 ,
       timepicker:false
});
</script>

<script language='javascript'>    
    $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

</script>
