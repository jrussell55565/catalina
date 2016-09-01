<?php if(!isset($RUN)) { exit(); } ?>
<?php

 access::menu("add_assignment");

 require "extgrid.php";
 require "db/users_db.php"; 
 require "db/asg_db.php";
 require "db/quiz_db.php";
 require "db/questions_db.php";
 require "lib/cert.php";
 require "events/assignments_events.php";
 require "lib/questions_util.php";
  

 $val = new validations("btnSave");
 $val->AddValidator("drpCats", "notequal", CATEGORY_VAL, "-1");
 //$val->AddValidator("drpTests", "notequal", TEST_VAL, "-1");
 $val->AddValidator("txtSuccessP", "empty", SUCCESS_VAL,"");
 $val->AddValidator("txtTestTime", "empty", TIME_VAL,"");
 $val->AddValidator("txtSuccessP", "numeric", SUCCESS_NUM_VAL,"");
 $val->AddValidator("txtTestTime", "numeric", TIME_NUM_VAL,"");
 $val->AddValidator("txtMailCopy", "email", R_EMAIL_VAL , "","0");
 $val->def_value="0";


$selected = "-1";
//$selected_subject = "-1";
$selected_calc_pen = "0";
$selected_test = "-1";
$selected_test_bnk = "-1";
$selected_type = "-1";
$selected_showres = "-1";
$selected_results = "-1";
$selected_q_order = "-1";
$selected_a_order = "-1";
$selected_review = "-1";
$selected_qtype ="-1";
$selected_qchnage = "-1";
$selected_send = "-1";
$selected_enablenew_options = "-1";
$selected_point_info = "-1";
$selected_res_template = "-1";

$selected_allow_change_answers = "-1";
$selected_show_msg_after_qst = "-1";
$selected_show_all_random = "-1";
$selected_random_variant = "-1";
$selected_calc_mode = "-1";
$selected_ans_calc_mode = "-1";
$selected_show_sub="-1";
$selected_fail_sub = "-1";
$selected_random_type = "2";
$selected_asg_rate = "-1";
$selected_qst_rate = "-1";

$selected_fb_users = 0;
$fb_user_options ="";

$selected_ldap_users = 0;
$ldap_user_options ="";

$selected_fb_share = "-1";

$asg_quiz_type="1";
$divQstBank = "";

$local_user_ids = array();
$imp_user_ids= array();
$ldap_user_ids = array();
$bank_questions_ids = array();
$txtRandom = "2";
$txtHowMany = "1";
$selected_cert_name = "-1";

$answer_variants = db::GetResultsAsArray(orm::GetSelectQuery("answer_variants", array(), array(), "id"));

$answer_js = GenerateJsArray($answer_variants);

$chkShowIntro = "";
$txtIntroText = "";
$txtShortDesc="";
$id="-1";
$local_asg_users=array();
$imp_asg_users=array();
$row_asg=0;
$asg_img_file="";
$img_thumb = "";

$display_users = "";
$disable_controls = "";
$subject_list="";
$asg_status=0;

$regen_display = "none";
$regen_mode=false;

$selected_quiz_ids = array();
$txtPointKoe = "1";

if(isset($_GET["id"]))
{
        
        access::has("edit_assignment", 2);
    
        $id = util::GetID("?module=assignments");
        $rs_asg=asgDB::GetAsgQueryById($id);

        if(db::num_rows($rs_asg) == 0 ) util::redirect("?module=assignments");

        $row_asg=db::fetch($rs_asg);

        $selected = $row_asg["is_random"] == "1" ? $row_asg["cat_id"] : $row_asg["asg_cat_id"];
        $selected_test= $row_asg["org_quiz_id"];
        $selected_test_bnk = $selected_test;
        $copied_quiz_id= $row_asg["quiz_id"];        
        $selected_type = $row_asg["quiz_type"];
        $selected_showres= $row_asg["show_results"];
        $selected_results =$row_asg["results_mode"];
        $selected_q_order =$row_asg["qst_order"];
        $selected_a_order =$row_asg["answer_order"];
        $selected_review=$row_asg["allow_review"];
	$txtHowMany =$row_asg["limited"];
	$selected_qchnage = $row_asg["affect_changes"];
	$selected_send = $row_asg["send_results"];
	$selected_enablenew_options = $row_asg["accept_new_users"];
        $txtSuccessP = $row_asg["pass_score"];
        $txtTestTime = $row_asg["quiz_time"];
        $txtMailCopy = $row_asg["mails_copy"];
        $txtAssignmentName=$row_asg["assignment_name"];
        $asg_quiz_type=$row_asg["asg_quiz_type"];
        $txtShortDesc=$row_asg["short_desc"];
        $selected_calc_mode = $row_asg['calc_mode'];
        $selected_ans_calc_mode = $row_asg['ans_calc_mode'];
        
        $selected_allow_change_answers = $row_asg["allow_change_prev"];
        $selected_show_msg_after_qst = $row_asg["show_success_msg"];
        $selected_point_info = $row_asg["show_point_info"];
        $selected_show_all_random = $row_asg["is_random"];
        $selected_random_type = $row_asg["random_type"];
        $selected_fb_users = $row_asg["fb_users_list"];
        $selected_ldap_users = $row_asg["ldap_users_list"];
      
        $selected_random_variant = $row_asg["variants"];        
        $txtRandom = $row_asg["random_qst_count"];
        $selected_cert_name = $row_asg["cert_name"];
        $chkShowIntro = $row_asg["show_intro"] == "1" ? "checked" : "";
        $txtIntroText = $row_asg["intro_text"];
        $selected_res_template = $row_asg["results_template_id"];
        $selected_fb_share = $row_asg["fb_share"];
        //$asg_status = $row_asg["status"];
        $asg_status = 1; 
        $txtStartDate = $row_asg["v_start_time"]!="" ? date('Y/m/d H:i', strtotime($row_asg["v_start_time"])) : ""; 
        $txtEndDate = $row_asg["v_end_time"]!="" ? date('Y/m/d H:i', strtotime($row_asg["v_end_time"])) : ""; 
        
        $selected_show_sub = $row_asg["show_subject_name"];
        $selected_fail_sub = $row_asg["fail_sbj_exam"];         
        
        $txtCost = $row_asg["asg_cost"];   
        
        $selected_asg_rate = $row_asg["asg_rate_id"];  
        $selected_qst_rate = $row_asg["qst_rate_id"];  
        
        $txtPointKoe = $row_asg["point_koe"];
        //$selected_calc_mode = $row_asg["tpoint_calc_mode"];
        $selected_calc_pen = $row_asg["calc_pen"];
  
        $local_asg_users=db::GetResultsAsArray(orm::GetSelectQuery("assignment_users", array(), array("assignment_id"=>$id, "user_type"=>"1"), ""));
        $local_user_ids= db::GetResultsByColumn($local_asg_users, "user_id");
        $imp_asg_users=db::GetResultsAsArray(orm::GetSelectQuery("assignment_users", array(), array("assignment_id"=>$id, "user_type"=>"2"), ""));
        $imp_user_ids= db::GetResultsByColumn($imp_asg_users, "user_id");
        
        $ldap_asg_users=db::GetResultsAsArray(orm::GetSelectQuery("assignment_users", array(), array("assignment_id"=>$id, "user_type"=>"4"), ""));
        $ldap_user_ids= db::GetResultsByColumn($ldap_asg_users, "user_id");
        
        $qst_diff_leves_asg_res = db::GetResultsAsArray(orm::GetSelectQuery("assignment_diff_level_xreff", array(), array("asg_id"=>$id), ""));
        
        if($selected_fb_users=="2")
        {
            $fb_user_results = db::exec_sql(asgDB::GetAssignmentAppUsersQuery(3,$id,3));
            $fb_user_options = webcontrols::GetOptions($fb_user_results, "email", "email", "", false);
        }
        
        if($selected_ldap_users=="2")
        {
            $ldap_user_results = db::exec_sql(asgDB::GetAssignmentAppUsersQuery(4,$id,4));
           // $ldap_user_options = webcontrols::GetOptions($ldap_user_results, "email", "email", "", false);  
            // can be uncommented
        }
       
        if($row_asg["asg_quiz_type"]=="2")
        {
            $selected_test_bnk="-100";   
            if($selected_show_all_random=="1") $bank_questions_ids= db::GetResultsAsArrayByColumn(orm::GetSelectQuery("questions", array(), array("quiz_id"=>$copied_quiz_id), "id"), "parent_id");           
            else $bank_questions_ids= db::GetResultsAsArrayByColumn("SELECT * FROM questions WHERE quiz_id IN (SELECT quiz_id FROM variant_quizzes vq WHERE vq.asg_id=$id)", "parent_id");           
            $divQstBank = GetQuestionsBank();
        }
        
        if($row_asg['is_random']=="2" && $selected_test_bnk=="-100")
        {
            $regen_display="";
            $regen_mode = true;
        }
        
        $selected_quiz_ids = db::GetResultsAsArrayByColumn(orm::GetSelectQuery("asg_qbank_quizzes", array(), array("asg_id"=>$id), ""), "quiz_id");
        $selected_quiz_ids_js = db::arr_to_in($selected_quiz_ids);   
        
        
        $subjects_res = db::exec_sql(asgDB::GetAsgSubjects($id));
        $subject_list_options = array();
        while($subject_row = db::fetch($subjects_res))
        {
            $pres_name = $subject_row['pres_name'];
            if($pres_name=="") $pres_name = NO_PRESENTATION;
            $key = base64_encode($subject_row['subject_id']).";|".base64_encode($subject_row['min_subject_point']).";|".base64_encode($subject_row['pres_id']).";|".base64_encode($subject_row['pres_duration']);
            $value = $subject_row['subject_name']." - ".MIN_SUCCESS_POINT." : ".$subject_row['min_subject_point']." - ".$pres_name." - ".PRESENTATION_DURATION." : ".$subject_row['pres_duration'];
            $subject_list_options[$key] = $value;
        } 
        
        $subject_list = webcontrols::BuildOptions($subject_list_options, "-1");
        
        $asg_img_file=trim($row_asg["asg_image"]);
        
        if($asg_img_file!="")
        $img_thumb=util::get_img($asg_img_file,false,'asg_images',100);
        
     //   if($asg_status>0) 
     //   {
            $display_users = "none";  
            $disable_controls="disabled";
     //   }

}
else access::has("add_assignment", 2);

assignment_add_page_loading($id,$row_asg,$local_asg_users,$imp_asg_users);

$qst_diff_leves_res = db::GetResultsAsArray(orm::GetSelectQuery("qst_diff_levels", array(), array(), "priority , id"));

$t_query = orm::GetSelectQuery("v_user_groups", array(), au::arr_where(array()), "");
$headers = array(iutil::get_chk_all("chk_group","chk_group_onclick"), L_USER_GROUP,L_COURSE);
$columns = array("group_name"=>"text", "course"=>"text");
$grd_groups = new extgrid($headers, $columns, util::GetCurrentUrl(), "tblgroups");
$grd_groups->chk_class = "chk_group";
$grd_groups->register_checkbox_click=true;
$grd_groups->checkbox=true;
$grd_groups->exp_enabled=false;
$grd_groups->delete=false;
$grd_groups->edit=false;
$grd_groups->identity="groups";
$grd_groups->PAGING=1000000;
$grd_groups->DrowTable($t_query);
$grd_groups_html = $grd_groups->table;        


$type_options = webcontrols::BuildOptions(array("1"=>O_QUIZ, "2"=>O_SURVEY), $selected_type);
$showres_options = webcontrols::BuildOptions(array("1"=>O_YES, "2"=>O_NO), $selected_showres);
$review_options = webcontrols::BuildOptions(array("2"=>O_NO,"1"=>O_YES), $selected_review);
$result_options = webcontrols::BuildOptions(array("1"=>O_POINT, "2"=>O_PERCENT), $selected_results);
$questions_order_options=webcontrols::BuildOptions(array("1"=>BY_PRIORITY, "2"=>RANDOM), $selected_q_order);
$answers_order_options=webcontrols::BuildOptions(array("1"=>BY_PRIORITY, "2"=>RANDOM), $selected_a_order);
//$qtype_options = webcontrols::BuildOptions(array("1"=>ASG_ONCE, "2"=>ASG_NO_LIMIT), $selected_qtype);
$qchange_options = webcontrols::BuildOptions(array("2"=>DONT_AFFECT, "1"=>AFFECT), $selected_qchnage);  
$sending_options = webcontrols::BuildOptions(array("2"=>ASG_SEND_MAN, "1"=>ASG_SEND_AUTO), $selected_send); 
$enablenew_options = webcontrols::BuildOptions(array("2"=>O_NO, "1"=>O_YES), $selected_enablenew_options);  

$calcpen_options = webcontrols::BuildOptions(array("0"=>O_NO, "1"=>L_QST_PEN, "2"=>L_ENT_PEN ), $selected_calc_pen);  
$allow_change_answers_options = webcontrols::BuildOptions(array("1"=>O_YES, "0"=>O_NO ), $selected_allow_change_answers);  
$show_msg_after_qst = webcontrols::BuildOptions(array( "0"=>O_NO ,"1"=>O_YES ), $selected_show_msg_after_qst);  
$show_all_random_options = webcontrols::BuildOptions(array("1"=>ALL_QUESTIONS, "2"=>L_SHOW_RAND_VARIANTS, "3"=>L_SHOW_RAND_USER ), $selected_show_all_random);  
$random_variant_options = webcontrols::BuildNumberOptions(2, sizeof($answer_variants), $selected_random_variant);  
$show_point_info_options = webcontrols::BuildOptions(array( "0"=>O_NO ,"1"=>O_YES ), $selected_point_info);  
$result_templates_res = orm::Select("result_templates", array(), array(), "id");
$result_template_options = webcontrols::GetOptions($result_templates_res,"id","template_name", $selected_res_template, false);
$calcmode_options= webcontrols::BuildOptions(array("3"=>L_ENTERED_POINTS,"1"=>QSTS_POINT, "2"=>ANSWERS_POINT ), $selected_calc_mode);  
$answer_calcmode_options = webcontrols::BuildOptions(array("1"=>MODE_IF_CORRECT, "2"=>MODE_ANYWAY ), $selected_ans_calc_mode);  

$fb_share_options=webcontrols::BuildOptions(array("0"=>DISABLE_SHARE, "1"=>ALLOW_SHARE, "3"=>AUTO_SHARE ), $selected_fb_share);  

$certs = cert::get_all_certs();
$certificate_options = webcontrols::AddOptions(webcontrols::GetSimpleArrayOptions($certs, "text", "text", $selected_cert_name, false), "-1", NO_CERTIFICATE, "-1") ;

$ratings_list = db::GetResultsAsArray(orm::GetSelectQuery("ratings", array(), array(), ""));
$rating_asg_options = webcontrols::GetArrayOptions($ratings_list, "id", "description", $selected_asg_rate);
$rating_qst_options = webcontrols::GetArrayOptions($ratings_list, "id", "description", $selected_qst_rate);


$results = db::GetResultsAsArray(orm::GetSelectQuery("cats", array(), au::arr_where(array()),""));
$cat_options = webcontrols::GetArrayOptions($results, "id", "cat_name", $selected);

$subject_results = db::GetResultsAsArray(orm::GetSelectQuery("quizzes", array(), au::arr_where(array("parent_id"=>0)),""));
$subject_options = webcontrols::GetArrayOptions($subject_results, "id", "quiz_name", "-1",false);

$pres_results  = db::GetResultsAsArray(orm::GetSelectQuery("pres", array(), au::arr_where(array()), ""));
$pres_options = webcontrols::GetArrayOptions($pres_results, "id", "pres_name", "-1",true, NO_PRESENTATION);


//$user_group_res = orm::Select("user_groups", array(), array(),"");
//$user_group_options = webcontrols::GetOptions($user_group_res, "id", "group_name", "-1");

$show_sub_options = webcontrols::BuildOptions(array("1"=>O_YES, "0"=>O_NO ), $selected_show_sub);  
$fail_subject_options = webcontrols::BuildOptions(array("0"=>O_NO,"1"=>O_YES ), $selected_fail_sub);  

$random_type_options = webcontrols::BuildOptions(array("1"=>RANDOM_TOTAL,"2"=>RANDOM_FROM_EACH_SBJ ), $selected_random_type);  

//$allow_change_answers_options = webcontrols::BuildOptions(array("1"=>O_YES, "0"=>O_NO ), $selected_allow_change_answers);  

//$cat_options = webcontrols::AddOptions($cat_options, "-100", QUESTIONS_BANK, "");

$chk_all_html = "<input class='els' type=checkbox name=chkAll2 onclick='grd_select_all(document.getElementById(\"form1\"),\"chklcl\",\"this.checked\")'>";

if(!$mobile)
{
    $hedaers = array($chk_all_html,LOGIN,USER_NAME,USER_SURNAME,EMAIL,VARIANTS);
    $columns = array( "UserName"=>"text", "Name"=>"text","Surname"=>"Surname","email"=>"text","variants"=>"text");
}
else
{
    $hedaers = array($chk_all_html,USER_NAME,USER_SURNAME,VARIANTS);
    $columns = array( "Name"=>"text","Surname"=>"Surname","variants"=>"text");
}

$grd = new extgrid($hedaers,$columns, "index.php?module=add_assignment","tbllocalusers");
//$grd->_column_details = array("variants"=>new ColumnDetails("trVariants"));
$grd->exp_enabled=false;
$grd->delete=false;
$grd->edit=false;
$grd->checkbox=true;
$grd->id_column="UserID";
$grd->selected_ids=$local_user_ids;
$grd->chk_class="chklcl";
$grd->PAGING = 1000000;

$grd->column_override=array("UserName"=>"user_name_override", "variants"=>"variants_override");

function user_name_override($row)
{
    return "<input type=hidden id='hdnchkgrd".$row['UserID']."' value='".$row["group_id"]."' />".$row["UserName"];
}
//"0"=>RANDOM,
function variants_override($row)
{
    global $variant_options,$answer_variants,$local_asg_users;   
    $rowid = $row["UserID"];
    $variant = "-1";
    
    if(isset($_GET['id'])) $variant = db::Select($local_asg_users, "user_id", $rowid, true,"variant_id");
    
    $variant_options = webcontrols::GetArrayOptions($answer_variants, "id", "variant_name", $variant, false);
    $variant_options = webcontrols::AddOptions($variant_options, "0", RANDOM, -1);  
    
    return "<select class=\"slclocal\" id=\"slclcl$rowid\" name=\"slclcl$rowid\" style=\"width:100px\">$variant_options</select>";
}

//$query = users_db::GetUsersQuery(" and disabled=0 and approved=1 ".au::get_where());
//$grd->mobile_grid=false;
//$grd->DrowTable($query);
//$grid_html = $grd->table;

$chk_all_html = "<input class='els' type=checkbox name=chkAll2 onclick='grd_select_all(document.getElementById(\"form1\"),\"chkimp\",\"this.checked\")'>";
$chk_ldap_all_html = "<input class='els'  type=checkbox name=chkAll4 onclick='grd_select_all(document.getElementById(\"form1\"),\"chkldap\",\"this.checked\")'>";
if(!$mobile)
{
    $hedaers_imp = array($chk_all_html,LOGIN,USER_NAME,USER_SURNAME,EMAIL,VARIANTS);
    $hedaers_ldap = array($chk_ldap_all_html,LOGIN,USER_NAME,USER_SURNAME,EMAIL,VARIANTS);
    $columns_imp = array("UserName"=>"text", "Name"=>"text","Surname"=>"Surname","email"=>"text","variants"=>"text");
}
else
{
    $hedaers_imp = array($chk_all_html,USER_NAME,USER_SURNAME,VARIANTS);
    $hedaers_ldap = array($chk_ldap_all_html,USER_NAME,USER_SURNAME,VARIANTS);
    $columns_imp = array( "Name"=>"text","Surname"=>"Surname","variants"=>"text");
}

$grd_imp = new extgrid($hedaers_imp,$columns_imp, "index.php?module=add_assignment");
$grd_imp->exp_enabled=false;
$grd_imp->delete=false;
$grd_imp->edit=false;
$grd_imp->checkbox=true;
$grd_imp->id_column="UserID";
$grd_imp->identity="imp";
$grd_imp->selected_ids=$imp_user_ids;
$grd_imp->chk_class="chkimp";
$grd_imp->PAGING = 1000000;

$grd_imp->column_override=array("UserName"=>"user_name_override_imp", "variants"=>"variants_override_imp");

function user_name_override_imp($row)
{
    return "<input type=hidden id='hdnchkgrdimp".$row['UserID']."' value='".$row["group_id"]."' />".$row["UserName"];
}

function variants_override_imp($row)
{
    global $variant_options,$answer_variants,$imp_asg_users;   
    $rowid = $row["UserID"];
    $variant = "-1";
    
    if(isset($_GET['id'])) $variant = db::Select($imp_asg_users, "user_id", $rowid, true,"variant_id");
    
    $variant_options = webcontrols::GetArrayOptions($answer_variants, "id", "variant_name", $variant, false);
    $variant_options = webcontrols::AddOptions($variant_options, "0", RANDOM, -1);  
    
    return "<select class=\"slcimport\" id=\"slcimp$rowid\" name=\"slcimp$rowid\" style=\"width:100px\">$variant_options</select>";
}





$grd_ldap = new extgrid($hedaers_ldap,$columns, "index.php?module=add_assignment");
//$grd->_column_details = array("variants"=>new ColumnDetails("trVariants"));
$grd_ldap->exp_enabled=false;
$grd_ldap->delete=false;
$grd_ldap->edit=false;
$grd_ldap->checkbox=true;
$grd_ldap->id_column="UserID";
$grd_ldap->selected_ids=$ldap_user_ids;
$grd_ldap->chk_class="chkldap";
$grd_ldap->identity="ldap";
$grd_ldap->PAGING = 1000000;
$grd_ldap->column_override=array("UserName"=>"user_name_override_ldap", "variants"=>"variants_override_ldap");
$grd_ldap->mobile_grid=false;


function user_name_override_ldap($row)
{
    return "<input type=hidden id='hdnchkgrdldap".$row['UserID']."' value='".$row["group_id"]."' />".$row["UserName"];
}

function variants_override_ldap($row)
{
    global $variant_options,$answer_variants,$ldap_asg_users;   
    $rowid = $row["UserID"];
    $variant = "-1";
    
    if(isset($_GET['id'])) $variant = db::Select($ldap_asg_users, "user_id", $rowid, true,"variant_id");
    
    $variant_options = webcontrols::GetArrayOptions($answer_variants, "id", "variant_name", $variant, false);
    $variant_options = webcontrols::AddOptions($variant_options, "0", RANDOM, -1);  
    
    return "<select class=\"slcldap\" id=\"slcldap$rowid\" name=\"slcldap$rowid\" style=\"width:100px\">$variant_options</select>";
}

function _post($var,$default=0)
{
    return isset($_POST[$var]) ? $_POST[$var] : $default;
}

if(isset($_POST["btnSave"]) && $val->IsValid())
{    
     
    $db = new db();
    $db->connect();
    $db->begin();

    $selected_quiz_type=$_POST["drpType"];
    $selected_show_res = $_POST["drpShowRes"];
    $show_questions = $_POST['drpIsRandom'];
    $drpVariants = $show_questions =="2" ? $_POST["drpVariants"] : "0";
    $affect_changes = isset($_POST["drpQChange"]) ? $_POST["drpQChange"] : 2;
   
    if($selected_quiz_type=="2") $selected_show_res="2";
    try
    {
        $org_quiz_id=$_POST["drpTests"];        
        $quiz_id = "-1";
        
        $cert_enabled = "0";
        $cert_name =  isset($_POST['drpCert']) ?  $_POST['drpCert'] : "-1";
        if($cert_name!="-1")
        {
            $cert_enabled = "1";
            $cert_name = $_POST['drpCert'];
        }                                      
      
       if($asg_status==0)
       {
            if($show_questions=="1")
            {      
               //  if(isset($_GET['id'])) {if($copied_quiz_id!=$selected_test && $selected_qchnage!=1) $db->query(quizDB::DeleteChildQuizByIdQuery($copied_quiz_id)); }

              //   if($org_quiz_id!="-100")
              //   {
                      //  if($affect_changes == "2")
                        $quiz_id = CopyQuiz();                   
                        //else $quiz_id = $org_quiz_id;                
               //  }
               //  else
               //  {
               //         $quiz_id = CreateQuizFromBank();
               //  }
             }
             else if($show_questions=="2")                  
             {
                 if($regen_mode==false || isset($_POST['chkRegen']))
                 $variant_quizzes=GenerateRandomQuizzes();                                 
             }
           
        }
       
        $fb_users_list=0;
        $ldap_users_list=0;
        if(isset($_POST['drpfbgroup'])) $fb_users_list= $_POST['drpfbgroup'];
        if(isset($_POST['drpldapgroup'])) $ldap_users_list= $_POST['drpldapgroup'];
       
        if(!isset($_GET["id"]))
        {
            add_file();    
            $arr_insert = au::add_insert(array("quiz_id"=>$quiz_id,
                                                   "results_mode"=>$_POST["drpResultsBy"],
                                                   "added_date"=>util::Now(),
                                                   "quiz_time"=>trim($_POST["txtTestTime"]),
                                                   "show_results"=>$selected_show_res,
                                                   "org_quiz_id"=>$org_quiz_id,
                                                   "pass_score"=>$_POST["txtSuccessP"],
                                                   "quiz_type"=>$selected_quiz_type,
                                                    "quiz_type"=>$_POST["drpType"],
                                                    "status"=>"0",
                                                    "qst_order"=>$_POST["drpQO"],
                                                    "answer_order"=>$_POST["drpAO"],
                                                    "allow_review"=>_post("drpAR","2"),
						    "limited"=>$_POST["txtHowMany"],
                                                    "affect_changes"=>$affect_changes,
                                                    "send_results"=>$_POST["drpSendRes"],
                                                    "accept_new_users"=>$_POST["drpNewUsers"],
                                                    "assignment_name"=>$_POST['txtAssignmentName'],
                                                    "asg_quiz_type"=>$org_quiz_id=="-100" ? 2 : 1,
                                                    "allow_change_prev"=>_post("drpAllowBack", 1),
                                                    "show_success_msg"=>$_POST['drpShowSuccess'],
                                                    "show_point_info"=>$_POST['drpShowPointInfo'],
                                                    "is_random"=>$_POST['drpIsRandom'],
                                                    "random_qst_count"=>$_POST['txtRandom'],
                                                    "variants"=>$_POST['drpVariants'],
                                                    "show_intro"=>isset($_POST["chkShowIntro"]) ? 1:0,
                                                    "intro_text"=>$_POST["editor1"],
                                                    "asg_cat_id"=>$_POST["drpCats"],
                                                    "results_template_id"=>$_POST['drpResTemp'],
                                                    "cert_enabled"=>$cert_enabled,
                                                    "cert_name"=>$cert_name,
                                                    "fb_users_list"=>$fb_users_list,
                                                    "ldap_users_list"=>$ldap_users_list,
                                                    "fb_share"=>$_POST['drpShare'],
                                                    "fb_allow_comments"=>$_POST['drpComments'],
                                                    "short_desc"=>$_POST['txtSHD'],
                                                    "asg_image"=>$filename,
                                                    "v_start_time"=>$_POST['datetimepicker_start'],
                                                    "v_end_time"=>$_POST['datetimepicker_end'],
                                                    "mails_copy"=>trim($_POST['txtMailCopy']),                                                                                   
                                                    "calc_mode"=>util::post('drpCalcMode',1),
                                                    "ans_calc_mode"=>trim($_POST['drpAnsCalcMode']),
                                                    "show_subject_name"=>util::GetPost("drpShowSubName","1"),
                                                    "fail_sbj_exam"=>util::GetPost("drpSbjFail","0"),
                                                    "random_type"=>$_POST['drpRandomType'],
                                                    "asg_cost"=>$_POST['txtCost'],
                                                    "asg_rate_id"=>$_POST['drpAsgRate'],
                                                    "qst_rate_id"=>$_POST['drpQstRate'] ,
                                                    "point_koe"=>util::post('txtPointKoe',1),
                                                    //"tpoint_calc_mode"=>$_POST['drpCalcMode'],
                                                    "calc_pen"=>util::post('drpCalcPen',1)
                                                    
                                                   ));
            if($_POST['datetimepicker_start']=="") $arr_insert['v_start_time'] = array("null",true);
            if($_POST['datetimepicker_end']=="") $arr_insert['v_end_time'] = array("null",true);                    
            
                     
            assignment_adding($arr_insert);
            $query = orm::GetInsertQuery("assignments", $arr_insert);
         
            $db->query($query);
            $asg_id = $db->last_id();
                                   
        }
        else
        {
            $asg_id = $id;
            if($selected_show_all_random=="2" && $asg_status==0 )
            {
                if($regen_mode==false || isset($_POST['chkRegen'])) {
                $query  = orm::GetDeleteQuery("variant_quizzes", array("asg_id"=>$asg_id));              
                $db->query($query);
                }
            }
            add_file();
            $arr_update = au::add_update(array("quiz_id"=>$quiz_id,
                                                  // "results_mode"=>$_POST["drpResultsBy"],
                                                   //"added_date"=>util::Now(),
                                                   "quiz_time"=>trim($_POST["txtTestTime"]),
                                                    "org_quiz_id"=>$org_quiz_id,
                                                   "show_results"=>$selected_show_res,
                                                   "pass_score"=>$_POST["txtSuccessP"],
                                                   "quiz_type"=>$selected_quiz_type,
                                                    "quiz_type"=>$_POST["drpType"],
                                                    "qst_order"=>$_POST["drpQO"],
                                                    "answer_order"=>$_POST["drpAO"],
                                                    "allow_review"=>_post("drpAR","2"),
						    "limited"=>$_POST["txtHowMany"],
                                                    "affect_changes"=>$affect_changes,
                                                    "send_results"=>$_POST["drpSendRes"],
						    "accept_new_users"=>$_POST["drpNewUsers"],
                                                     "assignment_name"=>$_POST['txtAssignmentName'],
                                                    "asg_quiz_type"=>$org_quiz_id=="-100" ? 2 : 1,
                                                    "allow_change_prev"=>_post("drpAllowBack", 1),
                                                    "show_success_msg"=>$_POST['drpShowSuccess'],
                                                    "show_point_info"=>$_POST['drpShowPointInfo'],
                                                    "is_random"=>$_POST['drpIsRandom'],
                                                    "random_qst_count"=>$_POST['txtRandom'],
                                                    "variants"=>$_POST['drpVariants'],
                                                    "show_intro"=>isset($_POST["chkShowIntro"]) ? 1:0,
                                                    "intro_text"=>$_POST["editor1"],
                                                    "asg_cat_id"=>$_POST["drpCats"],
                                                    "results_template_id"=>$_POST['drpResTemp'],
                                                    "cert_enabled"=>$cert_enabled,
                                                    "cert_name"=>$cert_name,
                                                    "fb_users_list"=>$fb_users_list,
                                                    "ldap_users_list"=>$ldap_users_list,
                                                    "fb_share"=>$_POST['drpShare'],
                                                    "fb_allow_comments"=>$_POST['drpComments'],
                                                    "short_desc"=>$_POST['txtSHD'],
                                                //    "asg_image"=>$filename,
                                                    "v_start_time"=>$_POST['datetimepicker_start'],
                                                    "v_end_time"=>$_POST['datetimepicker_end'],
                                                    "mails_copy"=>trim($_POST['txtMailCopy']),
                                                    "calc_mode"=>trim($_POST['drpCalcMode']),
                                                    "ans_calc_mode"=>trim($_POST['drpAnsCalcMode']),
                                                    "show_subject_name"=>util::GetPost("drpShowSubName","1"),
                                                    "fail_sbj_exam"=>util::GetPost("drpSbjFail","0"),
                                                    "random_type"=>$_POST['drpRandomType'],
                                                    "asg_cost"=>$_POST['txtCost'],
                                                    "asg_rate_id"=>$_POST['drpAsgRate'],
                                                    "qst_rate_id"=>$_POST['drpQstRate'],
                                                    "point_koe"=>$_POST['txtPointKoe']
                                                   // "status"=>"1" ,
                                                   ));
            
             if($_POST['datetimepicker_start']=="") $arr_update['v_start_time'] = array("null",true);
             if($_POST['datetimepicker_end']=="") $arr_update['v_end_time'] = array("null",true);
            
             if($asg_status>0)
             {
                 unset($arr_update['quiz_id']);
                 unset($arr_update['affect_changes']);
                 unset($arr_update['org_quiz_id']);
                 unset($arr_update['quiz_type']);
                 unset($arr_update['asg_quiz_type']);
                 unset($arr_update['is_random']);
                 unset($arr_update['variants']);
                 unset($arr_update['random_qst_count']);
                 unset($arr_update['asg_cat_id']);
                 unset($arr_update['fb_users_list']);
                 unset($arr_update['ldap_users_list']);
                 unset($arr_update['calc_mode']);
                 unset($arr_update['ans_calc_mode']);
                 unset($arr_update['random_type']);
             }
             
             if($mobile)
             {
                 unset($arr_update['show_subject_name']);
                 unset($arr_update['fail_sbj_exam']);
             }
            
             if(trim($filename)!="")  
             {
                    $arr_update["asg_image"]=$filename;
                    if(trim($asg_img_file)!="")
                    {
                            @unlink("asg_images".DIRECTORY_SEPARATOR.$asg_img_file);                            
                    }
             }
            
            assignment_editing($asg_id,$arr_update);
            $query = orm::GetUpdateQuery("assignments", $arr_update ,
                                                   array("id"=>$id)
                                         );
            $db->query($query);
            
           
            if($asg_status==0 && ($regen_mode==false || isset($_POST['chkRegen'])) ) $db->query(orm::GetDeleteQuery("assignment_users", array("assignment_id"=>$asg_id)));
            if(isset($_POST['mltSubjectList'])) $db->query (orm::GetDeleteQuery ("assignment_subjects", array("asg_id"=>$asg_id)));
            //$db->query(questions_db::GetGroupDeleteQuery($question_id));
        }
        
        if(isset($_POST['mltSubjectList']))
        {
            foreach ($_POST['mltSubjectList'] as $subject_details)
            {
                   // list($subject_id, $subject_point, $pres_id, $pres_duration) = split(';|', $subject_details);
                   // echo base64_decode($subject_id);
                $details = explode(';|',$subject_details);
                $subject_id =  base64_decode($details[0]);
                $subject_point =  base64_decode($details[1]);
                $pres_id =  base64_decode($details[2]);
                $pres_duration =  base64_decode($details[3]);
                                    
                $query = orm::GetInsertQuery("assignment_subjects", array("asg_id"=>$asg_id,"subject_id"=>$subject_id, "min_subject_point"=>$subject_point, "pres_id"=>$pres_id, "pres_duration"=>$pres_duration));
                $value = $db->query_single_value(orm::GetSelectQuery("assignment_subjects", array("subject_id"), array("asg_id"=>$asg_id,"subject_id"=>$subject_id), ""), "subject_id");
                if($value=="") $db->query($query);
             }
        } 

        if($asg_status==0 && ($regen_mode==false || isset($_POST['chkRegen']))) {
            
        $chkgrddiffs=$_POST['chkgrddiffs'];
        for($i=0;$i<count($chkgrddiffs);$i++)
        {
            $view_priority = $_POST["txtQuizPrior".$chkgrddiffs[$i]];
            $db->query(orm::GetInsertQuery("asg_qbank_quizzes", array("asg_id"=>$asg_id,"view_priority"=>$view_priority,"quiz_id"=>$chkgrddiffs[$i])));
            
            if(trim($_POST['hdnQ'.$chkgrddiffs[$i]])=="") continue;
            $theme_list = explode(",", $_POST['hdnQ'.$chkgrddiffs[$i]]);
            foreach($theme_list as $theme)
            {         
                $theme = db::clear($theme);
                $db->query(orm::GetInsertQuery("assignment_themes_xreff", array("asg_id"=>$asg_id,"theme_id"=>$theme, "quiz_id"=>$chkgrddiffs[$i])));
            }
        }            
            
        $chkgrdgroup=$_POST['chkgrdgroups'];                  
     
        while (list ($vid,$group_id) = @each ($chkgrdgroup))
        {                
                $query_grp = orm::GetInsertQuery("assignment_usergroup_xreff", array(                                                                       
                                                                       "asg_id"=>$asg_id,
                                                                       "group_id"=>$group_id
                                            ));
                 $db->query($query_grp);
        }    
                
        
        $chkgrddiffs=$_POST['chkgrddiffs'];                    
        while (list ($did,$d_quiz_id) = @each ($chkgrddiffs))
        {
            for($i=0;$i<count($qst_diff_leves_res);$i++)
            {
                $diff_id= $qst_diff_leves_res[$i]['id'];
            
                if(isset($_POST['txtHPoints_'.$d_quiz_id.'_'.$diff_id]))
                {
                    $pen_point = floatval(util::post('txtPenPoint_'.$d_quiz_id.'_'.$diff_id));                    
                    $qst_count = intval(util::post('txtDiffLevel_'.$d_quiz_id.'_'.$diff_id));
                    $diff_point = floatval(util::post('txtDiffPoint_'.$d_quiz_id.'_'.$diff_id));
                    $query_diff = orm::GetInsertQuery("assignment_diff_level_xreff", array(                                                                       
                                                                           "asg_id"=>$asg_id,
                                                                           "diff_id"=>$diff_id,
                                                                           "diff_point"=>$diff_point,
                                                                           "pen_point"=>$pen_point,
                                                                            "qst_count"=>$qst_count,
                                                                            "quiz_id"=>$d_quiz_id
                                                ));
                    $db->query($query_diff);                                        
                }
            }
        }        
            
        $chkgrd=$_POST['chkgrd'];  
        
        $user_variants = randomize_user_quizzes($chkgrd,"slclcl",true);
     
        while (list ($user_id,$variant_id) = @each ($user_variants))
        {
            $column = $show_questions=="3" ? "u_quiz_id" : "variant_id";
            $query_lcl = orm::GetInsertQuery("assignment_users", array("assignment_id"=>$asg_id,
                                                                   "user_type"=>"1",                                                                       
                                                                   "user_id"=>$user_id,
                                                                   $column=>$variant_id
                                        ));
             $db->query($query_lcl);
        }
    
        
        $chkgrdimp=$_POST['chkgrdimp'];
        
        $user_variants = randomize_user_quizzes($chkgrdimp, "slcimp",false);        
        
        while (list ($user_id,$variant_id) = @each ($user_variants))
        {
             $column = $show_questions=="3" ? "u_quiz_id" : "variant_id";
             $query_imp = orm::GetInsertQuery("assignment_users", array("assignment_id"=>$asg_id,
                                                                       "user_type"=>"2",
                                                                       "user_id"=>$user_id,
                                                                       $column=>$variant_id
                                            ));
             $db->query($query_imp);
        }
       
        if($fb_users_list==2)
        {
            while (list ($email,$email_id) = @each ($_POST['drpFBUsers']))
            {
                $fb_user_id = -1;
                $default_branch_query = orm::GetSelectQuery("branches", array("id"), array("system_row"=>1), "",false,false);
                $default_user_role_query = orm::GetSelectQuery("roles", array("id"), array("system_row"=>2), "",false,false);
                $fbresults = $db->query(orm::GetSelectQuery("app_users", array("UserID"), array("email"=>trim($email_id),"app_id"=>"3"), ""));
                if($db->num_rows($fbresults)>0)
                {
                    $fb_row = db::fetch($fbresults);
                    $fb_user_id = $fb_row["UserID"];
                }
                else
                {
                    $fb_user_id=$db->insert_query(orm::GetInsertQuery("app_users", array("email"=>$email_id,"disabled"=>0,"app_id"=>3,"branch_id"=>array("($default_branch_query)",false),"user_type"=>array("($default_user_role_query)",false)), true));                    
                }
                
                $fb_variant = 0;
                if($show_questions=="2") 
                {
                    $fb_variant = array_rand($variant_quizzes);
                }
                
                $column ="variant_id";
                $column_value = $fb_variant;
                
                if($show_questions=="3")
                {
                     $column ="u_quiz_id";
                     $column_value = CopyQuiz();
                }
                
                $query_fb = orm::GetInsertQuery("assignment_users", array("assignment_id"=>$asg_id,
                                                                       "user_type"=>"3",
                                                                        "already_checked"=>1,
                                                                       "user_id"=>$fb_user_id,
                                                                       "variant_id"=>$fb_variant
                                            ));
                $db->query($query_fb);
            }
        }
        
        $chkgrdldap=$_POST['chkgrdldap'];
        $user_variants = randomize_user_quizzes($chkgrdldap, "slcldap",false);  
        
        while (list ($user_id,$variant_id) = @each ($user_variants))
        {
             
             $column = $show_questions=="3" ? "u_quiz_id" : "variant_id";
             $query_ldap = orm::GetInsertQuery("assignment_users", array("assignment_id"=>$asg_id,
                                                                       "user_type"=>"4",
                                                                        "already_checked"=>1,
                                                                       "user_id"=>$user_id,
                                                                       $column=>$variant_id
                                            ));
             $db->query($query_ldap);
        }
      
        if($ldap_users_list==2 && $mobile==false)
        {
            while (list ($email,$email_id) = @each ($_POST['drpLDAPUsers']))
            {
                $ldap_user_id = -1;
                $default_branch_query = orm::GetSelectQuery("branches", array("id"), array("system_row"=>1), "",false,false);
                $default_user_role_query = orm::GetSelectQuery("roles", array("id"), array("system_row"=>2), "",false,false);
                $ldapresults = $db->query(orm::GetSelectQuery("app_users", array("UserID"), array("email"=>trim($email_id),"app_id"=>"4"), ""));
                if($db->num_rows($ldapresults)>0)
                {
                    $ldap_row = db::fetch($ldapresults);
                    $ldap_user_id = $ldap_row["UserID"];
                }
                else
                {
                    $ldap_user_id=$db->insert_query(orm::GetInsertQuery("app_users", array("email"=>$email_id,"disabled"=>0,"app_id"=>4,"branch_id"=>array("($default_branch_query)",false),"user_type"=>array("($default_user_role_query)",false)), true));                    
                }
               
                $ldap_variant = 0;
                if($show_questions!="1") 
                {
                    $ldap_variant = array_rand($variant_quizzes);
                }
                
                $check_query = orm::GetSelectQuery("assignment_users", array(), array("assignment_id"=>$asg_id,"user_type"=>"4","user_id"=>$ldap_user_id), "");
                
                $asg_user_res = $db->query($check_query);
                
                $column ="variant_id";
                $column_value = $ldap_variant;
                
                if($show_questions=="3")
                {
                     $column ="u_quiz_id";
                     $column_value = CopyQuiz();
                }
                
                $query_ldap = orm::GetInsertQuery("assignment_users", array("assignment_id"=>$asg_id,
                                                                       "user_type"=>"4",
                                                                        "already_checked"=>1,
                                                                       "user_id"=>$ldap_user_id,
                                                                       $column=>$column_value
                                            ));
                
                if(db::num_rows($asg_user_res)==0) $db->query($query_ldap);
            }
        }
        
        if($show_questions=="3")
        questions_util::CopyAsgQuestionsBulk($asg_id);
       
        }
        
        if(!isset($_GET["id"])) assignment_added($db,$asg_id, $arr_insert, $chkgrd, $chkgrdimp);        
        else assignment_edited($db,$asg_id, $arr_update, $chkgrd, $chkgrdimp);      

        $db->commit();
        util::redirect("?module=assignments&asg_id=$asg_id");

    }
    catch(Exception $e)
    {
        echo $e->getMessage();
        $db->rollback();
    }
    $db->close_connection();
}

function randomize_user_quizzes($chkgrd,$drpKey,$insert)
{
        global $variant_quizzes,$show_questions,$db,$variant_count,$user_variants;
        global $drpVariants,$answer_variants,$asg_id,$org_quiz_id;
        $user_variants = array();                    
        
        if($show_questions=="2")
        {
            if($insert==true)
            {
                while (list ($variant_id,$variant_quiz_id) = @each ($variant_quizzes))
                {                    
                     $db->query(orm::GetInsertQuery("variant_quizzes", array("variant_id"=>$variant_id, "quiz_id"=>$variant_quiz_id, "asg_id"=>$asg_id)));                    
                } 
            }

            for($i=0;$i<sizeof($answer_variants);$i++)
            {
                if($i==$drpVariants) break;
                $variant_count[$answer_variants[$i]["id"]] = 0;
            }
        }
        
        while (list ($key,$val) = @each ($chkgrd))
        {
            if($show_questions=="1")
            {
                $user_variants[$val] = 0;
            }
            else if($show_questions=="2")
            {       
                 $slc_user_variant=$_POST[$drpKey.$val];
                 if($slc_user_variant!="0") $variant_count[$slc_user_variant]++;
                
                 $user_variants[$val] = $slc_user_variant;                                 
            }
            else if($show_questions=="3")
            {                
                if($org_quiz_id!="-100")
                { 
                   $last_quiz_id = CopyQuiz($val);
                }
                else
                {
                   $last_quiz_id = CreateQuizFromBank(true,$val);
                }
                
                $user_variants[$val] = $last_quiz_id;                                                  
              //  $user_variants[$val] = -1;
            }
        
        }
        if($show_questions=="2")
        $user_variants = randomize_user_variants($user_variants);
        
        return $user_variants;
}

function randomize_user_variants($user_variants)
{
    global $variant_count;
    $randomized_user_variants = $user_variants;
    while (list ($key,$val) = @each ($user_variants))
    {
        if($val=="0")
        {
            $random_selected_variant = get_minimum_used_variant();
            $randomized_user_variants[$key] = $random_selected_variant;             
            $variant_count[$random_selected_variant]++;            
        }       
    }
    
    return $randomized_user_variants;
}

function get_minimum_used_variant()
{   
    
    global $variant_count;        

    $min = reset($variant_count); 
    $min_key= key($variant_count);

    while (list ($key,$val) = @each ($variant_count))
    {      
        
        if($key=="0") continue;

        if($min>=$val)
        {                     
            $min = $val; 
            $min_key= $key;
        }
        
    }
    
    return $min_key;
}

function qst_count_override($row)
{
    return $row['qst_count']."<input type=hidden name='hdnQ".$row['id']."' id='hdnQ".$row['id']."' />";
}

if(isset($_POST["ajax"]))
{
         if(isset($_POST["fill_tests"]))
         {             
            $cat_id=$_POST["cat_id"];            
            $results_test = orm::Select("quizzes", array(), au::arr_where(array("cat_id"=>$cat_id,"parent_id"=>"0")),"");
            $add_options = webcontrols::BuildOptions(array("-100"=>QUESTIONS_BANK), $selected_test_bnk, "style='font-weight: bold'");
            $attr = $asg_status>0 ? "disabled" : "";
            $attr.=" class='form-control'";
            $tests_drop = webcontrols::GetDropDown("drpTests",$results_test, "id", "quiz_name", $selected_test,$add_options,$attr);
            echo $tests_drop;
         }
         if(isset($_POST["tests_grid"]))
         {             
            $cat_id=db::clear($_POST["cat_id"]);            
            //$results_test = orm::Select("quizzes", array(), au::arr_where(array("cat_id"=>$cat_id,"parent_id"=>"0")),"");
           /// $tests_query = quizDB::GetQuizQuery(" and q.cat_id=$cat_id ");
            $tests_query = quizDB::GetQuizAndCount(" and q.cat_id=$cat_id ");
            
            $t_headers = array("&nbsp;",QUIZ_NAME,L_QST_COUNT,"&nbsp;");
            $t_columns = array("quiz_name"=>"text","qst_count"=>"text");
            $grd_tests = new extgrid($t_headers,$t_columns,"?module=add_assignment","tblquizzes");
            $grd_tests->exp_enabled=false;
            $grd_tests->column_override=array("qst_count"=>"qst_count_override");
            $grd_tests->delete=false;
            $grd_tests->checkbox=true;
            $grd_tests->edit=false;
            $grd_tests->chk_class='chk_diffs';
            $grd_tests->identity='diffs';
            $grd_tests->selected_ids=$selected_quiz_ids;
            $grd_tests->register_checkbox_click=true;
            $grd_tests->jslinks=array(L_SETTINGS=>"load_subject_settings([id])");
            if($id!=-1) $grd_tests->chk_attrs="disabled";
            $grd_tests->empty_data_text=L_SELECT_CAT;
    //	$grd_tests->jslinks=array("Sual sayı"=>"LoadSubjectDetails(\"[id]\")");
            $grd_tests->DrowTable($tests_query);
            $tests_html = $grd_tests->table;
			            
            echo $tests_html;
         }
         if(isset($_POST['load_users']) )
         {
             if(isset($_POST['chkboxes']))
             {
                 $chkboxes = $_POST['chkboxes'];
                 $in = db::clear(db::arr_to_in($chkboxes));             
             }
             else $in = "-20";
            
            $query = users_db::GetUsersQuery(" and disabled=0 and approved=1 and group_id in ($in) ".au::get_where());            
            $grd->mobile_grid=false;
            $grd->checkbox_all_checked=true;
            $grd->DrowTable($query);
            $grid_html = $grd->table;
            
            $query_imp = users_db::GetImportedUsersQuery(" and group_id in ($in) ");
            $grd_imp->mobile_grid=false;
            $grd_imp->checkbox_all_checked=true;
            $grd_imp->DrowTable($query_imp);
            $imported_grid_html = $grd_imp->table;
            
            $query_ldap = users_db::GetLDAPUsersQuery(" and group_id in ($in) ");
            $grd_ldap->checkbox_all_checked=true;
            $grd_ldap->DrowTable($query_ldap);            
            $ldap_grid_html = $grd_ldap->table;                        
            
            echo json_encode(array("div_grid"=>$grid_html,"div_grid_imp"=>$imported_grid_html,"div_grid_ldap"=>$ldap_grid_html));
            
         }
         if(isset($_GET["bank"]))
         {
            $html = GetQuestionsBank();
            echo $html;
         }
         
         if(isset($_POST['load_subject_themes']))
         {
             $quiz_id = util::GetInt($_POST['quiz_id']);
             $theme_headers= array("&nbsp;",SUBJECT);
             $theme_columns = array("subject_name"=>"text");
             $t_grd = new extgrid($theme_headers, $theme_columns,"");             
             $t_grd->delete=false;
             $t_grd->edit=false;
             $t_grd->checkbox=true;
             $t_grd->exp_enabled=false;
             $t_grd->register_checkbox_click=true;
             $t_grd->identity='themes';
             $t_grd->chk_class='chk_themes';
             $t_grd->selected_ids = explode(",",$_POST['selchkboxes']);
             $query = orm::GetSelectQuery("subjects", array("subject_name","id"), array("quiz_id"=>$quiz_id), "");
             $query.=" union select '".db::clear(OTHERS)."' as subject_name, -1 as id  order by id desc ";
             $t_grd->DrowTable($query);
             echo $t_grd->table;
         }
         
        if(isset($_POST['load_diff_levels']))
        {
            $results_tmp = "<table border=0>";
            $chkboxes = $_POST['chkboxes'];
            
            $in = db::clear(db::arr_to_in($chkboxes));
            
            $quiz_results = db::GetResultsAsArray(quizDB::get_quiz_by_id_list($in));
            
            $quiz_count_results = db::GetResultsAsArray(quizDB::get_quiz_count_by_id_list($in));
            
            for($z=0;$z<count($chkboxes);$z++)
            {                    
                    $load_results = LoadDiffLevels($quiz_results[$z],$quiz_count_results);								
                    $st_begin = "";					
                    if($z==0)
                    {
                            $st_begin = "<tr>";						
                    }
                    else if($z%2==0)
                    {
                            $st_begin = "</tr><tr>";						
                    }
                    $results_tmp.= "$st_begin<td>$load_results</td><td style='width:50px'>&nbsp;</td>";
            }
            $results_tmp.= "</tr></table>";
            echo $results_tmp;
            exit();
        }
         
}

function GetQuestionsBank()
{
    $chk_attr = "";
    global $bank_questions_ids,$asg_status;
    if($asg_status>0) $chk_attr = "disabled";
    $chk_all_html = "<input $chk_attr type=checkbox class='els'  name=chkAll2 onclick='grd_select_all(document.getElementById(\"form1\"),\"chk_qst\",\"this.checked\")'>";
    
    $hedaers = array($chk_all_html,QUESTION, TYPE, POINT, "&nbsp;");
    $columns = array( "question_text"=>"text","question_type"=>"text" ,"point"=>"text");
// for($i=0;$i<count($bank_questions_ids);$i++) echo $bank_questions_ids[$i]."<br>";
    $grd = new extgrid($hedaers,$columns, "index.php?module=add_assignment&bank=1");
    $grd->exp_enabled=false;
    $grd->selected_ids=$bank_questions_ids;
    $grd->edit=false;
    $grd->delete=false;
    $grd->column_override=array("question_type"=>"question_type_override");
    $leftC = 0;
    if($grd->mobile) $leftC = "-1";
    $grd->jslinks=array(PREVW=>"ShowPreview(\"[id]\",event.pageY, $leftC)");
    $grd->auto_id=false;
    $grd->checkbox=true;
    $grd->chk_class="chk_qst";
    $grd->identity="bank";
    $grd->search=true;
    $grd->search_mode=2;
    $grd->grid_control_name="divQstBank";
    $grd->PAGING = 1000000;
    $grd->chk_attrs=$chk_attr;
    $where = "";
    if(isset($_POST['filter_cats']))
    {
        $cat_str = $_POST['filter_cats'];
        if(trim($cat_str)!="") 
        {
            $cat_str = substr ($cat_str, 1);
            $cat_str = db::clear($cat_str);

            $where.= " and ifnull(qz.cat_id,0) in ($cat_str) ";
        }
    }
    
    if(isset($_POST['filter_subjects']))
    {
        $sub_str = $_POST['filter_subjects'];
        if(trim($cat_str)!="") 
        {
            $sub_str = substr ($sub_str, 1);
            $sub_str = db::clear($sub_str);

            $where.= " and ifnull(q.subject_id,-1) in ($sub_str) ";
        }
    }
    
    $search_html = $grd->DrowSearch(array(QUESTION),array("question_text"));
    
    $query = questions_db::GetQuestionsBankQuery($where); 
    $grd->mobile_grid=false;
    $grd->DrowTable($query);
    
    if(!isset($_POST['first_load']))
    return $grd->table;
    
    return json_encode(array("grid_html"=>$grd->table,"search_html"=>$search_html));
}

function question_type_override($row)
{
    global $QUESTION_TYPE;
    return $QUESTION_TYPE[$row['question_type_id']];
}

function array_random_assoc($arr, $num = 1) {
    $keys = array_keys($arr);
    shuffle($keys);
    
    $r = array();
    for ($i = 0; $i < $num; $i++) {
        $r[$i] = $arr[$keys[$i]];
    }
    return $r;
}

function GenerateRandomQuizzes()
{
    global $answer_variants;
    global $org_quiz_id;
    global $db;        
    global $drpVariants;        
    
         
    for($i=1;$i<sizeof($answer_variants)+1;$i++)
    {        
        if($org_quiz_id!="-100")
        { 
           $last_quiz_id = CopyQuiz();
        }
        else
        {           
           $last_quiz_id = CreateQuizFromBank(true);
        }
       $variant_quizzes[$i] = $last_quiz_id;

       if($i==$drpVariants) break;
    }            
    return $variant_quizzes;
}

function CreateQuizFromBank($randomize=false)
{

    $date = util::Now();
    global $db;
    $query = orm::GetInsertQuery("quizzes", array("cat_id"=>$_POST['drpCats'], "quiz_name"=>$_POST['txtAssignmentName'],
                                                  "added_date"=>util::Now(), "parent_id"=>"-100"
        ));
    
    $last_quiz_id=$db->insert_query($query);
        
    
    $chkgrdbank=$_POST['chkgrdbank'];
    $str_in = "";
    while (list ($key,$val) = @each ($chkgrdbank))
    {
          $str_in.=",".$val;
    }
    $str_in = db::clear("-1".$str_in);
 
    
    $random_qst_count = intval(db::clear($_POST['txtRandom']));
    
    if($randomize==true)
    {          
          $group_by_subject = $_POST['drpRandomType'] == "1" ? false : true;
          $db->query("set @type = ''");
          $db->query("set @num  = 0");
          $res_qst = $db->query_as_array(questions_db::GetRandomizedQuestions($random_qst_count, " and id in($str_in) ", $group_by_subject));
         // $qst_count = intval($_POST['txtRandom']) > sizeof($res_qst) ? sizeof($res_qst) : intval($_POST['txtRandom']);
         // $res_qst = array_random_assoc($res_qst, $qst_count) ;
    }
    else $res_qst = $db->query_as_array("select * from questions where id in($str_in)");
    
    //CopyQuestions($res_qst,$last_quiz_id);
    questions_util::CopyQuestions($db, $res_qst, $last_quiz_id,false);
    
    return $last_quiz_id;
}

function GetQuizIDByStep($step)
{
    $return_id = 0;
    $i = 1;
    while (list ($did,$d_quiz_id) = @each ($chkgrddiffs))
    {
        if($i==$step) 
        {
            $return_id = $d_quiz_id ;
            break;
        }
        $i++;   
    }
    return $return_id;
}

function CopyQuiz($user_id="-1")
{       
      // $user_id = "-1"; //edit 
      global $db,$show_questions;       
      $quiz_id = GetQuizIDByStep(1);      
      $chkboxes = $_POST['chkgrddiffs'];
      $quiz_id_in = db::clear(db::arr_to_in($chkboxes));      
      
      $last_quiz_id = questions_util::CopyQuiz(get_quiz_where(),$chkboxes[0], $show_questions,$_POST['txtRandom'],2,get_randomize_where(), $user_id);
      
      return $last_quiz_id;

}

function get_quiz_where()
{
    $where = "";
    $chkgrddiffs=$_POST['chkgrddiffs'];
    while (list ($did,$d_quiz_id) = @each ($chkgrddiffs))
    {        
        $theme_list = $_POST['hdnQ'.$d_quiz_id];        
        $themes = explode(",", $theme_list);
        $theme_where = "";
        foreach($themes as $theme)
        { 
            if(trim($theme)!="")
            $theme_where.=",".$theme;
        }
        if($theme_where!="")
        {              
            $theme_where = substr($theme_where, 1);
            $theme_where = " and subject_id in ($theme_where) ";
          
        }
        
        $where.=" OR ( quiz_id=$d_quiz_id $theme_where ) ";
    }
    
    $where = substr($where, 3);
    
    $where = " ( $where ) ";
    
    return $where;
    //hdnQ
}

function get_randomize_where()
{
    $chkgrddiffs=$_POST['chkgrddiffs'];
    $in = db::clear(db::arr_to_in($chkgrddiffs));
   
    global $qst_diff_leves_res;
    $globalwhere = "";
    while (list ($did,$d_quiz_id) = @each ($chkgrddiffs))
    {        
        $where="";
        for($i=0;$i<count($qst_diff_leves_res);$i++)
        {
            $diff_id= $qst_diff_leves_res[$i]['id'];
            if(isset($_POST['txtDiffLevel_'.$d_quiz_id.'_'.$diff_id]))
            {
                $qst_count = intval($_POST['txtDiffLevel_'.$d_quiz_id.'_'.$diff_id]);
                $where.=" OR (q.diff_id=$diff_id AND q.diff_row_number<=$qst_count AND q.sbj_id=$d_quiz_id ) ";
            }
        }
        $where =  db::clear(substr($where, 3));    
        $globalwhere.= " OR ( $where ) ";
    }
    
    $globalwhere =  db::clear(substr($globalwhere, 3));   
    $globalwhere=" ( $globalwhere )";
            
    return $globalwhere;
}

function GenerateJsArray($answer_variants)
{
    $js = "";
    for($i=0;$i<sizeof($answer_variants);$i++)
    {
        $js.=",'".$answer_variants[$i]["variant_name"]."'";
    }
    return substr($js,1);
}

  //  include_once "ckeditor/ckeditor.php";     
 //   $CKEditor = new CKEditor();
 //   $CKEditor->config['filebrowserBrowseUrl']='ckeditor/kcfinder/browse.php?type=files';
 //   $CKEditor->config['filebrowserImageBrowseUrl']='ckeditor/kcfinder/browse.php?type=images';
  //  $CKEditor->config['filebrowserFlashBrowseUrl']='ckeditor/kcfinder/browse.php?type=flash';
  //  $CKEditor->basePath = 'ckeditor/';

 function add_file()
 {    
        global $filename,$thumb;  
        if($_FILES['asg_image']['size']>0)
        {
                $filename=basename( $_FILES['asg_image']['name']);
                $arr = explode(".", $filename);
                $ext = end($arr);                
                $filename=md5(util::GUID()).".".$ext;
                $target_path = "asg_images/";
                $target_path = $target_path . $filename;

                move_uploaded_file($_FILES['asg_image']['tmp_name'], $target_path);     
               
               // util::createThumbnail($target_path,90);
                $thumb=".thumb.".$ext;
        }

}    
    
function desc_func()
{
        return ADD_ASSIGNMENT;
}

function LoadDiffLevels($row,$row_count)
{
	$quiz_id = $row['id'];
	$quiz_name = $row['quiz_name'];
	global $qst_diff_leves_res,$disable_controls;
	$results = "	
	<table align='left' border='0' style='width:450px' id='tblDiff".$quiz_id."' >
					<tr>
						<td colspan=3><font color=red size=3>".$quiz_name ."</font></td>
					</tr>
                    <tr>
                        <td><b>".L_DIFF_LEVEL."</b>
                        </td>
                         <td><font color=green><b>".L_MAXQST_COUNT."</font></b>
                        </td>
                        <td><b>".L_QST_COUNT."</b>
                        </td>
                         <td><b>".L_QST_POINT."</b>
                        </td>
                         <td><b>".L_QST_PENALTY."</b>
                        </td>
                    </tr> ";
                    
                    
                       $my_arr = db::Select($row_count, "id", $quiz_id, false, "");
                        
                       for($i=0;$i<count($qst_diff_leves_res);$i++)
                       {
                           $diff_row = $qst_diff_leves_res[$i];
                         //  $diff_ids.=$diff_row['id'].",";    
                            $d_count = 0;                            
                            $d_count = db::Select($my_arr, "diff_level_id", $diff_row['id'], true, "dcount","0");
                            $results.= "<tr>
                                 <td style='width:140px'>".$diff_row['level_name']." : &nbsp;</td>
                                 <td><input style='width:70px' class='form-control input-sm inline' onkeypress='return onlyNumbers(event);' disabled id='txtMaxDiffLevel_".$quiz_id."_".$diff_row['id']."' name='txtMaxDiffLevel_".$quiz_id."_".$diff_row['id']."' type='text' style='width:50px;height:10px' value='".$d_count."' /></td>
                                 <td><input style='width:70px' class='form-control input-sm inline' onkeypress='return onlyNumbers(event);' $disable_controls id='txtDiffLevel_".$quiz_id."_".$diff_row['id']."' name='txtDiffLevel_".$quiz_id."_".$diff_row['id']."' type='text' style='width:50px;height:10px' value='".get_diff_value($diff_row['id'],$quiz_id)."' /></td>
                                 <td><input style='width:70px' class='form-control input-sm inline'  onkeypress='return onlyDecs(event);' $disable_controls id='txtDiffPoint_".$quiz_id."_".$diff_row['id']."' name='txtDiffPoint_".$quiz_id."_".$diff_row['id']."' type='text' style='width:50px;height:10px' value='".get_diff_point($diff_row['id'],$diff_row['level_point'],$quiz_id)."' /></td>
                                      <td><input style='width:70px' class='form-control input-sm inline'  onkeypress='return onlyDecs(event);' $disable_controls id='txtPenPoint_".$quiz_id."_".$diff_row['id']."' name='txtPenPoint_".$quiz_id."_".$diff_row['id']."' type='text' style='width:50px;height:10px' value='".get_pen_point($diff_row['id'],$quiz_id,$diff_row['level_pen'])."' /><input id='txtHPoints_".$quiz_id."_".$diff_row['id']."' name='txtHPoints_".$quiz_id."_".$diff_row['id']."' type='hidden' value='0' /></td>
                             </tr>";
                       
                                                        
                       }
                    
                  
                  $results.="<tr >
                        <td><b>".L_QST_PRIORITY."</b>
                        </td>
                        <td align=\"center\" colspan=\"3\"><input $disable_controls style='width:70px' class='form-control input-small inline' onkeypress='return onlyDecs(event);'  id='txtQuizPrior".$quiz_id."' name='txtQuizPrior".$quiz_id."' type='text'  value='".util::GetData("txt_point_koe")."' />
                        </td>                     
                    </tr>
                </table>
                <p></p> &nbsp; ";
	
		return $results;
	
	
}

$diff_ids="";
for($i=0;$i<count($qst_diff_leves_res);$i++)
{
    $diff_row = $qst_diff_leves_res[$i];
    $diff_ids.=$diff_row['id'].",";  
}

function get_diff_value($diff_id,$quiz_id)
{    
    if(!isset($_GET['id'])) return "0";    
    global $qst_diff_leves_asg_res;        
    
    $results =  db::Select($qst_diff_leves_asg_res, "diff_id", $diff_id, false, "", 0);
    return db::Select($results, "quiz_id", $quiz_id, true, "qst_count", 0);
    
}

function get_diff_point($diff_id,$level_point,$quiz_id)
{
    if(!isset($_GET['id'])) {
        return $level_point;
    }    
    global $qst_diff_leves_asg_res;
    
    $results =  db::Select($qst_diff_leves_asg_res, "diff_id", $diff_id, false, "", 0);    
    return db::Select($results, "quiz_id", $quiz_id, true, "diff_point", 0);    
}

function get_pen_point($diff_id,$quiz_id,$pen_point)
{
    if(!isset($_GET['id'])) {
        return $pen_point;
    }    
    global $qst_diff_leves_asg_res;
    
    $results =  db::Select($qst_diff_leves_asg_res, "diff_id", $diff_id, false, "", 0);    
    return db::Select($results, "quiz_id", $quiz_id, true, "pen_point", 0);    
}

?>
