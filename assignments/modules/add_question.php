<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::has("view_qst",2);

    require "db/questions_db.php";
    require "db/quiz_db.php";
    require "events/questions_events.php";
    
    define("BASE64_SAVE_PATH","ckeditor/kcfinder/upload_img/images/base64_images/");
    
    $val = new validations("btnSubmit");    
    $val->AddValidator("drpQuiz", "notequal", L_QUIZ_VAL, "-1");

    $quiz_id = util::GetKeyID("quiz_id", "?module=questions");

    $txtQsts="";
    $answers_count=4;
    $selected =$selected_subject_id= "-1";    
    $id = "-1";
    $video_file_name = "";
    $quiz_display = "";
    $row_qsts=0;
    $qst_level = "1";
    
    $ANSWER_MODE = isset($_GET['f']) ? "EXTENDED" : "SIMPLE";
    $ASNWER_MODE_ID = isset($_GET['f']) ? 2 : 1;
    $ext_link_display= $ASNWER_MODE_ID == 2 ? "none" : "";
    
    $display_partly = POINT_CALCULATION == "COMPLETE" ? "none" : "";
    
    $a_id = "-1";
    $a_link = "";
    $where_in = "";
    if(isset($_GET['a_id'])) 
    {
        access::has("man_asg_qsts", 2);
        $a_id = util::GetKeyID("a_id", "index.php?module=quizzes");
        $a_link= "&a_id=$a_id";
        $linked_quiz_id = util::GetKeyID("quiz_id");
        $arr_in = db::GetResultsAsArrayByColumn(orm::GetSelectQuery("asg_qbank_quizzes", array(), array("asg_id"=>$a_id), "view_priority"), "quiz_id");
        if(count($arr_in)>0)
        {            
            $in = db::arr_to_in($arr_in);
            $where_in = " and q.id in($in) ";
        }
    }
    
    $quiz_res = db::exec_sql(quizDB::GetQuizQuery(" $where_in "));  
    
    if(isset($_GET["id"]))
    {
        access::has("edit_qst",2);
        $id = util::GetID("?module=questions");
        $rs_qsts=orm::Select("questions", array(), au::arr_where(array("id"=>$id)), "");

        if(db::num_rows($rs_qsts) == 0 ) util::redirect("?module=questions");

        $quiz_display = "";                        
        
        $row_qsts=db::fetch($rs_qsts);
        $txtQsts = $row_qsts["question_text"];        
        $txtPoint = $row_qsts["point"];        
        $txtHeader = $row_qsts["header_text"];
        $txtFooter = $row_qsts["footer_text"];
        $txtSuccessMsg = $row_qsts["success_msg"];
        $txtPSuccessMsg = $row_qsts["psuccess_msg"];
        $txtUnSuccessMsg = $row_qsts["unsuccess_msg"];
        $selected = $row_qsts["question_type_id"];
        $txtPenaltyPoint=$row_qsts["penalty_point"];
        $video_file_name = $row_qsts["video_file"];
        $selected_subject_id = $row_qsts["subject_id"];    
        $qst_level = $row_qsts["diff_level_id"];  
        $txtComments =  $row_qsts["qst_comments"];      
        
        if($a_id=="-1") $selected_quiz_id=$quiz_id;                    
        else $selected_quiz_id=$row_qsts["parent_quiz_id"]; 
        
        $rs_grp=orm::Select("question_groups", array(), array("question_id"=>$id), "added_date");
        $row_grp = db::fetch($rs_grp);

        $txtGroupName=$row_grp["group_name"];
        $txtGroupID=$row_grp["id"];

        $rs_ans = orm::Select("answers", array(), array("group_id"=>$row_grp["id"]), "priority");                

        $answers_count = db::num_rows($rs_ans);

        $i = 0;
        while($row_ans=db::fetch($rs_ans))
        {
            $i++;
            ${"txtChoise".$i} = $row_ans["answer_text"];
            ${"txtCrctAnswer".$i} = $row_ans["correct_answer_text"];
            ${"ans_selected".$i} = $row_ans["correct_answer"]=="1" ? "checked" : "";
            ${"txtDesc".$i} = $row_ans["answer_desc"];
            ${"txtAPoint".$i} = $row_ans["answer_point"];            
        }
        
        $qst_diff_levels_arr = db::GetResultsAsArray(orm::GetSelectQuery("qst_diff_xreff", array(), array("qst_id"=>"$id"), "course_id"));

    }
    else access::has("add_qst",2);
    
    $quiz_options = webcontrols::GetOptions($quiz_res, "id", "quiz_name", $selected_quiz_id);    
            
    
    //$diff_level_opts = webcontrols::BuildOptions(array("1"=>$QST_LEVEL[1], "2"=>$QST_LEVEL[2],"3"=>$QST_LEVEL[3] ), $qst_level);  
    
    $diff_level_res = db::GetResultsAsArray(orm::GetSelectQuery("qst_diff_levels", array("id","level_name"), array(), "priority,id", false));  
    $diff_level_opts = webcontrols::GetArrayOptions($diff_level_res, "id", "level_name", $qst_level, false, "", $QST_LEVEL);
    
    if($a_id!="-1") 
    {
      //  $quiz_display = "none";        
    }
   
    
    question_add_page_loading($id, $row_qsts);

    $results = orm::Select("question_types", array(), array() , "id");
    //$temp_options = webcontrols::GetOptions($results, "id", "question_type",$selected);
    $temp_options= webcontrols::BuildOptions($QUESTION_TYPE, $selected);


  //  $val = new validations("btnSave");
  //  $val->AddValidator("txtName", "empty", "Name cannot be empty","");

    if(isset($_POST["btnSave"]))
    {        
        $db = new db();
        $db->connect();
        $db->begin();        
        try
        {
            $filename = "";      
            
            add_video();                   
           
            $question_type=$_POST["drpTemplate"];
            
            if($question_type==0)
            {
                $group_name= trim($_POST["txtMultiGroupName"]);
            }
            else if($question_type==1)
            {
                $group_name= trim($_POST["txtOneGroupName"]);
            }
            else if($question_type==3)
            {
                $group_name= trim($_POST["txtAreaGroupName"]);
            }
            else 
            {
                $group_name= trim($_POST["txtMultiTextGroupName"]);
            }
            
            $html_content = replace_img_tags($_POST["editor1"]);
            
            if(!isset($_GET["id"]))
            {                
               
                $quiz_id = $a_id == "-1" ? $_POST['drpQuiz'] : $linked_quiz_id;
                $arr_insert = array("question_text"=>trim($html_content),
                                                   "question_type_id"=>$_POST["drpTemplate"],
                                                   "priority"=>"(select ifnull(max(priority)+1,1) from questions where quiz_id=$quiz_id)",
                                                   "quiz_id"=>array($quiz_id =="-1" ? "null" : $quiz_id,false),
                                                   "point"=>$_POST["txtPoint"],
                                                   "penalty_point"=>$_POST["txtPenaltyPoint"],
                                                   "parent_id"=> $a_id == "-1" ? 0 : -1 ,
                                                   "footer_text"=>$_POST["txtFooter"],
                                                   "header_text"=>$_POST["txtHeader"],
                                                   "success_msg"=>$_POST["txtSuccessMsg"],
                                                    "psuccess_msg"=>$_POST["txtPSuccessMsg"],
                                                    "unsuccess_msg"=>$_POST["txtUnSuccessMsg"],
                                                    "subject_id"=>$_POST["drpSubjects"],
                                                    "diff_level_id"=>$_POST['drpDiffLevel'],
                                                    "qst_comments"=>$_POST['txtComments'],
                                                    "qst_mode"=>$ASNWER_MODE_ID   
                                                   );   
                
                if($a_id!="")
                {
                    $arr_insert['parent_quiz_id'] = $_POST['drpQuiz'];
                }
                
                if(trim($filename)!="")
                {
                    $arr_insert["video_file"]=$filename;
                }                
                question_adding($db,au::add_insert($arr_insert));
                $query = orm::GetInsertQuery("questions", au::add_insert($arr_insert));
                
                $db->query($query);
                $question_id = $db->last_id();
                $db->query(questions_db::UpdatePriorityQuery($quiz_id, $question_id));
                question_added($db,$question_id, au::add_insert($arr_insert));
                
                $query= orm::GetInsertQuery("question_groups", array("group_name"=>$group_name,
                                                                 "show_header"=>$group_name =="" ? 0 : 1,
                                                                 "question_id"=>$question_id,
                                                                 "parent_id"=>$a_id == "-1" ? 0 : -1
                                                            ));

                $db->query($query);
                $group_id = $db->last_id();
                
            }
            else
            {
                // add_video();
                 
                 if(isset($_POST['chkRemove']) || trim($filename)!="")
                 {
                     $query = orm::GetUpdateQuery("questions", array("video_file"=>""), array("id"=>$id));
              
                     $db->query($query);
                     
                     if(trim($video_file_name)!="")
                     {
                        @unlink("video_files".DIRECTORY_SEPARATOR.$video_file_name);
                     }
                 }
                 
                
                 $arr_update = array("question_text"=>trim($html_content),
                                                   "question_type_id"=>$_POST["drpTemplate"],
                                                   "point"=>$_POST["txtPoint"],
                                                   "penalty_point"=>$_POST["txtPenaltyPoint"],
                                                   "footer_text"=>$_POST["txtFooter"],
                                                   "header_text"=>$_POST["txtHeader"],
                                                     "success_msg"=>$_POST["txtSuccessMsg"],
                                                    "psuccess_msg"=>$_POST["txtPSuccessMsg"],
                                                    "unsuccess_msg"=>$_POST["txtUnSuccessMsg"],
                                                    "subject_id"=>$_POST["drpSubjects"],
                                                    "diff_level_id"=>$_POST['drpDiffLevel'],
                                                    "qst_comments"=>$_POST['txtComments'],
                                                    "qst_mode"=>$ASNWER_MODE_ID   
                                                   
                                            );
                if(trim($filename)!="")
                {
                    $arr_update["video_file"]=$filename;
                }     
                                
                $drpQuiz = $_POST['drpQuiz'];
                if($drpQuiz=="-1") $drpQuiz = "null";
                
                if($a_id=="-1") $arr_update["quiz_id"]=array($drpQuiz, $drpQuiz == "-1" ? false : true);
                else $arr_update["parent_quiz_id"] = $drpQuiz;
                
                $query = orm::GetUpdateQuery("questions", au::add_update($arr_update), array("id"=>$id));
                question_edited($db,$id, au::add_update($arr_update));
                $db->query($query);        
                question_edited($db,$id, au::add_update($arr_update));
                $question_id = $id;
               // $db->query(questions_db::GetAnswerDeleteQuery($question_id));                
               // $db->query(questions_db::GetGroupDeleteQuery($question_id));
                
                $query= orm::GetUpdateQuery("question_groups", array("group_name"=>$group_name,
                                                                 "show_header"=>$group_name =="" ? 0 : 1
                                                                 //"question_id"=>$question_id,
                                                                // "parent_id"=>"0"
                                                            ), array("question_id"=>$question_id) );

                $db->query($query);
                $group_id = $txtGroupID;
                
                $db->query(orm::GetDeleteQuery("qst_diff_xreff", array("qst_id"=>$question_id)));
                
            }
                                                           
            $all_ans_count = 0;
            for($i=1;;$i++)
            {                
                $all_ans_count = $i-1;
                if($question_type==0)
                {
                    if(!isset($_POST["txtMultiDesc".$i])) break;
                    
                    $answer_text = $ANSWER_MODE == "EXTENDED" && $mobile!=true ? trim($_POST["chkeMulti".$i]) : trim($_POST["txtMulti".$i]);
                    $answer_desc= trim($_POST["txtMultiDesc".$i]);
                    $answer_point= trim($_POST["txtMAPoint".$i]);
                    $correct_answer = isset($_POST["chkMulti".$i]) ==true ? 1 : 0;
                    $correct_answer_text="";
                }
                else if($question_type==1)
                {
                    if(!isset($_POST["txtOneDesc".$i])) break;

                    $answer_text = $ANSWER_MODE == "EXTENDED" && $mobile!=true  ?  trim($_POST["chkeOne".$i]) : trim($_POST["txtOne".$i]);
                    $answer_desc= trim($_POST["txtOneDesc".$i]);
                    $answer_point= trim($_POST["txtOAPoint".$i]);
                    $correct_answer = isset($_POST["rdOne"]) ==true && $_POST["rdOne"]==$i? 1 : 0;
                    $correct_answer_text="";
                }
                else if($question_type==3)
                {
                    if(!isset($_POST["txtArea".$i])) break;

                    $answer_text = "";
                    $correct_answer = 0;
                    $correct_answer_text=trim($_POST["txtArea".$i]);
                    $answer_point= trim($_POST["txtPoint"]);
                }
                else 
                {
                    if(!isset($_POST["txtMultiText".$i])) break;

                    $answer_text = trim($_POST["txtMultiText".$i]);
                    $answer_desc= trim($_POST["txtMultiTextDesc".$i]);
                    $answer_point= trim($_POST["txtMTAPoint".$i]);
                    $correct_answer = 0;
                    $correct_answer_text=trim($_POST["txtMultiCrctAnswer".$i]);
                }
                
                $ans_arr = array("group_id"=>$group_id,"answer_text"=>$answer_text,
                                            "correct_answer"=>$correct_answer ,
                                            "correct_answer_text"=>$correct_answer_text ,
                                            "priority"=>$i,
                                            "answer_pos"=>"1",
                                            "answer_point"=>$answer_point,
                                            "parent_id"=>$a_id == "-1" ? 0 : -1,
                                            "control_type"=>"1",
                                            "answer_parent_id"=>"0",
                                            "answer_desc"=>$answer_desc);
                
                $ans_res_t = $db->query(orm::GetSelectQuery("answers", array("id"), array("group_id"=>$group_id, "priority"=>$i), ""));
                
                if(db::num_rows($ans_res_t)>0 && isset($_GET['id']))
                {                    
                    $query=orm::GetUpdateQuery("answers", $ans_arr,array("group_id"=>$group_id, "priority"=>$i));                    
                }               
                else {
                    $query=orm::GetInsertQuery("answers", $ans_arr);
                }
                
                $db->query($query);
            }
            
            $db->query(questions_db::GetAnswerDeleteQuery2($group_id, $all_ans_count));
            
            for($i=1;;$i++)
            {
                if(!isset($_POST['drpDiff'.$i])) break;
                
                $diff_id= $_POST['drpDiff'.$i];    
                if($diff_id!="-1") $db->query(orm::GetInsertQuery("qst_diff_xreff", array("qst_id"=>$question_id, "diff_id"=>$diff_id, "course_id"=>$i)));                
            }
            
            $db->commit();
            
            $quiz_id = $a_id!="-1" ? $linked_quiz_id : $quiz_id;
            
            if(ISSET($_GET['qstbank'])) util::redirect("?module=questions_bank");            
            else util::redirect("?module=questions&quiz_id=$quiz_id".$a_link);
        }
        catch(Exception $e)
        {
            //echo $e->getMessage();
            $db->rollback();
        }

        $db->close_connection();

    }
    
  //  include_once "ckeditor/ckeditor.php";
  //  $CKEditor = new CKEditor();
   // $CKEditor->config['filebrowserBrowseUrl']='ckeditor/kcfinder/browse.php?type=files';
  //  $CKEditor->config['filebrowserImageBrowseUrl']='ckeditor/kcfinder/browse.php?type=images';
  //  $CKEditor->config['filebrowserFlashBrowseUrl']='ckeditor/kcfinder/browse.php?type=flash';    
   // $CKEditor->basePath = 'ckeditor/';        
    
    $simple_ans_dipslay = $ANSWER_MODE == "EXTENDED" ? "none" : "";
    $ext_ans_dipslay = $ANSWER_MODE != "EXTENDED" ? "none" : "";
    
    function replace_img_tags($htmlContent)
    {                        
        $imgTags= array();
        preg_match_all('/<img[^>]+>/i',$htmlContent, $imgTags); 

        for ($i = 0; $i < count($imgTags[0]); $i++) {
          // get the source string
          preg_match('/src="([^"]+)/i',$imgTags[0][$i], $imgage);

          // remove opening 'src=' tag, can`t get the regex right
          $origImageSrc[$i] = str_ireplace( 'src="', '',  $imgage[0]);
                     
        }
        $base64str='data:image/png;base64,';
        for($i=0;$i<count($origImageSrc);$i++)
        {                                    
            if(!util::startsWith($origImageSrc[$i], $base64str)) continue;
            $img = str_replace($base64str, '', $origImageSrc[$i]);            
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            //echo getcwd() ;
            $file= BASE64_SAVE_PATH.util::new_guid().".png";            
            file_put_contents(getcwd()."/".$file, $data);
            $htmlContent = str_replace($origImageSrc[$i], $file, $htmlContent);
                     
        }
        
        return $htmlContent;
    }
    
    function add_video()
    {    
        global $filename;  
        if($_FILES['videoFile']['size']>0)
        {
                $filename=basename( $_FILES['videoFile']['name']);
                $arr = explode(".", $filename);
                $ext = end($arr);                
                $filename=md5(util::GUID()).".".$ext;                         
                $target_path = "video_files/";
                $target_path = $target_path . $filename;

                move_uploaded_file($_FILES['videoFile']['tmp_name'], $target_path);     
        }

    }
    
    function get_selected_diff($i)
    {
        if(!isset($_GET['id'])) return "-1";
        
        global $qst_diff_levels_arr;
       
        return db::Select($qst_diff_levels_arr, "course_id", $i, true, "diff_id", "-1");
        
    }
   
    if(isset($_POST['ajax']))
    {
        if(isset($_POST['load_subjects']))
        {
            $quiz_id = $_POST['quiz_id'];
            $subject_res = orm::Select("subjects", array(), au::arr_where(array("quiz_id"=>$quiz_id)), "");
            //$subject_options = webcontrols::GetOptions($subject_res, "id", "subject_name", $selected_subject_id);
            $attrs = 'class="form-control input-medium"';
            echo webcontrols::GetDropDown("drpSubjects", $subject_res, "id", "subject_name", $selected_subject_id, "", $attrs);
            exit();
        }
    }

   function desc_func()
   {
        return ADD_EDIT_QUESTION;
   }
   

?>
