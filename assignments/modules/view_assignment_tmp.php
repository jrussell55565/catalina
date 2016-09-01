<?php if(!isset($RUN)) { exit(); } ?>
<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<script language="javascript">
var chklocal = new Array();
var chkIDS = new Array();
var chktype = new Array();
var uqids = new Array();
var unames = new Array();
var msg_type = 0;
var labelid = 'lbl';
function send_message(mtype)
{
		EnableButtons(true);
		if(mtype==2) labelid='lblres' ;
		else labelid='lbl';
	
		msg_type = mtype;
		chklocal = new Array();
                chkIDS = new Array();
		chktype = new Array();
		uqids = new Array();
		var cName="chklcl";
		var cNameI = "chkimp";
                var cNameF = "chkfb"
                var cNameL = "chkldap"
		var theForm = document.getElementById("form1");
                
		for (v=0,z=0,y=0,w=0,i=0,n=theForm.elements.length;i<n;i++)
		{
		  var lclind = theForm.elements[i].className.indexOf(cName);
		  var impind = theForm.elements[i].className.indexOf(cNameI);
                  var fbind = theForm.elements[i].className.indexOf(cNameF);
                  var ldapind = theForm.elements[i].className.indexOf(cNameL);
                  
		  if ( lclind!=-1 || impind !=-1 || fbind!=-1 || ldapind!=-1) {
		  
		    	if(theForm.elements[i].checked) 
			{
                            
                                var mytype=1;
                                var hdnID = "hdn"+y ;                                
                                var hdnuqID = "hdnuq"+y ;                              
                                var nameID = "hdnnames"+y ;
                                if(impind!=-1) 
                                {
                                     hdnID = "hdnI"+v ;                                
                                     hdnuqID = "hdnuqi"+v ;                              
                                     nameID = "hdnnamesi"+v ;
                                     mytype=2;
                                }
                                else if(fbind!=-1) 
                                {
                                     hdnID = "hdnfb"+w ;                                
                                     hdnuqID = "hdnuqfb"+w ;                              
                                     nameID = "hdnnamesfb"+w ;
                                     mytype = 3;
                                }
                                else if(ldapind!=-1) 
                                {
                                     hdnID = "hdnldap"+w ;                                
                                     hdnuqID = "hdnuqldap"+w ;                              
                                     nameID = "hdnnamesldap"+w ;
                                     mytype = 4;
                                }
                                
//                                alert(hdnID);
				var user_id=document.getElementById(hdnID).value;                                
				var uq_id=document.getElementById(hdnuqID).value;
                                var name=document.getElementById(nameID).value;                                   
                                
                                chkIDS[z] = theForm.elements[i];
                                
				chklocal[z] = user_id;
				chktype[z] = mytype;
				uqids[z] = uq_id;
                                unames[z] = name;
				z++;
			}			
                        if(lclind!=-1) y++;
                        else if(impind!=-1) v++;
                        else w++;
		  }
	       }
	       c = 0;
                              
	       SendMails();
}

function SendMails()
{
		if(sent==true)
		{
			if(chklocal[c]==null) 
			{
				EnableButtons(false);
				return ;
			}
			sent = false;
			SendMail(chklocal[c],chktype[c],uqids[c],unames[c])
                        chkIDS[c].checked=false;
			c++;
		}
		
		setTimeout(function(){SendMails()},2000);
		
	
} 

var sent = true;
var c = 0;

function SendMail(user_id, user_type,user_quiz_id,name)
{
	var lblID = "";
	msg_type == 1 ? lblID  = labelid+user_id : lblID  = labelid+user_id+user_quiz_id;
	document.getElementById(lblID).innerHTML="<img src='style/i/ajax_loader2.gif'>";
        // $.sticky("Sending mail to "+name, {autoclose : 4000, position: "top-right", type: "st-error" });         
        toastr.info("Mail to "+name, 'Sending');
	  $.post(document.location.href, {  send_mail : "yes", drpSendType : $("#drpSendType").val(), user_id : user_id , user_type : user_type,user_quiz_id:user_quiz_id, msgtype :msg_type, ajax: "yes" },
         function(data){          
//alert(data);   
            sent = true;
            msg_type == 1 ? lblID  = labelid+user_id : labelid+user_id+user_quiz_id;
	    document.getElementById(lblID).innerHTML='<?php echo $SENT['yes'] ?>';
        });
}
function EnableButtons(enable)
{	
	document.form1.btnStart.disabled=enable;
	document.form1.btnRes.disabled=enable;
}

function AddLocalUser()
{
    var user = $("#drpLclUser").val();
    var variant =$("#drpLclVariants").val(); 
    if(user=="") return ;
    
     $.post(document.location.href, {  add_user : "yes", user : user,variant:variant, is_local : "1" , ajax: "yes" },
         function(data){          
                document.getElementById('divLU').innerHTML=data;
        });
}

function AddImpUser()
{
    var user = $("#drpImpUser").val();
    var variant =$("#drpImpVariants").val(); 
    
    if(user=="") return ;
    
     $.post(document.location.href, {  add_user : "yes", user : user,variant:variant, is_local : "2" , ajax: "yes" },
         function(data){                      
                document.getElementById('divIU').innerHTML=data;
        });
}

function AddLDAPUser()
{
    var user = $("#drpLDAPUser").val();
    var variant =$("#drpLDAPVariants").val(); 
    if(user=="") return ;
    
     $.post(document.location.href, {  add_user : "yes", user : user,variant:variant, is_local : "4" , ajax: "yes" },
         function(data){          
                document.getElementById('divLDAP').innerHTML=data;
        });
}

</script>

<script language="javascript">
    
    function RecalcAll()
    {
        if(confirm('<?php echo ARE_YOU_SURE ?>'))
        {
            $.post(document.location.href, {  recalc_all : "yes", ajax: "yes" },
            function(data){                       
                   document.getElementById('divLU').innerHTML=data.divLU;
                   document.getElementById('divIU').innerHTML=data.divIU;
                   document.getElementById('divLDAP').innerHTML=data.divLDAP;
                   document.getElementById('divFB').innerHTML=data.divFB;
           }, "json");
        }
    }
    
</script>
                  


<form name=form1 id=form1 >

       
                            
                            <table border="0" style="width:90%" cellpadding="2" cellspacing="2">
                                <tr>
                                    <TH style='width:18%;border-width:0px' ROWSPAN=2 align='center' >
                                    <div align=left><img style='height:100px' align='left'  src='asg_images/<?php echo $img_src ?>' /></div>
                                    </TH>
                                    <td align="left"><b>&nbsp;<?php echo $asg_name ?></b>
                                    </td>
                                </tr>
                                </tr><td align="left">&nbsp;<?php echo $asg_desc ?></td></tr>
                             
                            </table>
                       
    <br />
    <div class="tabbable"> 
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#tabUsers" data-toggle="tab"><?php echo L_USER_LIST ?></a></li>  
                      <li ><a href="#tab1" data-toggle="tab"><?php echo ASG_SETTINGS ?></a></li>
                      <li><a href="#tab2" data-toggle="tab"><?php echo SUBJECT_SETTINGS ?></a></li>
                      <li><a href="#tab4" data-toggle="tab"><?php echo PAYMENT_INFO ?></a></li>
                      <li><a href="#tab3" data-toggle="tab"><?php echo A_OPERATIONS ?></a></li>
                      <li style="display:<?php echo $log_display ?>" ><a onclick="LoadLogs()" href="#mtab8l" data-toggle="tab"><?php echo L_LOGS ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tabUsers">
                            <div class="tabbable"> 
                    <ul class="nav nav-tabs">
                      <li class="active"><a href="#mtab1l" data-toggle="tab"><?php echo LOCAL_USERS ?></a></li>
                      <li><a href="#mtab2l" data-toggle="tab"><?php echo IMPORTED_USERS ?></a></li>
                      <li><a href="#mtab3l" data-toggle="tab"><?php echo FACEBOOK_USERS ?></a></li>
                      <li><a href="#mtab4l" data-toggle="tab"><?php echo LDAP_USERS ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="mtab1l" >
                            
                            <table border='0' style='width:100%'>
                                <tr>
                                   
                                    <td valign='top' >
                            
                                <?php if(access::has("add_user_asg")) { ?>
                                    <select id="drpLclUser" name="drpLclUser" data-placeholder="<?php echo CHOOSE_USER ?>..." class="chosen-select" style="width:220px;" tabindex="2"> 
                                        <option value=""></option>
                                        <?php echo $local_user_options ?>
                                    </select>            
                                    <span style="display:<?php echo $variants_display ?>">
                                    <select style="width:90px;" id="drpLclVariants" name="drpLclVariants" style="width:90px" class="chosen-select" >
                                            <?php echo $variant_options ?>
                                    </select>
                                    </span>
                                    <input class='btn green' onclick="AddLocalUser()" class="btn" style="height:29px" type="button" id="btnAddLclUser" name="btnAddLclUser" value=" + <?php echo ADD_USER ?>" />
                                    <br> <br>
                                     <?php } ?> 
                                    </td>
                                     <td valign='bottom' align='right'>
                                     <form class="form-inline"></form>
                                    <form class="form-inline" onsubmit="return submitHandler()">
                                    <input type="text" id="txtSearchUser" class="form-control inline input-medium" placeholder="<?php echo SEARCH ?>" />
                                    <input type="button" class='btn green' onclick="SearchInTable('txtSearchUser','table-lc','1,2,3,4')" value='<?php echo SEARCH ?>' />
                                    <input  class='btn green' type="button" onclick="ShowAllTableRows('txtSearchUser','table-lc')" value='<?php echo SHOW_ALL ?>' />    
                                       </form>  
                                    </td>
                                </tr>
                            </table>    
                                    
                                    
                                <div id="divLU"><?php echo $grid_lu_html ?></div>
                                
                                <?php if(access::has("delete_user_asg")) { ?><input style='width:150px' class='btn btn-primary' type='button' value='<?php echo L_DELETE_USER ?>' onclick='DeleteUsers()' /><?php } ?>
                                <?php if(access::has("delete_user_asg")) { ?><input style='width:150px' class='btn btn-primary' type='button' value='<?php echo L_DELETE_EXAM ?>' onclick='DeleteExam()' /><?php } ?>
                                <?php if(access::has("recalc_all")) { ?><input class='btn btn-primary' type='button' value='<?php echo RECALC_USER ?>' onclick='RecalcPoints()' /><?php } ?>                                
                                <input class='btn btn-primary' type='button' value='<?php echo L_REFRESH_DATA ?>' onclick='RefreshUsers()' />
                                
                            
                                   
                               
                                
                           <br />
                        </div>
                        <div class="tab-pane" id="mtab2l">
                            
                                
                                <?php if(access::has("add_user_asg")) { ?>
                                <select id="drpImpUser" name="drpImpUser"  data-placeholder="<?php echo CHOOSE_USER ?>..."  style="width:220px;" tabindex="2" class="form-control inline" >
                                    <option value=""></option>
                                <?php echo $imp_user_options ?>
                                </select>
                                            <span style="display:<?php echo $variants_display ?>">
                                                    <select style="width:90px;" id="drpImpVariants" name="drpImpVariants" class="form-control inline"  >
                                                        <?php echo $variant_options ?>
                                                    </select>
                                            </span>    
                                             <input class='btn green' onclick="AddImpUser()" class="btn green" style="height:29px" type="button" id="btnAddImpUser" name="btnAddImpUser" value=" + <?php echo ADD_USER ?>" />

                                             <br><br>
                                <?php } ?>                                
                                <div id="divIU"><?php echo $grid_iu_html ?></div>
                            
                           <br />
                        </div>
                         <div class="tab-pane" id="mtab3l">
                                <div id="divFB">
                                    <?php echo $grid_fb_html ?>
                                </div>
                            </div>
                         <div class="tab-pane" id="mtab4l">
                                <?php if(access::has("add_user_asg")) { ?>
                                    <select id="drpLDAPUser" name="drpLDAPUser" data-placeholder="<?php echo CHOOSE_USER ?>..."  style="width:220px;" tabindex="2" class="form-control inline" > 
                                        <option value=""></option>
                                        <?php echo $ldap_user_options ?>
                                    </select>            
                                    <span style="display:<?php echo $variants_display ?>">
                                    <select style="width:90px;" id="drpLDAPVariants" name="drpLDAPVariants" style="width:90px" class="form-control inline" >
                                            <?php echo $variant_options ?>
                                    </select>
                                    </span>
                                    <input class='btn green' onclick="AddLDAPUser()" class="btn green" style="height:29px" type="button" id="btnAddLDAPUser" name="btnAddLDAPUser" value=" + <?php echo ADD_USER ?>" />
                                    <br> <br>
                                     <?php } ?> 
                                <div id="divLDAP">
                                    <?php echo $grid_ldap_html ?>
                                </div>
                            </div>
                                                
                        
                    </div>
</div>

      
 <table>   
    <tr>
	<td ><hr />
            <table>
                <tr>
                    <td valign="middle">
                        <?php if((access::has("send_start_mail_asg") || access::has("send_results_mail_asg")) && $row['quiz_type']=="1") { ?><select class='form-control' STYLE="WIDTH:250px" id="drpSendType" name="drpSendType"><option value="1"><?php echo SEND_IF_NOT ?></option><option value="2"><?php echo SEND_ANYWAY ?></option></select><?php } ?>
                    </td>
                     <td valign="top">
                        <?php if(access::has("send_start_mail_asg") && $row['quiz_type']=="1") { ?>&nbsp;<input class='btn green' type=button  id=btnStart onclick='send_message(1)' value="<?php echo SENT_START_SENT ?>" /> <?php } ?>
                        <?php if(access::has("send_results_mail_asg") && $row['quiz_type']=="1") { ?><input class='btn green' type=button  id=btnRes onclick='send_message(2)' value="<?php echo SENT_RESULTS_SENT ?>" /><?php } ?>
                    </td>
                </tr>
            </table>

		
	</td>
    </tr>
</table>
                        </div>
                        <div class="tab-pane" id="tab1">
    <table>
        
        <tr>
            <td valign="top">
      
          
        
    
<table class="desc_text_bg">
       <tr>
        <td width="280px">
            <?php echo CREATED_BY ?> :
        </td>
        <td>
            <b><?php echo $creator ?></b>
        </td>
    </tr>
     <tr>
        <td width="280px">
            <?php echo ASSIGNMENT_NAME ?> :
        </td>
        <td>
            <?php echo $asg_name ?>
        </td>
    </tr>
    <tr>
        <td width="280px">
            <?php echo CAT ?> :
        </td>
        <td>
            <?php echo $cat_name ?>
        </td>
    </tr>
    <tr style="display:none">
        <td>
            <?php echo TEST ?> :
        </td>
        <td>
            <?php echo $test_name ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php echo TYPE ?> :
        </td>
        <td>
            <?php echo $quiz_type ?>
        </td>
    </tr>
    
    <tr style="display:<?php echo $srv_display ?>">
        <td>
            <?php echo RESULTS_BY ?> :
        </td>
        <td>
            <?php echo $results_by ?>
        </td>
    </tr>
   <tr >
        <td>
            <?php echo ASG_HOW_MANY ?> :
        </td>
        <td>
            <?php echo $asg_how_many ?>
        </td>
    </tr>
   <tr>
  <tr >
        <td>
            <?php echo ASG_AFFECT_CHANGE ?> :
        </td>
        <td>
            <?php echo $asg_affect_change ?>
        </td>
    </tr>
   <tr>
   

    <tr style="display:<?php echo $srv_display ?>">
        <td>
            <?php echo SUCCESS_POINT_PERC ?> :
        </td>
        <td>
            <?php echo $pass_score ?>
        </td>
    </tr>
    <tr style="display:<?php echo $srv_display ?>">
        <td>
            <?php echo TEST_TIME ?> :
        </td>
        <td>
            <?php echo $test_time ?>
        </td>
    </tr>   
    
     <tr >
        <td>
            <?php echo SHOW_QUESTIONS ?> :
        </td>
        <td>
            <?php echo $show_questions ?>
        </td>
    </tr>
  
       
        
     <tr >
        <td>
            <?php echo SHOW_RANDOMLY ?> :
        </td>
        <td>
            <?php echo $show_randomly ?> - <?php echo $random_type ?>
        </td>
    </tr>
    
     <tr >
        <td>
            <?php echo VARIANTS ?> :
        </td>
        <td>
            <?php echo $variants ?>
        </td>
    </tr>
    
     <tr >
        <td>
            <?php echo FACEBOOK_SHARE ?> :
        </td>
        <td>
            <?php echo $show_fb_share ?>
        </td>
    </tr>
    
        <tr >
        <td>
            <?php echo VISIBLE_START_DATE ?> :
        </td>
        <td>
            <?php echo $row['v_start_time']  ?>
        </td>
    </tr>
    
    <tr >
        <td>
            <?php echo VISIBLE_END_DATE ?> :
        </td>
        <td>
            <?php echo $row['v_end_time']  ?>
        </td>
    </tr>
    
    
  
    
</table>
                
                    </td>
                    <td>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </td>
              <td valign="top">
                  <table>
                      
    <tr>
        <td>
            <?php echo CALC_BY ?> :
        </td>
        <td>
            <?php echo $cal_by ?>
        </td>
    </tr>
    
    
    <tr>
        <td>
            <?php echo ANSWER_CALC_MODE ?> :
        </td>
        <td>
            <?php echo $ans_calc_mode ?>
        </td>
    </tr>
                      
   <tr>
        <td>
            <?php echo QUESTIONS_ORDER ?> :
        </td>
        <td>
            <?php echo $questions_order ?>
        </td>
    </tr>
    
    <tr>
        <td>
            <?php echo ANSWERS_ORDER ?> :
        </td>
        <td>
            <?php echo $answer_order ?>
        </td>
    </tr>
    
  <tr style="display:<?php echo $srv_display ?>">
        <td>
            <?php echo ASG_SEND_RESULTS ?> :
        </td>
        <td>
            <?php echo $asg_send_results ?>
        </td>
    </tr>
   <tr>	
       
        <tr >
        
       
            <td >
               <?php echo SEND_MAILS_COPY ?> :
            </td>
            <td>
                 <?php echo $txtMailCopy ?>
            </td>
        </tr>
       
   <tr >
        <td>
            <?php echo ENABLE_FOR_NEW ?> :
        </td>
        <td>
            <?php echo $accept_new ?>
        </td>
    </tr>
   <tr>	
       
  <tr style="display:<?php echo $srv_display ?>">
        <td>
            <?php echo SHOW_RESULTS ?> :
        </td>
        <td>
            <?php echo $show_results ?>
        </td>
    </tr>
    
    <tr style="display:<?php echo $srv_display ?>">
        <td>
            <?php echo REVIEW_ANSWERS ?> :
        </td>
        <td>
            <?php echo $review_answers ?>
        </td>
    </tr>
    
      <tr >
        <td>
            <?php echo ALLOW_BACK_EXAM ?> :
        </td>
        <td>
            <?php echo $allow_back_examp ?>
        </td>
    </tr>
    
     <tr >
        <td>
            <?php echo SHOW_MSG_AFTER_EACH_QST ?> :
        </td>
        <td>
            <?php echo $show_msg_after ?>
        </td>
    </tr>
    
     <tr >
        <td>
            <?php echo SHOW_POINT_INFO ?> :
        </td>
        <td>
            <?php echo $show_point_info ?>
        </td>
    </tr>
    
      <tr >
        <td>
            <?php echo CERTIFICATE ?> :
        </td>
        <td>
            <?php echo $certoficate ?>
        </td>
    </tr>
    
    <tr >
        <td>
            <?php echo ALLOW_ASG_RATE ?> :
        </td>
        <td>
            <?php echo $asg_rate ?>
        </td>
    </tr>
    
    <tr >
        <td>
            <?php echo ALLOW_QST_RATE ?> :
        </td>
        <td>
            <?php echo $qst_rate ?>
        </td>
    </tr>
    
    

    

                  </table>
            </td>
        </tr>
        <?php if($asg_rate_id!="-1") { ?>
        <tr >
            <td colspan="2" >
                <table>
                  <tr >
                        <td>
                            <?php echo RATED ?> :
                        </td>
                        <td>
                            <script language="javascript">
                                DrowRating('a<?php echo $asg_id ?>',<?php echo $asg_rate_id ?>, '','rating','<?php echo $LANGUAGES[$DEFAULT_LANGUAGE_FILE] ?>','local');
                            </script>
                            <div id ="a<?php echo $asg_id ?>" ></div>
                        </td>
                    </tr>
                 </table>
            </td>
        </tr>
        <?php } ?>
    </table>
                            
    <hr />
    <h5><b><?php echo L_QST_SETT ?></b></h5> <br />
    <table style='display:<?php echo $row['results_mode']=="1" ? "" : "none" ?>'>
        <tr>
            <td><?php echo L_POINT_KOE ?> : &nbsp;</td>
            <td><?php echo $row['point_koe'] ?></td>
        </tr>
         <tr>
            <td><?php echo CALC_BY ?> : &nbsp;</td>
            <td><?php echo $calcmode ?></td>
        </tr>
        <tr>
            <td><?php echo L_CALC_PEN ?> : &nbsp;</td>
            <td><?php echo $calcpen ?></td>
        </tr>
    </table>
    <br />
    <?php echo $results_tmp ?>
                            
</div>
                        <div class="tab-pane" id="tab4">
                              <table>
                                <tr>
                                    <td><?php echo COST ?> : </td>
                                    <td><?php echo $asg_cost ?>&nbsp;<?php echo PAYPAL_CURRENCY ?></td>
                                </tr>      
                                
                                  <tr>
                                    <td ><?php echo TOTAL_PAYMENTS ?> : </td>
                                    <td><?php echo $total_payments ?>&nbsp;<?php echo PAYPAL_CURRENCY ?></td>
                                </tr> 
                                
                            </table>
                        </div>
                        <div class="tab-pane" id="tab2">
                            <table>
                                <tr>
                                    <td style="width:300px"><?php echo SHOW_SUBJECT_NAME ?> : </td>
                                    <td><?php echo $show_sbj_name ?></td>
                                </tr>
                                 <tr>
                                    <td><?php echo FAIL_IF_SUBJECT_FAILS ?> : </td>
                                    <td><?php echo $fail_sbj_exam ?></td>
                                </tr>
                            </table>
                            <br />
                            <div id="divSBJ"><?php echo $sbj_grd_html ?></div>
                        </div>
                        <div class="tab-pane" id="tab3">
                             <br>
                                <?php if(access::has("recalc_all")) { ?><input class='btn green' type="button" class="btn" onclick="RecalcAll()" value="<?php echo RECALC_ALL ?>" /> <br /><br />  <?php } ?>                              
                                
                                <?php if(access::has("view_not")) { ?><a href="index.php?module=nots&id=<?php echo $asg_id ?>"><h4><?php echo NOTIFICATIONS ?></h4></a><?php } ?> 
                                <?php if(access::has("man_asg_qsts")) { ?>
                                    <?php if($row['is_random']=="1") { ?> <a href="index.php?module=questions&a_id=<?php echo $asg_id ?>&quiz_id=<?php echo $a_quiz_id ?>"><h4><?php echo MAN_ASG_QSTS ?></h4></a><?php } ?>
                                    <?php if($row['is_random']=="2") { ?> <h4><?php echo MAN_ASG_QSTS ?> - <?php echo $v_links ?></h4><?php } ?>
                                <?php } ?>
                                 <?php if(access::has("print_asg_qsts")) { ?>                                    
                                    <?php if($row['is_random']=="1") { ?> <a target="_blank" href="index.php?module=print_questions&id=<?php echo $a_quiz_id ?>"><h4><?php echo PRINT_ASG_QSTS ?></h4></a><a target="_blank" href="index.php?module=print_questions&c=yes&id=<?php echo $a_quiz_id ?>"><h4><?php echo PRINT_ASG_QSTS_CRCT ?></h4></a><?php } ?>
                                    <?php if($row['is_random']=="2") { ?> <h4><?php echo PRINT_ASG_QSTS ?> - <?php echo $v_print_links ?></h4><h4><?php echo PRINT_ASG_QSTS_CRCT ?> - <?php echo $v_print_c_links ?></h4><?php } ?>
                                <?php } ?>
                        </div>
                        
                        <div class="tab-pane" id="mtab8l" >
                            
                                <div id="div_grid_logs" style="width:90%"></div>
                        </div>
                        
                    </div>
    </div>                            

<br><br>

</form>

<script language="javascript">
function DeleteUsers()
{
    var myarr = grd_get_checkboxes(document.getElementById("form1"),"chklcl","");
    var myarrImp = grd_get_checkboxes(document.getElementById("form1"),"chkimp","");
    var myarrLdap = grd_get_checkboxes(document.getElementById("form1"),"chkldap","");
    var myarrfb = grd_get_checkboxes(document.getElementById("form1"),"chkfb","");    
    if(confirm('<?php echo ARE_YOU_SURE ?>'))
    {
        $.post(document.location.href, {  delete_user : "yes", ajax: "yes" , myarr : myarr , myarrImp:myarrImp , myarrLdap:myarrLdap, myarrfb:myarrfb },
        function(data){                            
                document.getElementById('divLU').innerHTML=data.divLU;
                document.getElementById('divIU').innerHTML=data.divIU;
                document.getElementById('divLDAP').innerHTML=data.divLDAP;
                document.getElementById('divFB').innerHTML=data.divFB;
       }, "json");
    }
}

function DeleteExam()
{
    var myarr = grd_get_checkboxes(document.getElementById("form1"),"chklcl","");
    var myarrImp = grd_get_checkboxes(document.getElementById("form1"),"chkimp","");
    var myarrLdap = grd_get_checkboxes(document.getElementById("form1"),"chkldap","");
    var myarrfb = grd_get_checkboxes(document.getElementById("form1"),"chkfb","");
    
    if(confirm('<?php echo ARE_YOU_SURE ?>'))
    {
        $.post(document.location.href, {  delete_exam : "yes", ajax: "yes", myarr : myarr , myarrImp:myarrImp , myarrLdap:myarrLdap, myarrfb:myarrfb },
        function(data){                       
                document.getElementById('divLU').innerHTML=data.divLU;
                document.getElementById('divIU').innerHTML=data.divIU;
                document.getElementById('divLDAP').innerHTML=data.divLDAP;
                document.getElementById('divFB').innerHTML=data.divFB;             
       }, "json");
    }
}

function RefreshUsers()
{
     $.post(document.location.href, {  refresh_users : "yes", ajax: "yes"  },
        function(data){                       
                document.getElementById('divLU').innerHTML=data.divLU;
                document.getElementById('divIU').innerHTML=data.divIU;
                document.getElementById('divLDAP').innerHTML=data.divLDAP;
                document.getElementById('divFB').innerHTML=data.divFB;
       }, "json");
}

function RecalcPoints()
{
    var myarr = grd_get_checkboxes(document.getElementById("form1"),"chklcl","");
    var myarrImp = grd_get_checkboxes(document.getElementById("form1"),"chkimp","");
    var myarrLdap = grd_get_checkboxes(document.getElementById("form1"),"chkldap","");
    var myarrfb = grd_get_checkboxes(document.getElementById("form1"),"chkfb","");
    
    if(confirm('<?php echo ARE_YOU_SURE ?>'))
    {
        $.post(document.location.href, {  recalc_points : "yes", ajax: "yes" , myarr : myarr , myarrImp:myarrImp , myarrLdap:myarrLdap, myarrfb:myarrfb },
        function(data){               
                document.getElementById('divLU').innerHTML=data.divLU;
                document.getElementById('divIU').innerHTML=data.divIU;
                document.getElementById('divLDAP').innerHTML=data.divLDAP;
                document.getElementById('divFB').innerHTML=data.divFB;
       }, "json"); //, "json"
    }
}

function LoadLogs()
{    
      $.post("index.php?module=view_assignment&asg_id=<?php echo $asg_id ?>", {  ajax: "yes", loadlogs:"yes"  },
     function(data){             
         $("#div_grid_logs").html(data);
    });
}

</script>


<br>

<a href="#" onclick="javascript:window.location.href='?module=assignments'"><?php echo BACK ?></a>

<br>
<br>
<br>
<br>
<br>


<style>
    .chosen-container-single .chosen-single {
    height: 30px;
    border-radius: 3px;
    border: 1px solid #CCCCCC;
}
.chosen-container-single .chosen-single span {
    padding-top: 2px;
}
.chosen-container-single .chosen-single div b {
    margin-top: 2px;
}

.chosen-container-active .chosen-single,
.chosen-container-active.chosen-with-drop .chosen-single {
    border-color: #ccc;
    border-color: rgba(82, 168, 236, .8);
    outline: 0;
    outline: thin dotted \9;
    -moz-box-shadow: 0 0 8px rgba(82, 168, 236, .6);
    box-shadow: 0 0 8px rgba(82, 168, 236, .6)
}

</style>