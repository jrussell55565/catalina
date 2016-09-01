<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("quizzes");
    access::has("view_quizzes",2);

    require "extgrid.php";    
    require "db/quiz_db.php"; 
    require "lib/questions_util.php";
    
    $selected_branch="-1";
    $branch_where = access::UserInfo()->access_type != 1 ? array("id"=>access::UserInfo()->branch_id) : array();
    $branches_res= orm::SelectAsArray("branches", array(), $branch_where, "branch_name");
    $branch_options =  webcontrols::GetArrayOptions($branches_res, "id", "branch_name", $selected_branch,false);

    $chk_all_html = "<input class='els' type=checkbox name=chkAll2 onclick='grd_select_all(document.getElementById(\"form1\"),\"chk_qb\",\"this.checked\")'>";   
    
    $hedaers = array(CAT_NAME,QUIZ_NAME, QUIZ_DESC, ADDED_DATE,QUESTIONS,"&nbsp;","&nbsp;");
    $columns = array("cat_name"=>"text","quiz_name"=>"text", "quiz_desc"=>"text","added_date"=>"short date");
    
    if(!$mobile) array_unshift($hedaers, $chk_all_html); 

    $exp_hedaers = array(CAT_NAME,QUIZ_NAME, QUIZ_DESC, ADDED_DATE); 
    
    $url = "index.php?module=quizzes";
    $grd = new extgrid($hedaers,$columns, $url);
    $grd->exp_headers = $exp_hedaers;
    $grd->exp_columns = $columns;
    $grd->edit_link="index.php?module=add_edit_quiz";
    $grd->row_info_table="quizzes";
    $grd->remember_checkboxes=false;       
    
    $questionsl = access::has("view_qst") == true ? QUESTIONS : "";
    
    $grd->id_links=(array($questionsl=>"?module=questions"));
    $grd->id_link_key="quiz_id";
    $grd->auto_id=false;
    
    if(!access::has("delete_quiz")) $grd->delete_text = "";
    if(!access::has("edit_quiz")) $grd->edit_text = "";

    if($grd->IsClickedBtnDelete() && access::has("delete_quiz"))
    {
       quizDB::DeleteQuizById($grd->process_id);
    }
    
    if(isset($_POST['pcommand']) && !empty($_POST['chkboxes']))
    {
        $chkboxes = $_POST['chkboxes'];
       
        if($_POST['command']=="delete" && access::has("delete_quiz"))
        {            
            for($i=0;$i<count($chkboxes);$i++)
            {
                quizDB::DeleteQuizById(util::GetInt($chkboxes[$i]));                
            }
        }
        else if($_POST['command']=="create_copy" && access::has("quiz_copy")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();
            for($i=0;$i<count($chkboxes);$i++)
            {
                $id = util::GetInt($chkboxes[$i]);
                //$db->insert_query(orm::GetInsertQuery("quizzes", $columns))
                $quiz_res = $db->query(orm::GetSelectQuery("quizzes", array(), array("id"=>$id), ""));
                $quiz_row = db::fetch($quiz_res);
                $last_quiz_id=$db->insert_query(orm::GetInsertQuery("quizzes", au::add_insert(array("cat_id"=>$quiz_row['cat_id'],"quiz_name"=>$quiz_row['quiz_name']." ".util::Now(), "quiz_desc"=>$quiz_row['quiz_desc'],"added_date"=>util::Now(),"parent_id"=>"0"))));
                $res_qst = $db->query_as_array(orm::GetSelectQuery("questions", array(), array("quiz_id"=>$id,"parent_id"=>"0"), "priority"));
                questions_util::CopyQuestions($db, $res_qst, $last_quiz_id,true);
            }
            $db->close_connection();
        }
        else if($_POST['command']=="change_branch" && access::has("quiz_change_brn")) // && access::has("create_copy")
        { 
            $db = new db();
            $db->connect();
            $branch_id = $_POST['branch_id'];
            if($branch_id!="-1") {
            for($i=0;$i<count($chkboxes);$i++)
            {
                $id = util::GetInt($chkboxes[$i]);
                $db->query(orm::GetUpdateQuery("quizzes", array("branch_id"=>$branch_id), array("id"=>$id)));
            }
            }
            $db->close_connection();
        }
    }
      
    //$query =  orm::GetSelectQuery("quizzes", array() , au::arr_where(array("parent_id"=>"0")), "added_date desc", true);  //quizDB::GetQuizQuery();
    $grd->sort_headers = array(CAT_NAME=>"cat_name",QUIZ_NAME=>"quiz_name",QUIZ_DESC=>"quiz_desc",ADDED_DATE=>"added_date");
    $grd->default_sort = "added_date desc";
    $query =  quizDB::GetQuizQuery("",$grd->GetSortQuery());
    $grd->search= true;
    if(!$mobile) $grd->checkbox = true;
    $grd->chk_class = "chk_qb";
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(QUIZ_NAME, QUIZ_DESC),array("quiz_name", "quiz_desc"));

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
        return QUIZZES;
    }

?>
