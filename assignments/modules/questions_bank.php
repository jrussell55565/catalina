<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::has("view_qst",2);

    require "extgrid.php";
    require "db/questions_db.php";
    require "db/quiz_db.php";
    require "events/questions_events.php";
    require "lib/questions_util.php";
    
    $selected_branch="-1";
    $selected_quiz= "-1";
    $selected_view="-1";
    $selected_subject_view = "-1";
    $selected_subject_id= "-1";
    
    if(isset($_GET['id']))
    {
        $selected_subject_view = util::GetID();
    }
    
    $branch_where = access::UserInfo()->access_type != 1 ? array("id"=>access::UserInfo()->branch_id) : array();
    $branches_res= orm::SelectAsArray("branches", array(), $branch_where, "branch_name");
    $branch_options =  webcontrols::GetArrayOptions($branches_res, "id", "branch_name", $selected_branch,false);
    
    $quiz_res = db::GetResultsAsArray(quizDB::GetQuizQuery(" "));
    $quiz_options = webcontrols::GetArrayOptions($quiz_res, "id", "quiz_name", $selected_quiz, false);      
       
    
    $chk_all_html = "<input type=checkbox name=chkAll2 class='els' onclick='grd_select_all(document.getElementById(\"form1\"),\"chk_qb\",\"this.checked\")'>";
    $hedaers = array(L_NUMBER,QUIZ_NAME,QUESTION, TYPE, POINT, ADDED_DATE, "&nbsp;","&nbsp;","&nbsp;");
    $columns = array("id"=>"text","quiz_name"=>"text", "question_text"=>"text","question_type"=>"text" ,"point"=>"text","added_date"=>"short date");
    
    if(!$mobile) array_unshift($hedaers, $chk_all_html); 
    
    $exp_hedaers = array(L_NUMBER,QUIZ_NAME,QUESTION, TYPE, POINT, ADDED_DATE); 

    $url = "index.php?module=questions_bank";
    $grd = new extgrid($hedaers,$columns, $url);
    $grd->row_info_table="questions";
    $grd->remember_checkboxes = false;
    
    $qst_where = "";
    if(isset($_POST['view_change']))
    {
        $view_id = $_POST['view'];
        $subject_id = $_POST['subject'];
        $selected_view = $view_id;
        $selected_subject_view = $subject_id;
        if(!is_numeric($view_id) || !is_numeric($subject_id)) exit();
        
        unset($_SESSION["p".$grd->page_name]);
        $qst_where= $view_id == "-1" ? "" : "and quiz_id=$view_id ";              
        $qst_where.=$subject_id == "-1" ? "" : "and subject_id=$subject_id "; 
        $_SESSION["sqland".$grd->page_name] = $qst_where;
        $_SESSION["sql_sv".$grd->page_name] = $selected_view;
        $_SESSION["sql_ssv".$grd->page_name] = $selected_subject_view;
    }
    else if (isset($_SESSION["sqland".$grd->page_name]))
    {
        $qst_where = $_SESSION["sqland".$grd->page_name];
        $selected_view = $_SESSION["sql_sv".$grd->page_name];
        $selected_subject_view = $_SESSION["sql_ssv".$grd->page_name];
    }  
    
    $quiz_view_options = webcontrols::GetArrayOptions($quiz_res, "id", "quiz_name", $selected_view, false);  
    
    $subject_res =db::GetResultsAsArray(orm::GetSelectQuery("subjects", array(), au::arr_where(array()), ""));
    $subject_options = webcontrols::GetArrayOptions($subject_res, "id", "subject_name", $selected_subject_view,false);
    
    $grd->exp_headers = $exp_hedaers;
    $grd->exp_columns = $columns;
    $grd->edit_link="index.php?module=add_question&quiz_id=-1";
    $grd->edit_link_override="edit_link";
    $grd->column_override=array("question_type"=>"question_type_override", "question_text"=>"question_text_override");
    $leftC = 0;
    if($grd->mobile) $leftC = "-1";
    $grd->jslinks=array(PREVW=>"ShowPreview(\"[id]\",event.pageY,$leftC)");
    //$grd->auto_id=true;
    if(!$mobile) $grd->checkbox = true;
    $grd->chk_class = "chk_qb";
    $grd->search_mode=2;
    $grd->search=true;
    
    $grd->edit_link_adds=array("f"=>"qst_mode");
    $grd->edit_link_adds_not_include=array("1");
    
    if(!access::has("delete_qst")) $grd->delete_text = "";
    if(!access::has("edit_qst")) $grd->edit_text = "";
    
    function question_text_override($row)
    {        
        return util::GetWords(strip_tags($row['question_text']),WORD_COUNT);
    }

    function edit_link($row,$edit_adds)
    {        
        if(!access::has("edit_qst")) return "";
        $quiz_id = $row["quiz_id"];
        $id= $row["id"];
        $quiz_id = $quiz_id=="" ? "-1" : $quiz_id;
        return "<a href=\"index.php?module=add_question&qstbank=1$edit_adds&id=$id&quiz_id=$quiz_id\">".EDIT."</a>";
    }
    function question_type_override($row)
    {
        global $QUESTION_TYPE;
        return $QUESTION_TYPE[$row['question_type_id']];
    }
    
    if($grd->IsClickedBtnDelete() && access::has("delete_qst"))
    {
        DeleteQuestion($grd->process_id);
    }
    
    if(isset($_POST['pcommand']) && !empty($_POST['chkboxes']))
    {
        $chkboxes = $_POST['chkboxes'];
       
        if($_POST['command']=="delete" && access::has("delete_qst"))
        {            
            for($i=0;$i<count($chkboxes);$i++)
            {
                DeleteQuestion(util::GetInt($chkboxes[$i]));
            }
        }
        else if($_POST['command']=="create_copy" && access::has("qst_copy")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();
            for($i=0;$i<count($chkboxes);$i++)
            {
                $id = util::GetInt($chkboxes[$i]);
                $res_qst = $db->query_as_array(orm::GetSelectQuery("questions", array(), array("id"=>$id,"parent_id"=>"0"), "priority"));
                questions_util::CopyQuestions($db, $res_qst, -1,true);
            }
            $db->close_connection();
        }
        else if($_POST['command']=="change_branch" && access::has("qst_change_brn")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();
            $branch_id = $_POST['branch_id'];
            if($branch_id!="-1") {
            for($i=0;$i<count($chkboxes);$i++)
            {
                $id = util::GetInt($chkboxes[$i]);
                $db->query(orm::GetUpdateQuery("questions", array("branch_id"=>$branch_id), array("id"=>$id)));
            }
            }
            $db->close_connection();
        }
        else if($_POST['command']=="change_quiz" && access::has("qst_change_quiz")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();
            $quiz_id = $_POST['quiz_id'];
            if($quiz_id!="-1") {
            for($i=0;$i<count($chkboxes);$i++)
            {                
                $id = util::GetInt($chkboxes[$i]);                
                
                $quiz_id_o= $db->query_single_value(orm::GetSelectQuery("questions", array("quiz_id"), array("id"=>$id), ""), "quiz_id");                
                $db->query(orm::GetUpdateQuery("questions", array("quiz_id"=>$quiz_id), array("id"=>$id)));
                
                if($quiz_id_o!="") {
                $priority = questions_db::GetMinPriority($quiz_id_o);
                if($priority!=-1)questions_db::UpdatePriority($quiz_id_o,$priority);  
                }
            }
            if($quiz_id!="") {
            $priority = questions_db::GetMinPriority($quiz_id);
            if($priority!=-1)questions_db::UpdatePriority($quiz_id,$priority);  
            }
            }
            $db->close_connection();
        }
          else if($_POST['command']=="arch" && access::has("arch_qst")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();
            
            for($i=0;$i<count($chkboxes);$i++)
            {                
                $id = util::GetInt($chkboxes[$i]);                
                                              
                $db->query(orm::GetUpdateQuery("questions", array("is_arch"=>1), au::arr_where(array("id"=>$id))));
                                
            }
          
            $db->close_connection();
        }
        else if($_POST['command']=="unarch" && access::has("arch_qst")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();                        
            for($i=0;$i<count($chkboxes);$i++)
            {                
                $id = util::GetInt($chkboxes[$i]);                
                                              
                $db->query(orm::GetUpdateQuery("questions", array("is_arch"=>0), au::arr_where(array("id"=>$id))));
                                
            }
          
            $db->close_connection();
        }
        else if($_POST['command']=="update_childs" && access::has("update_child_qst")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();                        
            for($i=0;$i<count($chkboxes);$i++)
            {                
                $id = util::GetInt($chkboxes[$i]);                
                                              
                questions_util::UpdateChildQuestions($id);
                                
            }
          
            $db->close_connection();
        }
    }
    
    function DeleteQuestion($id)
    {
        question_deleting($id);
        $rs_qsts=orm::Select("questions", array(), au::arr_where(array("id"=>$id)), "");
        $row_qst = db::fetch($rs_qsts);
        $quiz_id=$row_qst['quiz_id'];
        questions_db::DeleteQuestion($id);    
        if($quiz_id!="")
        {
            $priority = questions_db::GetMinPriority($quiz_id);
            if($priority!=-1)
            questions_db::UpdatePriority($quiz_id,$priority);  
        }
        
        question_deleted($id);
    }
    
    $grd->search_arr['txtqid'] = "txtq.id";
    $grd->sort_headers = array(L_NUMBER=>"id",QUIZ_NAME=>"quiz_name",QUESTION=>"question_text", TYPE=>"question_type_id", POINT=>"point", ADDED_DATE=>"added_date");
    $grd->default_sort = "id desc";
    $query = questions_db::GetQuestionsBankQuery($qst_where,$grd->GetSortQuery());   
    $grd->checkbox_add_func = "checkbox_add";
    $grd->checkbox_width="40px";
    $grd->DrowTable($query);
    $grid_html = $grd->table;
    
    function checkbox_add($row)
    {
        $color = "t_green";
        if($row['is_arch']=="1") $color="t_red";
        return "&nbsp;<img style='width:5px' src='style/i/$color.png' />";
    }
    
    $search_html = $grd->DrowSearch(array(L_NUMBER,QUIZ_NAME, QUESTION),array("qid","quiz_name", "question_text"));

    if(isset($_POST["ajax"]))
    {
         if(isset($_POST['info_id'])) echo $grd->LoadUserInfo ($_POST['info_id']) ;
         else echo $grid_html;
    } 
    if(isset($_GET["expgrid"]))
    {
        $grd->Export();
    }
    
   function desc_func()
   {
        return QUESTIONS_BANK;
   }
    
?>    