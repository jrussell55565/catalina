<?php if(!isset($RUN)) { exit(); } ?>

<script language="javascript">

var cpage = "index.php?module=view_details&user_quiz_id=<?php echo $id ?>";

function SaveResults()
{
    var status = $("#slcStatus").val();
    var point = $("#txtTP").val();
    var levelid = $("#slcLevel").val();
    
    $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", update_results : "yes", status : status, point : point, level : levelid },
         function(data){
             alert(data);
        });
}

function SaveSubjectResults(myid)
{
    var status = $("#slcSbjStatus"+myid).val();
    var point = $("#txtSbjPoint"+myid).val();
    
      $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", update_subject_results : "yes", status : status, point : point, sbj_id : myid },
         function(data){
             alert(data);
        });
}

</script>

<script language="JavaScript" type="text/javascript" src="lib/validations.js"></script>


<table border="0">
    <tr>
        <td valign="top">
            <table>
                 <tr>
                    <td align="center"><?php echo $img ?></td>
                </tr> 
            </table>
        </td>
        <td>&nbsp;&nbsp;</td>
        <td valign="top">
            <table border="0" style="width:<?php echo util::GetWidth() ?>">                                        
                  <tr>
                      <td><h5 class="heading"><?php echo USER_NAME ?></h5></td>
                       <td><h5 class="heading"><?php echo $uq_row['NAME'] ?></h5></td>
                  </tr>
                   <tr>
                      <td><h5 class="heading"><?php echo USER_SURNAME ?></h5></td>
                       <td><h5 class="heading"><?php echo $uq_row['Surname'] ?></h5></td>
                  </tr>
                     <tr>
                      <td><h5 class="heading"><?php echo EMAIL ?></h5></td>
                      <td><h5 class="heading"><?php echo $uq_row['email'] ?></h5></td>
                  </tr>
                 

              </table>
        </td>
    </tr>
</table>    

<br />

<div class="tabbable" > 
                    <ul class="nav nav-tabs" style="display:<?php echo $displaym ?>">
                      <li style="display:<?php echo $res_display ?>" <?php echo $exam_active ?>><a href="#mtab1l" data-toggle="tab"><?php echo TOTAL_RESULTS ?></a></li>
                      <li style="display:<?php echo $res_display ?>" ><a href="#mtab2l" data-toggle="tab"><?php echo SUBJECT_RESULTS ?></a></li>                    
                      <li <?php echo $survey_active ?>><a href="#mtab3l" data-toggle="tab"><?php echo DETAILS ?></a></li>                    
                      <li style="display:<?php echo $log_display ?>" ><a onclick="LoadLogs()" href="#mtab5l" data-toggle="tab"><?php echo L_LOGS ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?php echo $exam_active_tab ?>" id="mtab1l" >

<div style="display:<?php echo $res_display ?>">
	<div >
            
		<table border=1 class="table table-striped table-bordered" style="width:200px">
			<tr>
				<td>
				
				
				
				
			<div >
                            <label><?php echo SUCCESS ?> :</label>
                            <select class='form-control' id="slcStatus" style="width:100px" <?php echo $disabled ?> >
                                    <?php echo $status_options ?>
                            </select>					
			</div>
			</td>
				<td>
                                    <div >
                                            <label><?php echo TOTAL_POINT ?> : </label>
                                            <input class='form-control' type="text" style="width:100px" <?php echo $disabled ?>  id="txtTP" class="span12" value="<?php echo $total_point ?>" />

                                    </div>
                                </td>
                
                         <td>
				
				
				
				
			<div >
			<label><?php echo LEVEL ?> :</label>
			<select class='form-control' id="slcLevel" style="width:130px" <?php echo $disabled ?> >
                                <?php echo $level_options ?>
                        </select>					
			</div>
			</td>
                
		<td valign=center style="display:<?php echo $btn_display ?>">
                <div >
			<label>&nbsp;</label>
			<button type="button" style="display:<?php echo $btn_display ?>" class="btn green" onclick="SaveResults()"><?php echo SAVE ?></button>
                                      
		</div>
		</td>
			</tr>
		</table>
	</div>
             <br />
             <table class="text1" cellpadding="2" cellspacing="2">
                <tr><td style="width:150px" ><?php echo START_DATE ?> : </td><td><?php echo $uq_row['added_date'] ?></td></tr>
                <tr><td><?php echo FINISH_DATE ?> : </td><td><?php echo $uq_row['finish_date'] ?></td></tr>
                <tr><td><?php echo TIME_SPENT ?> : </td><td><?php echo $time_spent ?> (<?php echo MINS ?>)</td></tr>
                <tr><td><?php echo STATUS ?></td><td><?php echo $ASG_STATUS[$uq_row['status']] ?></td></tr>
                <tr><td><?php echo EXAM_TIME ?></td><td><span id="spExamTime"><?php echo $uq_row['user_quiz_time'] ?></span></td></tr>
            </table>
             
            <br />
            
            <form class="form-inline">
                <input onclick="RefreshPage()" style="width:300px" type="button" value="<?php echo L_REFRESH_DATA ?>" class="btn green" /><br/><br/>
                <?php  if(access::has("change_uq_status")) { ?><input <?php if($uq_row['uq_status']=="1") { echo "disabled"; } ?> onclick="ExamStatus(1)" style="width:150px" type="button" value="<?php echo L_OPEN_EXAM ?>" class="btn green" /><input <?php if($uq_row['uq_status']!="1") { echo "disabled"; } ?> onclick="ExamStatus(2)" style="width:150px" type="button" value="<?php echo L_FINISH_EXAM ?>" class="btn green" /><br /><br /><?php } ?>
                <?php if(access::has("download_cert") && $uq_row['cert_enabled'] == "1" &&  $uq_row['success']=="1") { ?><input onclick="window.location.href='<?php echo $download_link ?>'" style="width:300px" class='btn green' type="button" value="<?php echo $download_cert ?>" class="btn" /><br/><br/><?php } ?>
                <?php if(access::has("recalc_all")) { ?><input class='btn green' onclick="RecalcUserAll()" style="width:300px" type="button" value="<?php echo RECALC_USER ?>" class="btn" /><br/><br/><?php } ?>
                <?php if(access::has("reset_userquiz")) { ?><input class='btn green' onclick="ResetUserQuiz()" style="width:300px" type="button" value="<?php echo RESET_RESULTS ?>" class="btn" /><br/><br/><?php } ?>
                <?php if(access::has("add_mins")) { ?><input onkeypress='return onlyNumbers(event);' type="text" id="txtTime" style="width:50px" class="form-control input-small" /><input onclick="AddMins()" style="width:155px" type="button" value="<?php echo ADD_MINS ?>" class="btn green" /><br/><br/><?php } ?>
                 <?php if(access::has("man_asg_qsts") && $uq_row['is_random']=="3" ) { ?>
                    <input style="width:300px" class="btn green" type="button" value="<?php echo MAN_ASG_QSTS ?>" onclick="window.location.href='index.php?module=questions&a_id=<?php echo $uq_row['asg_id'] ?>&quiz_id=<?php echo $uq_row['u_quiz_id'] ?>'" />
                 <?php } ?>
            </form>
    
</div>
                            
                            </div>
                        <div class="tab-pane" id="mtab2l" style="display:<?php echo $displaym ?>">
                            <div style="display:<?php echo $res_display ?>">
                            <table border=1 class="table table-striped table-bordered" style="width:600px">
                                <tr>
                                    <td><?php echo SUBJECT ?></td>
                                    <td><?php echo POINT ?></td>
                                    <td><?php echo SUCCESS ?></td>
                                    <td>&nbsp;</td>
                                </tr>
                           
                           <?php
                                while($sbj_row = db::fetch($subject_results))
                                {
                                    ?>
                            <tr>
                                <td>
                                    <?php echo $sbj_row['subject_name'] ?>&nbsp;&nbsp;
                                </td>
                                <td>
                                    <input class='form-control' <?php echo $disabled ?> style="width:70px" type="text" id="txtSbjPoint<?php echo $sbj_row['id'] ?>" value="<?php echo get_success_point($sbj_row) ?>" />&nbsp;&nbsp;
                                </td>
                                <td>
                                    <select class='form-control' id="slcSbjStatus<?php echo $sbj_row['id'] ?>" style="width:100px" <?php echo $disabled ?> >
                                            <?php echo get_success_status_options($sbj_row) ?>
                                    </select>		
                                    &nbsp;&nbsp;
                                </td>
                                <td>
                                    <input onclick="SaveSubjectResults(<?php echo $sbj_row['id'] ?>)" style="display:<?php echo $btn_display ?>" type="button" id="btnSaveSbj" value="<?php echo SAVE ?>" class="btn green" />
                                </td>
                            </tr>
                                    <?php
                                }
                           ?>
                             </table>
                        </div>
                            </div>
                        <div class="tab-pane <?php echo $survey_active_tab ?>" id="mtab3l" >
          

<table width="90%">
    <tr>
        <td>
 
        </td>   
    </tr>    

<?php
while($row = db::fetch($asg_res))
{
    ?>
    <tr>
        <td>
            <hr />
        </td>
    </tr>
    <tr>
        <td><div id="dvQst<?php echo $row['id'] ?>">
   <?php
    echo get_question($row);
    ?></div>
        </td>
    </tr>
     <tr>
        <td>
            <input href='#myModal' data-toggle='modal' <?php echo access::display("make_cor") ?> onclick="MakeCorrections(<?php echo $row['id'].",".$id.",".$row['question_type_id'] ?>)" type="button" value="<?php echo MAKE_CORRECT ?>" class="btn green" />&nbsp;
            <input <?php echo $uq_row["quiz_type"]=="2" ? "style='display:none'" : access::display("recalc_points"); ?> onclick="RecalcPoints(<?php echo $row['id'] ?>)" type="button" value="<?php echo RECALC_POINTS ?>" class="btn green" />&nbsp;
            <input  <?php echo access::display("rep_issue") ?> type="button" onclick="ReportIssue(<?php echo $row['id'] ?>)" value="<?php echo REPORT_ISSUE ?>" class="btn green" />&nbsp;
            <input  <?php echo access::display("set_correct") ?> type="button" onclick="MakeTrue(1,<?php echo $row['id'].",".$id.",".$row['question_type_id'] ?>)" value="<?php echo L_SET_CORRECT ?>" class="btn green" />&nbsp;
            <input  <?php echo access::display("clear_answers") ?> type="button" onclick="ResetAns(<?php echo $row['id'].",".$id.",".$row['question_type_id'] ?>)"  value="<?php echo L_CLEAR_ANS ?>" class="btn green" />
        </td>
    </tr>
    <?php
}
?>
    
   

</table>

                                          </div>
                        
                         <div class="tab-pane" id="mtab5l" >                            
                                <div id="div_grid_logs" style="width:90%"></div>
                        </div>
                        
                    </div>
</div>
<div style="display:<?php echo $hide_mobile ?>">


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 id="myModalLabel"><?php echo MAKE_CORRECT ?></h3>
      </div>
      <div class="modal-body" style="height:500px">
         <p>
            <img src="style/i/ajax_loader.gif" id="imgAjax" />
        <table style="display:none">
            <tr><td><?php echo YOUR_POINT ?></td><td>&nbsp;&nbsp;&nbsp;</td><td><?php echo PENALTY_POINT ?></td></tr>
            <tr><td><input class="input-small"type="text" id="txtQstPoint" /></td><td>&nbsp;&nbsp;&nbsp;</td><td><input class="input-small" type="text" id="txtQstPenalty" td></tr>
        </table>
             
        <form method="post" id="form1" name="form1"><div id="divQstHtml" ></div></form>
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo CLOSE ?></button>
        <button type="button" onclick="SaveQuestion()" class="btn btn-primary"><?php echo SAVE ?></button>
      </div>
    </div>
  </div>
</div>    
    
    
</div>
<script language="javascript" >
    var question_type = -1;
    var _qid = -1;
    
    <?php if(access::has("set_correct")) { ?>
    function MakeTrue(mtype,qid,uid,qst_type)
    {
         _qid=qid;         
         $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", settrue:"yes", qid:qid ,mtype:mtype  },
         function(data){                   
             document.getElementById('imgAjax').style.display="none";
             document.getElementById('dvQst'+_qid).innerHTML=data.rhtml;         
        },"json");
    }
    <?php } ?>
    
    <?php if(access::has("clear_answers")) { ?>
    function ResetAns(qid,uid,qst_type)
    {
        _qid=qid;         
         $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", reset_ans:"yes", qid:qid   },
         function(data){                   
             document.getElementById('imgAjax').style.display="none";
             document.getElementById('dvQst'+_qid).innerHTML=data.rhtml;         
        },"json");
    }
    <?php } ?>
    
    <?php if(access::has("make_cor")) { ?>
    function MakeCorrections(qid,uid,qst_type)
    {                
        question_type = qst_type;
        _qid=qid;
        $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", loadqst:"yes", qid:qid   },
         function(data){
             document.getElementById('imgAjax').style.display="none";
             document.getElementById('divQstHtml').innerHTML=data.rhtml;
             $("#txtQstPoint").val(data.total_point);
             $("#txtQstPenalty").val(data.penalty_point);            
        },"json");
        
        
    }        
    function SaveQuestion()
    {               
        
        var post_string = get_post_string(question_type);
        
         $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", qid:_qid, question_type:question_type, saveqst:"yes", post_data : post_string   },
         function(data){
          //   alert(data.rhtml);
              document.getElementById('dvQst'+_qid).innerHTML=data.rhtml;
              $('#myModal').modal('hide');
        }, "json");
    }    
    <?php } ?>
    
    <?php if(access::has("recalc_points")) { ?>
    function RecalcPoints(qst_id)
    {
         $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", recalc:"yes", qid:qst_id   },
         function(data){
             document.getElementById('dvQst'+qst_id).innerHTML=data.rhtml;;            
        },"json");
        
    }
    <?php } ?>
    
    <?php if(access::has("recalc_all")) { ?>
    function RecalcUserAll()
    {
        if(confirm('<?php echo ARE_YOU_SURE ?>')) {
        $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", recalcall:"yes" },
         function(data){
             if(parseInt(data)==1) window.location.href = '<?php echo util::GetCurrentUrl() ?>';
            // document.getElementById('dvQst'+qst_id).innerHTML=data.rhtml;;            
        });
        }
    }
    <?php } ?>

    <?php if(access::has("reset_userquiz")) { ?>
    function ResetUserQuiz()
    {        
        if(confirm('<?php echo RESET_USERQUIZ_CONFIRM ?>')) {
        $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", reset_userquiz:"yes" },
         function(data){
             window.location.href = data;
            // document.getElementById('dvQst'+qst_id).innerHTML=data.rhtml;;            
        });
        }
    }
    <?php } ?>

    <?php if(access::has("reset_userquiz")) { ?>
    function AddMins()
    {
        if(confirm('<?php echo ARE_YOU_SURE ?>')) {
        $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", addmins:"yes" , "txtTime" : $("#txtTime").val() },
         function(data){
             document.getElementById('spExamTime').innerHTML = data;                 
        });
        }
    }
    <?php } ?>

    function ReportIssue(qst_id)
    {
        $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", rep_issue:"yes" , qst_id : qst_id },
         function(data){
             if(parseInt(data)==1) window.location.href='index.php?module=add_edit_ticket';             
        });
    }
    
    function RefreshPage()
    {
        ReloadPage();
    }
    
    function ReloadPage()
    {
        window.location.href = '<?php echo util::GetCurrentUrl() ?>';
    }
    
    <?php if(access::has("change_uq_status")) { ?>
    function ExamStatus(ptype)
    {
            if(confirm('<?php echo ARE_YOU_SURE ?>')) {
            $.post(cpage, {  ajax: "yes", open_exam:"yes" , ptype:ptype },
             function(data){            
                 ReloadPage();
            });
        }
    }
    <?php } ?>
    
    function LoadLogs()
    {
          $.post("index.php?module=view_details&user_quiz_id=<?php echo $id ?>", {  ajax: "yes", loadlogs:"yes"  },
         function(data){             
             $("#div_grid_logs").html(data);
        });
    }
        
    
</script>