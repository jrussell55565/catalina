<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::has("view_qst",2);

    require "extgrid.php";
    require "db/questions_db.php";
    require "events/questions_events.php";

    $quiz_id = util::GetKeyID("quiz_id", "index.php?module=quizzes");
    $a_id = "-1";
    $a_link = "";
    if(isset($_GET['a_id'])) 
    {
        access::has("man_asg_qsts", 2);
        $a_id = util::GetKeyID("a_id", "index.php?module=quizzes");
        $a_link= "&a_id=$a_id";
    }

    $hedaers = array("&nbsp;",QUESTION, TYPE, POINT, ADDED_DATE, "&nbsp;","&nbsp;","&nbsp;","&nbsp;","&nbsp;");
    $columns = array("question_text"=>"text","question_type"=>"text" ,"point"=>"text","added_date"=>"short date");
    
    $exp_hedaers = array(QUESTION, TYPE, POINT, ADDED_DATE); 

    $grd = new extgrid($hedaers,$columns, "index.php?module=questions&quiz_id=$quiz_id".$a_link);
    $grd->exp_headers = $exp_hedaers;
    $grd->exp_columns = $columns;
    $grd->edit_link="index.php?module=add_question&quiz_id=$quiz_id".$a_link;
    $grd->column_override=array("question_type"=>"question_type_override", "question_text"=>"question_text_override");
    $leftC = 0;
    if($grd->mobile) $leftC = "-1";
    $grd->jslinks=array(PREVW=>"ShowPreview(\"[id]\",event.pageY,$leftC)");
    $grd->auto_id=true;
    $grd->row_info_table="questions";
    
    $grd->edit_link_adds=array("f"=>"qst_mode");
    $grd->edit_link_adds_not_include=array("1");
    
    if(!access::has("delete_qst")) $grd->delete_text = "";
    if(!access::has("edit_qst")) $grd->edit_text = "";

    function question_text_override($row)
    {
        return util::GetWords(strip_tags($row['question_text']),WORD_COUNT);
    }
    
    function question_type_override($row)
    {
        global $QUESTION_TYPE;
        return $QUESTION_TYPE[$row['question_type_id']];
    }

    //$grd->links=(array("Questions"=>"?module=questions"));
    $grd->commands_direction = ArrDirection::ValueFirst;
    
    $up = access::has("move_qst") == true ? UP : "";
    $down = access::has("move_qst") == true ? DOWN : "";
    
    $grd->commands=array("up"=>$up, "down"=>$down);

    if($grd->IsClickedBtnDelete() && access::has("delete_qst"))
    {
       question_deleting($grd->process_id);
       $resultsd = orm::Select("questions", array("video_file"), array("id"=>$grd->process_id), "id");
       $rowd=db::fetch($resultsd);
       if($rowd['video_file']!="")
       {           
           @unlink("video_files".DIRECTORY_SEPARATOR.$rowd['video_file']);           
       }
        
        questions_db::DeleteQuestion($grd->process_id);    
	$priority = questions_db::GetMinPriority($quiz_id);
	if($priority!=-1)
	questions_db::UpdatePriority($quiz_id,$priority);   
        
        question_deleted($grd->process_id);
    }

    if($grd->IsClickedBtn("up") && access::has("move_qst"))
    {        
        questions_db::MoveQuestion("up", $grd->process_id);        
    }

    if($grd->IsClickedBtn("down") && access::has("move_qst"))
    {
        questions_db::MoveQuestion("down", $grd->process_id);
    }

    $grd->sort_headers = array(QUESTION=>"question_text", TYPE=>"question_type_id", POINT=>"point", ADDED_DATE=>"added_date");
    $grd->default_sort = "priority asc";
    $query = questions_db::GetQuestionsQuery($quiz_id, $grd->GetSortQuery());
    $grd->DrowTable($query);
    $grid_html = $grd->table;

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
        return QUESTIONS;
    }

?>
