<?php if(!isset($RUN)) { exit(); } ?>

<script language="javascript">
    function ProcessCommand(command)
    {                
        if(command=="delete")
        {
            var confirmed = confirm('<?php echo ARE_YOU_SURE ?>');
            if(confirmed == false) return;
        }        
        var myarr = grd_get_checkboxes(document.getElementById("form1"),"chk_qb","");       
        var branch_id = $("#btnChangeBrn").val();        
         $.post("<?php echo $url ?>", {  ajax: "yes", pcommand:"yes" , chkboxes : myarr , command : command, branch_id:branch_id },
         function(data){                
             document.getElementById('div_grid').innerHTML=data;
             //$.sticky("<?php echo DONE ?>", {autoclose : 2000, position: "top-right", type: "st-error" });
             toastr.info('<?php echo DONE ?>', '<?php echo DONE ?>');
        });
    }
    
    function ChangeBranch()
    {
        if($("#btnChangeBrn").val()!="-1")
        {
            ProcessCommand("change_branch");
            $("#btnChangeBrn").val("-1");
        }
    }
    
    
</script>

<div id="div_search"><?php echo $search_html ?></div>
<table style="width:100%"><tr><td>
   <table  border="0" cellpadding="3" align="right" style="display:<?php echo $hide_mobile ?>">
                    <tr>                   
                        <?php if(access::has("quiz_change_brn")) { ?>
                        <td >
                                <select class="form-control" onchange="ChangeBranch()" id="btnChangeBrn" name="btnChangeBrn"><option value="-1" ><?php echo CHANGE_BRANCH ?></option><?php echo $branch_options ?></select> 
                        </td>
                        <?php } ?>
                        <?php if(access::has("quiz_copy")) { ?>
                        <td valign="top">
                                &nbsp;<input class="btn green" onclick="ProcessCommand('create_copy')" type="button" value="<?php echo CREATE_COPY ?>" id="btnCopy" name="btnCopy" class="btn" /> 
                        </td>
                        <?php } ?>
                        <?php if(access::has("delete_quiz")) { ?>
                        <td valign="top">
                                &nbsp;<input class="btn green"  onclick="ProcessCommand('delete')" type="button" value="<?php echo DELETE ?>" id="btnDelete" name="btnDelete" class="btn" />
                        </td>
                        <?php } ?>
                    </tr>
    </table>
        </td></tr></table>
<form id="form1" name="form1">
<div id="div_grid"><?php echo $grid_html ?></div>
</form>
    <br>
    <hr />
    <?php if(access::has("add_quiz")) { ?>
    <a class="btn btn-primary" href="?module=add_edit_quiz"><?php echo NEW_QUIZ; ?></a>
    <?php } ?>
                 
