<?php

class questions_util 
{
    
    public static function UpdateChildQuestions($qst_id)
    {
        global $db;
        
        $results_arr  = db::GetResultsAsArray(orm::GetSelectQuery("questions", array(), array("id"=>$qst_id), ""));
        
        $row_qst = $results_arr[0];
        
        $query = orm::GetUpdateQuery("questions", 
                                    array("question_text"=>$row_qst['question_text'],
                                                            "question_type_id"=>$row_qst['question_type_id'],                                                                                                                                                                                                                                               
                                                            "header_text"=>$row_qst['header_text'],
                                                            "footer_text"=>$row_qst['footer_text'],
                                                            "success_msg"=>$row_qst['success_msg'],
                                                            "psuccess_msg"=>$row_qst['psuccess_msg'],
                                                            "unsuccess_msg"=>$row_qst['unsuccess_msg'],                                                                                                                      
                                                            "diff_level_id"=>$row_qst['diff_level_id'],                                                                                                                                                                           
                                                            "qst_mode"=>$row_qst['qst_mode'],
                                                            "qst_comments"=>$row_qst['qst_comments'],
                                                            "video_file"=>$row_qst['video_file'],
                                                            "question_type_id"=>$row_qst['question_type_id'],
                                                            "parent_quiz_id"=>$row_qst['quiz_id']															
                                                            ),
                                    array("parent_id"=>$qst_id));       
        
        $db->query($query);
        
        
       $groups_res = $db->query(orm::GetSelectQuery("question_groups", array("group_name","id"), array("question_id"=>$qst_id), ""));
       
       while($groups_row=db::fetch($groups_res))
       {
           
           $sql = orm::GetUpdateQuery("question_groups", array("group_name"=>$groups_row['group_name']), array("parent_id"=>$groups_row['id']));
           
           $db->query($sql);
           
           $answer_res = $db->query(orm::GetSelectQuery("answers", array(), array("group_id"=>$groups_row['id']), "priority"));
           
           while($answers_row=db::fetch($answer_res))
           {                
            
               $sql = orm::GetUpdateQuery("answers", 
                              array("answer_text"=>$answers_row['answer_text'],
                                    "answer_desc"=>$answers_row['answer_desc'],
                                    "correct_answer"=>$answers_row['correct_answer'],
                                    "correct_answer_text"=>$answers_row['correct_answer_text'],
                                )                       
                            , array("parent_id"=>$answers_row['id']));
               
               $db->query($sql);
               
           }
           
           
           
       }
        
       // $sql = "select id, group_name from question_groups where "
        
        
        
    }
    
    public static function CopyQuestions($db,$res_qst,$quiz_id, $parent_id_zero=false)
    {
        global $db;
        $q = 0;
        if($quiz_id=="NULL") $quiz_id = array("NULL",true);
        for($i=0;$i<sizeof($res_qst);$i++)
        {
            $row_qst = $res_qst[$i];
            $q++;
            $query = orm::GetInsertQuery("questions", au::add_insert(array("question_text"=>$row_qst['question_text'],
                                                            "question_type_id"=>$row_qst['question_type_id'],
                                                            "priority"=>$q,
                                                            "quiz_id"=>$quiz_id == -1 ? $row_qst['quiz_id'] : $quiz_id,
                                                            "point"=>$row_qst['point'],
                                                            "penalty_point"=>$row_qst['penalty_point'],
                                                            "parent_id"=>$parent_id_zero == true ? 0 : $row_qst['id'],
                                                            "added_date"=>util::Now(),
                                                            "header_text"=>$row_qst['header_text'],
                                                            "footer_text"=>$row_qst['footer_text'],
                                                            "success_msg"=>$row_qst['success_msg'],
                                                            "psuccess_msg"=>$row_qst['psuccess_msg'],
                                                            "unsuccess_msg"=>$row_qst['unsuccess_msg'],                                                          
                                                            "subject_id"=>$row_qst['subject_id'],  
                                                            "inserted_by"=>$row_qst['inserted_by'],
                                                            "diff_level_id"=>$row_qst['diff_level_id'],
                                                            "inserted_date"=>$row_qst['inserted_date'],
                                                            "video_file"=>$row_qst['video_file'],
                                                            "parent_quiz_id"=>$row_qst['quiz_id']
                                                            )
                                      ),true);
          
            $last_qst_id=$db->insert_query($query);

            $res_grp = $db->query(orm::GetSelectQuery("question_groups", array(), array("question_id"=>$row_qst['id'],"parent_id"=>"0"), ""));

            while($row_grp=$db->fetch($res_grp))
            {
                $query = orm::GetInsertQuery("question_groups", array("group_name"=>$row_grp['group_name'],
                                                                      "show_header"=>$row_grp['show_header'],
                                                                      "question_id"=>$last_qst_id,
                                                                      "parent_id"=>$parent_id_zero == true ? 0 : $row_grp['id'],
                                                                      "added_date"=>util::Now()

                ));
                $last_grp_id=$db->insert_query($query,true);

                $res_ans = $db->query(orm::GetSelectQuery("answers", array(), array("group_id"=>$row_grp['id'],"parent_id"=>"0"), ""));

                while($row_ans=$db->fetch($res_ans))
                {
                    $query = orm::GetInsertQuery("answers", array("group_id"=>$last_grp_id,
                                                                      "answer_text"=>$row_ans['answer_text'],
                                                                        "correct_answer"=>$row_ans['correct_answer'],
                                                                        "priority"=>$row_ans['priority'],
                                                                        "correct_answer_text"=>$row_ans['correct_answer_text'],
                                                                        "answer_pos"=>$row_ans['answer_pos'],
                                                                        "parent_id"=>$parent_id_zero == true ? 0 : $row_ans['id'],
                                                                        "control_type"=>$row_ans['control_type'],
                                                                        "answer_point"=>$row_ans['answer_point'],
                                                                        "answer_parent_id"=>$row_ans['answer_parent_id']                                                                      
                    ),true);
                    $last_ans_id=$db->insert_query($query);
                }

            }

        }

      }
      
      public static function UpdateQuestionValue($db,$qst_id,$user_quiz_id,$qst_type,$post_data)
      {
      
            $inserted =false;
            $current_qst_id=intval($qst_id);
            $db->query(orm::GetDeleteQuery("user_answers", array("user_quiz_id"=>$user_quiz_id , "question_id"=>$current_qst_id)));
            
            $date = date('Y-m-d H:i:s');
            switch ($qst_type) {

                case 0 : // if checkbox
                    $chks = explode(";|",$post_data);
                    for($i=0;$i<sizeof($chks);$i++)
                    {
                        $chk_value=trim(urldecode($chks[$i]));
                        if($chk_value=="") continue;

                        $chk_value = intval($chk_value);
                        $query = orm::GetInsertQuery("user_answers", array("user_quiz_id"=>$user_quiz_id,
                                                                           "question_id"=>intval($current_qst_id),
                                                                           "answer_id"=>$chk_value,
                                                                           "user_answer_id"=>$chk_value,
                                                                           "added_date"=>$date
                                                                     ));
                        $db->query($query);
                        $inserted=true;
                    }
                break;
                case 1 : //if radio button
                        $chk_value=trim($post_data);                       
                        if($chk_value!="")
                        {
                           $chk_value = intval($chk_value);
                           $query = orm::GetInsertQuery("user_answers", array("user_quiz_id"=>$user_quiz_id,
                                                                           "question_id"=>intval($current_qst_id),
                                                                           "answer_id"=>$chk_value,
                                                                           "user_answer_id"=>$chk_value,
                                                                           "added_date"=>$date
                                                                    ));                         
                           $db->query($query);
                           $inserted=true;
                        }
                break ;
                case 3 : // if free text area
                        $free_vals = explode(";|",$post_data);
                        $answer_id=urldecode($free_vals[0]);
                        $answer_text=urldecode($free_vals[1]);
                        if(trim($answer_text)!="")
                        {                    
                           $query = orm::GetInsertQuery("user_answers", array("user_quiz_id"=>$user_quiz_id,
                                                                           "question_id"=>intval($current_qst_id),
                                                                           "answer_id"=>$answer_id,
                                                                           "user_answer_text"=>$answer_text,
                                                                           "added_date"=>$date
                                                                    ));
                           $db->query($query);
                           $inserted=true;
                        }
               break ;
               case 4 : // if muti text
                    $txts = explode(";|",$post_data);
                    for($i=0;$i<sizeof($txts);$i++)
                    {
                        $txt_key_value=trim(urldecode($txts[$i]));                 
                        if($txt_key_value=="") continue;

                        $txt_exp=explode(":|",$txt_key_value);
                        $txt_key = intval(urldecode($txt_exp[0]));
                        $txt_value = urldecode($txt_exp[1]);

                        if(trim($txt_key)=="" || trim($txt_value)=="") continue ;

                        $query = orm::GetInsertQuery("user_answers", array("user_quiz_id"=>$user_quiz_id,
                                                                           "question_id"=>intval($current_qst_id),
                                                                           "answer_id"=>$txt_key,
                                                                           "user_answer_text"=>$txt_value,
                                                                           "added_date"=>$date
                                                                     ));
                        $db->query($query);
                        $inserted=true;
                    }

                break;

            }
            
            return $inserted;
      }
      
      public static function UpdateQuesitonPoints($db,$user_quiz_id,$question_id,$calc_mode, $set_true=0,$check_true=false)
      {                   
           $is_true = $set_true;
           if($check_true)
           {               
                $is_true = $db->query_single_value(orm::GetSelectQuery("assignment_question_points", array("is_true"), array("user_quiz_id"=>$user_quiz_id,"question_id"=>$question_id), ""),"is_true");                                                                             
           }
           if($is_true=="") $is_true = 0;

           $db->query(orm::GetDeleteQuery("assignment_question_points", array("user_quiz_id"=>$user_quiz_id, "question_id"=>$question_id)));
           $db->query(asgDB::UpdateQstPointQuery($user_quiz_id, $question_id, $calc_mode,$is_true));
          
      }
      
      public static function RecalcUserResults($db,$user_quiz_id,$calc_mode,$check_true=false)
      {          
          $db_res = $db->query("SELECT DISTINCT question_id  as question_id FROM user_answers where user_quiz_id=$user_quiz_id");
          while($db_row = db::fetch($db_res))
          {                      
              questions_util::UpdateQuesitonPoints($db, $user_quiz_id, $db_row['question_id'], $calc_mode,0, $check_true);
          }
      }
      
     public static function CopyQuiz($quiz_id_in,$quiz_id, $show_questions,$random,$random_type,$randomize_where, $user_id )
     {        
          global $db;
          $last_quiz_id=$db->insert_query("insert into quizzes (cat_id,quiz_name,quiz_desc,added_date,parent_id,branch_id,inserted_by,inserted_date) select cat_id,quiz_name,quiz_desc,added_date,id,branch_id,inserted_by,inserted_date from quizzes where parent_id=0 and id=$quiz_id");      

          $random_qst_count = intval(db::clear($random)); // $_POST['txtRandom']

          $subject_where = "";
          if($subject_list!="")
          {
              $subject_where = " and subject_id in ($subject_list) " ;
          }

          if($show_questions!="1")
          {
		  
              $group_by_subject = $random_type == "1" ? false : true; // $_POST['drpRandomType']
              $db->query("set @type = ''");
              $db->query("set @num  = 0");
              $db->query("set @diff_num  = 0");
              $db->query("set @diff_type  = ''");
              $db->query("set @sb_type  = ''");
              $db->query("set @sb_num = 0");
              $db->query("set @sb_type2 = ''");
              $db->query("set @sb_num2 = 0");
              $db->query("set @z_type = ''");
              $db->query("set @z_num = 0");
          
			 if($show_questions!=3) $res_qst = $db->query_as_array(questions_db::GetRandomizedQuestions($random_qst_count, " and is_arch=0 and $quiz_id_in ", $group_by_subject, $randomize_where, $user_id));
             //  $res_qst = $db->query_as_array("select * from questions where parent_id=0 limit 0,30");
          }
          else $res_qst = $db->query_as_array(orm::GetSelectQuery("questions", array(), array("parent_id"=>"0","is_arch"=>"0"), "priority", false,true," and $quiz_id_in "));
          //CopyQuestions($res_qst,$last_quiz_id);          
          
          if($show_questions==3) questions_util::CopyQuestions2($random_qst_count, $quiz_id_in, $last_quiz_id, $group_by_subject, $randomize_where, $user_id);
          else questions_util::CopyQuestions($db, $res_qst, $last_quiz_id,false,false,"difflevel_id");

          return $last_quiz_id;

     }
     
    public static function CopyAsgQuestionsBulk($asg_id, $quiz_id = -1)
    {
        $quiz_where = " SELECT u_quiz_id from assignment_users where assignment_id=$asg_id ";
        if($quiz_id!=-1)
        {
            $quiz_where = $quiz_id;
        }
        global $db;     
        $sql = "insert INTO question_groups ( group_name, question_id, parent_id, added_date)
                SELECT qgo.group_name,q.id, qgo.id, NOW()
                from questions q
                inner join questions qo ON qo.id=q.parent_id
                inner JOIN question_groups qgo ON qgo.question_id=qo.id
                where q.quiz_id in ( $quiz_where )";
        $db->query($sql);
        
        $sql = "INSERT into answers (group_id,answer_text,correct_answer, priority, correct_answer_text,answer_pos,parent_id, control_type,answer_desc,answer_parent_id,answer_point)
                SELECT qg.id, ao.answer_text,ao.correct_answer,ao.priority,ao.correct_answer_text,ao.answer_pos, ao.id, ao.control_type,ao.answer_desc,ao.answer_parent_id, ao.answer_point
                from questions q
                inner join question_groups qg ON qg.question_id=q.id
                inner join questions qo ON qo.id=q.parent_id
                inner JOIN question_groups qgo ON qgo.question_id=qo.id
                inner join answers ao on ao.group_id=qgo.id
                where q.quiz_id in ( $quiz_where )
                ORDER by ao.group_id, ao.priority";
        $db->query($sql);
    }
    
    public static function CopyQuestions2($random_qst_count,$quiz_id_in,$last_quiz_id, $group_by_subject, $randomize_where,$user_id)
    {
        global $db;        
        
        $sql = "insert into questions(question_type_id,
                                      priority,
                                      quiz_id,
                                      point,
                                      penalty_point,
                                      parent_id,
                                      added_date,
                                      header_text,
                                      footer_text,
                                      success_msg,
                                      psuccess_msg,
                                      unsuccess_msg,
                                      subject_id,                                      
                                      diff_level_id,
                                      inserted_by,
                                      inserted_date,                                      
                                      is_arch,
                                      qst_mode,
                                      qst_comments,
                                      video_file,
                                      parent_quiz_id,									  
                                      question_text                                      
                )"; 
                
        $now = util::Now();        
        $columns = "q.question_type_id,@rn:=@rn+1,$last_quiz_id,q.point,q.penalty_point,q.id,'".$now."',q.header_text,q.footer_text,q.success_msg,q.psuccess_msg,q.unsuccess_msg,q.subject_id,q.difflevel_id,$user_id,'".$now."',q.is_arch,q.qst_mode,q.qst_comments,q.video_file,q.quiz_id";
        
        $sql.=questions_db::GetRandomizedQuestions($random_qst_count, " and is_arch=0 and $quiz_id_in ", $group_by_subject, $randomize_where, $user_id, $columns);
        
        
        $db->query($sql);
    
        return array($quiz_id,$last_qst_id);

      }
     
      
      
}

?>