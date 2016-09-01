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
        
        if(myarr.length<1)
        {
            alert('<?php echo L_SELECT_ITEM ?>');
            return;
        }
        
        var branch_id = $("#btnChangeBrn").val();
        var quiz_id = $("#btnChangeQuiz").val(); 
         $.post("<?php echo $url ?>", {  ajax: "yes", pcommand:"yes" , chkboxes : myarr , command : command, branch_id:branch_id, quiz_id : quiz_id },
         function(data){                
             document.getElementById('div_grid').innerHTML=data;
          //   $.sticky("<?php echo DONE ?>", {autoclose : 2000, position: "top-right", type: "st-error" }); 
			 toastr.info('<?php echo DONE ?>', '<?php echo DONE ?>');
        });
    }
    
    function UpdateChilds()
    {
        if(confirm('<?php echo ARE_YOU_SURE ?>')==false) return;
        ProcessCommand('update_childs');
    }
    
    function ChangeBranch()
    {
        if($("#btnChangeBrn").val()!="-1")
        {
            ProcessCommand("change_branch");
            $("#btnChangeBrn").val("-1");
        }
    }
    
    function ChangeQuiz()
    {
        if($("#btnChangeQuiz").val()!="-1")
        {
            ProcessCommand("change_quiz");
            $("#btnChangeQuiz").val("-1");
        }
    }
    
    function drpTViews_onchange()
    {
         var view = $("#drpTViews").val();
         var subject = $("#drpSubjects").val();
         $.post("<?php echo $url ?>", {  ajax: "yes", view_change:"yes" ,  view : view , subject : subject },
         function(data){                
             document.getElementById('div_grid').innerHTML=data;
        });
    }
    
    
</script>


<div id="div_search"><?php echo $search_html ?></div>
<br />
<div style="width:100%;display: <?php echo $hide_mobile ?>">
    <table style="width:100%">
        <tr>
            <td align="left">
                <table>
                    <tr>
                        <td><?php echo SHOW ?> : </td>
                        <td>&nbsp;<select class="form-control input-inline " style="width:150px" onchange="drpTViews_onchange()" id="drpTViews" name="drpTViews"  ><option value="-1" ><?php echo SHOW_ALL_QUESTIONS ?></option><?php echo $quiz_view_options ?></select> </td>
                        <td>&nbsp;<select class="form-control input-inline " style="width:150px" onchange="drpTViews_onchange()" id="drpSubjects" name="drpSubjects"  ><option value="-1" ><?php echo SHOW_ALL_SUBJECTS ?></option><?php echo $subject_options ?></select> </td>
                    </tr>
                </table>                
            </td>
            <td align="right">
                
                 <table  border="0" cellpadding="3" align="right">
                    <tr>
                        <?php if(access::has("qst_change_quiz")) { ?>
                        <td >
                                <select class="form-control" onchange="ChangeQuiz()" id="btnChangeQuiz" name="btnChangeQuiz"  ><option value="-1" ><?php echo CHANGE_QUIZ ?></option><?php echo $quiz_options ?></select> 
                        </td>
                        <?php } ?>
                        <?php if(access::has("qst_change_brn")) { ?>
                        <td >
                                <select class="form-control" onchange="ChangeBranch()" id="btnChangeBrn" name="btnChangeBrn"  ><option value="-1" ><?php echo CHANGE_BRANCH ?></option><?php echo $branch_options ?></select> 
                        </td>
                        <?php } ?>
                        <?php if(access::has("qst_copy")) { ?>
                        <td valign="top">
                                <input class="btn green" onclick="ProcessCommand('create_copy')" type="button" value="<?php echo CREATE_COPY ?>" id="btnCopy" name="btnCopy" class="btn" /> 
                        </td>
                        <?php } ?>
                        <?php if(access::has("delete_qst")) { ?>
                        <td valign="top">
                                &nbsp;<input class="btn green" onclick="ProcessCommand('delete')" type="button" value="<?php echo DELETE ?>" id="btnDelete" name="btnDelete" class="btn" />
                        </td>
                        <?php } ?>
                    </tr>
                </table>
                
            </td>
        </tr>
    </table>
   
</div>
<br />
<form id="form1" name="form1">
<div id="div_grid"><?php echo $grid_html ?></div>
</form>


<table border="0" cellpadding="3" >
    <tr>            
        <?php if(access::has("arch_qst")) { ?>
        <td valign="top">
                <input  onclick="ProcessCommand('arch')" type="button" value="<?php echo L_ARCH ?>" id="btnArch" name="btnArch" class="btn green" /> 
        </td>
        <td valign="top">
               &nbsp;<input  onclick="ProcessCommand('unarch')" type="button" value="<?php echo L_UNARCH ?>" id="btnUnArch" name="btnUnArch" class="btn green" /> 
        </td>
        <?php } ?>
        <?php if(access::has("update_child_qst")) { ?>
         <td valign="top">
                &nbsp;<input  onclick="UpdateChilds()" type="button" value="<?php echo L_UPD_CHILDS ?>" id="btnUpdateChilds" name="btnUpdateChilds" class="btn green" />
        </td>  
        <?php } ?>
    </tr>
</table>    


    <br>
    <hr />
      <?php if(access::has("add_qst")) { ?>
       <a class="btn btn-primary" href="?module=add_question&quiz_id=-1&qstbank=1"><?php echo NEW_QUESTION ?></a>
       
       <a class="btn btn-primary" href="?module=add_question&quiz_id=-1&qstbank=1&f=2"><?php echo NEW_QUESTION ?> (<?php echo L_IMAGE_BASED ?>)</a>
      <?php } ?>
    <table id="test_div" style="display: none;background-color:#FFE591;"  width="<?php echo $mobile ? "95%" : "610px" ?>">
        <tr>
            <td colspan=2 align=right><a href="#" border="0" onclick="close_window()"><img src="style/i/close_button.gif" /></a></td>
        </tr>
        <tr>
             <td>
                &nbsp;&nbsp;
            </td>
            <td id="test_hr" >

            </td>
        </tr>
    </table>    
     <div id="templateDiv" style="display: none;background-color:#F9DD93">
        <table width="610px" bgcolor="#767F86" align="center" border="0">
            <tr>
                <td align="center">
                    <font color="white" face=tahoma size="3"><b><?php echo PLEASE_WAIT ?></b></font>
                </td>
            </tr>
        </table>
    </div>
