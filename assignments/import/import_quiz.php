<?php

  
	require "../lib/util.php";
	require '../config.php';
	require '../db/mysql2.php';
        require '../db/questions_db.php';
	require '../db/access_db.php';  
	require "../lib/access.php";
	require "../db/orm.php";	
	require "../lib/webcontrols.php";	
      
        $has_access = false;
        if(isset($_POST['login']))
        {
            $login = db::esp(trim($_POST['login']));			
            $password = Local_Users_Password_Hash(trim($_POST['password']));			
            $results=access_db::GetModules($login, $password, $password, true);
            $has_result = sizeof($results)>0 ? true : false;
            if($has_result!=false )
            {
                $row = $results[0];
                if($row["system_row"]=="1")
                {
                    $has_access=true;
                    $data = $_POST['data'];
                }
            }
                        
        }

        if($has_access==false)
        {
            echo "Login or password is incorrect !";
            exit();            
        }
		
		$branch_id = $row["branch_id"];
		$inserted_by = $row["UserID"];
		$inserted_date = util::Now();
        
        $quiz = simplexml_load_string($data);
        
        $db = new db();
        $db->connect();	        
        
        try
        {
        
        $quiz_name = trim($quiz['QuizName']);
        $cat_name = trim($quiz['Category']);
	
        
        $cat_res = $db->query(orm::GetSelectQuery("cats", array(), array("cat_name"=>$cat_name), "id"));
        if(db::num_rows($cat_res)>0)
        {
            $cat_row = db::fetch($cat_res);
            $cat_id = $cat_row['id'];
        }
        else
        {
            $cat_id = $db->insert_query(orm::GetInsertQuery("cats", array("cat_name"=>$cat_name, "branch_id"=>$branch_id, "inserted_by"=>$inserted_by, "inserted_date"=>$inserted_date)));
        }
               
        $quiz_res = $db->query(orm::GetSelectQuery("quizzes", array(), array("quiz_name"=>$quiz_name, "cat_id"=>$cat_id), "id"));
        if(db::num_rows($quiz_res)>0)
        {
             $quiz_row = db::fetch($quiz_res);
             $quiz_id = $quiz_row['id'];
        }
        else
        {
             $quiz_id = $db->insert_query(orm::GetInsertQuery("quizzes", array("quiz_name"=>$quiz_name,"parent_id"=>"0", "added_date"=>util::Now(), "cat_id"=>$cat_id, "branch_id"=>$branch_id, "inserted_by"=>$inserted_by, "inserted_date"=>$inserted_date)));
        }        
            
	for($i=0;$i<count($quiz->question);$i++)
	{        
            
		$question_text = trim($quiz->question[$i]->text);                
                
                $question_type = trim($quiz->question[$i]->type);
                
                $header_text = trim($quiz->question[$i]->header_text);  
                $footer_text = trim($quiz->question[$i]->footer_text);  
                $point = trim($quiz->question[$i]->point);                              	
                $penalty_point = trim($quiz->question[$i]->penalty_point);     
                $success_message = trim($quiz->question[$i]->success_message);  
                $fail_message = trim($quiz->question[$i]->fail_message);                  
                $partly_success_message = trim($quiz->question[$i]->partly_success_message);  
                
                $diff_level_id = 2;
                if(isset($quiz->question[$i]->difficult))
                {
                    $diff_level = trim($quiz->question[$i]->difficult);  
                    if($diff_level=="Easy") $diff_level_id=1;
                    else if($diff_level=="Hard") $diff_level_id=3;
                    else $diff_level_id=2;
                }
				
				$subject_name = trim($quiz->question[$i]->subject);
						
				$subject_res = $db->query(orm::GetSelectQuery("subjects", array(), array("subject_name"=>$subject_name), "id"));
				if(db::num_rows($subject_res)>0)
				{
					$subject_row = db::fetch($subject_res);
					$subject_id = $subject_row['id'];
				}
				else
				{
					$subject_id = $db->insert_query(orm::GetInsertQuery("subjects", array("subject_name"=>$subject_name,"quiz_id"=>$quiz_id ,"branch_id"=>$branch_id, "inserted_by"=>$inserted_by, "inserted_date"=>$inserted_date)));
				}
				
                
                $question_type_id = 1;
                if(strtolower($question_type)=="multi answer") $question_type_id = 0;
                else if(strtolower($question_type)=="free text") $question_type_id = 3;
                else if(strtolower($question_type)=="multi text") $question_type_id = 4;
                
                $query = orm::GetInsertQuery("questions", array("question_text"=>$question_text,
                                                   "question_type_id"=>$question_type_id,
                                                   "priority"=>$i+1,
                                                   "quiz_id"=>$quiz_id,
                                                   "point"=>$point,                                                   
                                                   "parent_id"=>"0",
                                                   "footer_text"=>$footer_text,
                                                   "header_text"=>$header_text,
                                                    "penalty_point"=>$penalty_point, 
                                                    "diff_level_id"=>$diff_level_id,
                                                    "success_msg"=>$success_message, 
                                                    "unsuccess_msg"=>$fail_message, 
                                                    "psuccess_msg"=>$partly_success_message ,
													"subject_id" =>$subject_id
													, "branch_id"=>$branch_id, "inserted_by"=>$inserted_by, "inserted_date"=>$inserted_date
                                                   ));
                $question_id = $db->insert_query($query);
                
                $db->query(questions_db::UpdatePriorityQuery($quiz_id, $question_id));
                
                $query= orm::GetInsertQuery("question_groups", array("group_name"=>"",
                                                                 "show_header"=>0,
                                                                 "question_id"=>$question_id,
                                                                 "parent_id"=>"0"
                                                            ));
                $group_id =$db->insert_query($query);             
                
		for($y=0;$y<count($quiz->question[$i]->answers[0]->answer);$y++)
		{
                    
			$answer_text = $quiz->question[$i]->answers[0]->answer[$y]->text;
                        $answer_desc = $quiz->question[$i]->answers[0]->answer[$y]->desc;
                        $correct = $quiz->question[$i]->answers[0]->answer[$y]->correct;   
                        if($question_type_id==3 || $question_type_id==4)
                        {
                            $correct_answer = 0;
                            $correct_answer_text=$correct;
                        }
                        else
                        {
                            $correct_answer = strtolower($correct)=="yes" ? 1 : 0;
                            $correct_answer_text="";
                        }
                        
                        $query=orm::GetInsertQuery("answers", array("group_id"=>$group_id,
                                                            "answer_text"=>$answer_text,
                                                            "correct_answer"=>$correct_answer ,
                                                            "answer_desc"=>$answer_desc,
                                                            "correct_answer_text"=>$correct_answer_text ,
                                                            "priority"=>$y+1,
                                                            "answer_pos"=>"1",
                                                            "parent_id"=>"0",
                                                            "control_type"=>"1",
                                                            "answer_parent_id"=>"0"
                                                            )
                                        );
                
                        $db->query($query);
		}
		
	}
        
            $db->commit();
            echo "Success !";
        }
        catch(Exception $e)
        {                  
            $db->rollback();
            echo $e->getMessage().$e->getLine();            
        }

        $db->close_connection();
?>