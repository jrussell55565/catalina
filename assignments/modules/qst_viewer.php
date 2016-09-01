<?php	
	
    class qst_viewer
    {
        var $html = "";
        var $show_next = true;
        var $show_prev = true;
        var $page_name = "";
        var $show_finish = false;
        var $show_correct_answers = false;
        var $print_version =false;
        var $show_mark = false;

        var $read_only_text = "";

        var $user_quiz_id=-1;
        var $ans_priority="1";
        var $control_unq;
        var $ids = "";
        var $video_enabled = true;
        var $edit_enabled = true;
        var $show_success_msg = false;
        var $success_type = 0;
        
        var $show_point_info = false; 
        var $question_point = 0;
        var $total_point=0;
        var $penalty_point=0;
        var $quiz_type = 1;
        var $mobile = false;
        var $calc_mode = 1 ;

        public function qst_viewer($page)
        {
            $this->page_name = $page;
        }

        private function BuildButtons($template,$row)
        {
            global $mobile;
            $buttons_html = "<tr><td align=left>";
            if($this->show_prev==true)
            {
                $prev_js = "javascript:PrevQst('".$this->page_name."',$row[question_type_id],$row[prev_priority],$row[id])";
                $buttons_html .= "<input class='btn green'  type=button onclick=".$prev_js." style='width:130px' value='< ".PREVIOUS."'>&nbsp;";
            }
            if($this->show_next==true)
            {
                $next_js = "javascript:NextQst('".$this->page_name."',$row[question_type_id],$row[next_priority],$row[priority],$row[id],0)";
                $buttons_html .= "<input class='btn green' type=button onclick=".$next_js." style='width:130px' value='".NEXT." >'>";
            }
            if($this->show_finish==true)
            {
                $finish_js = "javascript:NextQst('".$this->page_name."',$row[question_type_id],$row[next_priority],$row[priority],$row[id],1)";
                $buttons_html .="<input class='btn green'  onclick=".$finish_js." type=button style='width:130px' value='".FINISH."'>";
            }
            if($this->show_mark==true && $mobile==false)
            {
                $finish_js = "javascript:MarkQst('".$this->page_name."',".$row['id'].")";
                $buttons_html .="&nbsp;<input class='btn green'  onclick=".$finish_js." type=button style='width:130px' value='".MARK_REV."'>";
            }
            $buttons_html .= "</td></tr>";
            $template = str_replace("[buttons]", $buttons_html, $template);
            return $template;
        }

        var $additional_text="";
        public function BuildQuestion($row)
        {         
            if($this->user_quiz_id>-1)
            {                                
                $ans_results = questions_db::GetAnswersByQstID2($row['id'],$this->user_quiz_id, $this->ans_priority);                  
            }
            else
            {            
                $ans_results = questions_db::GetAnswersByQstID($row['id']);                    
            }
          
            
            if(trim($row['video_file'])=="")
            {
                $template = qst_viewer::ReadTemplate("1");
                $template = str_replace("[question_text]", qst_viewer::get_answer_text($row['question_text']), $template);
            }
            else 
            {                
                $template = qst_viewer::ReadTemplate("2");
                $template = str_replace("[question_text]", qst_viewer::get_answer_text($row['question_text']), $template);
                
                $video_template = $this->ReadVideoTemplate();     
                
                $video_pos = strpos($template, "VIDEO_TABLE");
                
                if($video_pos === false)
                {
                    $template = str_replace("[video_template]", $video_template, $template);
                }
                else
                {
                    $template = str_replace("VIDEO_TABLE", "<table style='width:100%'>".$video_template."</table>", $template);
                    $template = str_replace("[video_template]", "", $template);
                }
                
                $template = str_replace("[video_file]", $row['video_file'], $template);
                
            }
            
            $template = str_replace("[additional_text]", $this->additional_text, $template); //  [additional_text]
            $template = str_replace("[footer_text]", $row['footer_text'], $template);
            $template = str_replace("[header_text]", $row['header_text'], $template);
            
            $template = str_replace("[id]", $row['id'], $template);
//	    $this->show_correct_answers == true ? 
            $display = "";
            if(!$this->video_enabled) $display = "none";
            
            $template = str_replace("[video_display]", $display, $template);

            if($this->print_version==false) $answers_html = $this->BuildAnswers($ans_results, $row['question_type_id']);
            else $answers_html = $this->BuildPrintAnswers($ans_results, $row['question_type_id']);
            
            $pos = strpos($template, "ANSWERS_TABLE");
            
            $answers_template = qst_viewer::ReadAnswersTemplate();            
            $answers_template = str_replace("[answers]", $answers_html, $answers_template);
            
            if($pos===false)
            {
                $template = str_replace("[answers_template]", $answers_template, $template);
            }
            else
            {
                $template = str_replace("ANSWERS_TABLE", "<table style='width:100%'>".$answers_template."</table>", $template);
                $template = str_replace("[answers_template]", "", $template);
            }
            
            $template = str_replace("[group_name]", $row['group_name'], $template);
            
            $template = str_replace("[answers]", $answers_html, $template);
            $template = $this->BuildButtons($template,$row);

            $hiddens = "<input type=hidden name=hdnPriority id=hdnPriority value=".$row['priority']."><input type=hidden name=hdnNextPriority id=hdnNextPriority value=".$row['next_priority'].">";
            $template = str_replace("[hiddens]", $hiddens, $template);

            $template = $this->AddPointInfo($template,$row);
            $template = $this->AddSuccessMsg($template,$row);            
            
            $this->html =  $template;

            return $row;

        }
        
        public function AddPointInfo($template,$row)
        {
            $point_display = "none";
            $calc_mode_display ="";
            $point_info="";
            if($this->calc_mode=="2") {
                $calc_mode_display = "none";            
               // $this->penalty_point=0;
            }
            if($this->show_point_info==true)
            {              
                $point_display="";
                $question_point_text = $this->quiz_type == "1" ? QUESTION_MAX_POINT : QUESTION_MAX_PERCENT;
                $your_point_text = $this->quiz_type == "1" ? YOUR_POINT : YOUR_PERCENT;
                $point_info.="<table><tr style=\"display:$calc_mode_display\"><td>$question_point_text : </td><td>&nbsp;".$this->question_point."</td></tr>".
                             "<tr><td>$your_point_text : </td><td>&nbsp;".$this->total_point."</td></tr>";
                if($this->quiz_type == "1")
                {
                    $point_info.="<tr><td>".PENALTY_POINT." : </td><td>&nbsp;".$this->penalty_point."</td></tr>";
                }
                $point_info.="</table>";
            }
            
            $template = str_replace("[point_display]",$point_display , $template);
            $template = str_replace("[point_info]",$point_info , $template);
            return $template;
        }
        
        public function AddSuccessMsg($template,$row)
        {
            $success_display = "none";
            $success_msg = "";
            $color ="green";
            if($this->show_success_msg==true)
            {
                $success_display="";   
                $success_img = "unsuccess.png";
                $success_msg = $row["unsuccess_msg"];
                $color = "red";
                if($this->success_type==1)
                {
                    $success_img = "success.png";
                    $success_msg = $row["success_msg"];
                    $color ="green";
                }
                else if($this->success_type==2)
                {
                    $success_img = "5050.jpg";
                    $success_msg = $row["psuccess_msg"];
                    $color = "green";
                }
                
                $template = str_replace("[success_icon]","$success_img" , $template);
                
            }
            
            $success_msg = str_replace("[max_point]",$this->total_point , $success_msg);
            $success_msg = str_replace("[your_point]",$this->total_point , $success_msg);
            
            $template = str_replace("[success_display]",$success_display , $template);
            $template = str_replace("[success_msg]","<font color=$color>".$success_msg."</font>" , $template);
            return $template;
            
        }
		
	public static function get_answer_text($answer_text)
        {            
            $results = $answer_text;
            if(USE_MATH=="yes")
            {
		$results=mathfilter($answer_text,"14","mathpublisher/img/");				
            }
            return $results;
        }
        
        public function BuildQuestionWithQuery($query)
        {
             $rows = db::exec_sql($query);
             $row=db::fetch($rows);
             $this->BuildQuestion($row);
        }

        public function BuildQuestionWithResultset($resultset)
        {
            $this->BuildQuestion($resultset);
        }
        
        public function GetIDS()
        {
            $ids = $this->ids;
            if($ids!="")
            {
                $ids = substr($ids,1); 
            }
            return $ids;
        }
        
        public function BuildAnswers($ans_results,$question_type)
        {
             $control_unq = $this->control_unq;             
             $answers_html="";
             $tabs = "&nbsp;&nbsp;&nbsp;";
             while($row=db::fetch($ans_results))
             {                              
                  $correct_answer = "";
                  $answers_val="";
                  $answer_desc = $row['answer_desc'];
                  $id= $row['a_id'];
                  $this->ids .= ",$id";
                  $control_disabled = "";
                  if($this->edit_enabled==false) $control_disabled = "disabled";
                  switch($question_type) {
                  case 0:                      
                      if($this->show_correct_answers==true && $row['correct_answer']=="1") $correct_answer = "<font color=red>$tabs (".CORRECT_ANSWER.")</font>";
                      if($this->user_quiz_id>-1 && $row['user_answer_id']!="") $answers_val = "checked";
                      $answers_html.= "<tr><td ><input class=els ".$answers_val." type=checkbox $control_disabled id=chkAns ".$this->read_only_text." name=chkAns value='".$row['a_id']."'></td><td align=left style=\"width:99%\" ><span id=s$id class=\"ttip_t\" title=\"$answer_desc\">".qst_viewer::get_answer_text($row['answer_text'])."</span>$correct_answer</td></tr>";
                  break;
                  case 1:
                      if($this->show_correct_answers==true && $row['correct_answer']=="1") $correct_answer = "<font color=red>$tabs (".CORRECT_ANSWER.")</font>";
                      if($this->user_quiz_id>-1 && $row['user_answer_id']!="") $answers_val = "checked";
                      $answers_html.=  "<tr><td ><input class=els ".$answers_val." type=radio $control_disabled id=rdAns$control_unq ".$this->read_only_text." name=rdAns$control_unq value='".$row['a_id']."'></td><td  align=left style=\"width:99%\" ><span id=s$id  class=ttip_t title=\"$answer_desc\">".qst_viewer::get_answer_text($row['answer_text'])."</span>$correct_answer</td></tr>";
                  break;
                  case 3:
                      if($this->show_correct_answers==true) $correct_answer = "<br><font color=red>".CORRECT_ANSWER." : ".$row['correct_answer_text']."</font>";
                      if($this->user_quiz_id>-1 && $row['user_answer_text']!="") $answers_val = $row['user_answer_text'];
                      $answers_html.=  "<tr><td class=desc_text_bg><textarea class='form-control' $control_disabled style='width:450px;height:140px' id=txtFree$control_unq ".$this->read_only_text." name=txtFree$control_unq value='".$row['a_id']."'>".$answers_val."</textarea>$correct_answer".
                                       "<input type=hidden name=txtFreeId$control_unq id=txtFreeId$control_unq value='".$row['a_id']."'></td></tr>";
                  break;
                  case 4:
                      if($this->show_correct_answers==true) $correct_answer = "$tabs<font color=red>".CORRECT_ANSWER." : ".$row['correct_answer_text']."</font>";
                      if($this->user_quiz_id>-1 && $row['user_answer_text']!="") $answers_val = $row['user_answer_text'];
                      $answers_html.=  "<tr><td  class=desc_text_bg>".qst_viewer::get_answer_text($row['answer_text'])."</td><td class=desc_text_bg align=left ><input $control_disabled type=text class='form-control input-medium' onkeypress='return onlyNumbers(event);' id=txtMultiAns ".$this->read_only_text." name=txtMultiAns value='".$answers_val."' >".
                                       "<input type=hidden id=txtMultiAnsId name=txtMultiAnsId value='".$row['a_id']."' >$correct_answer</td></tr>";
                  break;
                        }
             }
             return $answers_html;
        }
        
        public function BuildPrintAnswers($ans_results,$question_type)
        {
             global $LETTERS;
             $control_unq = $this->control_unq;             
             $answers_html="";
             $tabs = "&nbsp;&nbsp;&nbsp;";
             $i=0;
             while($row=db::fetch($ans_results))
             {                              
                  $correct_answer = "";
                  $answers_val="";
                  $answer_desc = $row['answer_text'];
                  $id= $row['a_id'];
                  $this->ids .= ",$id";
                  $control_disabled = "";
             //     if($this->edit_enabled==false) $control_disabled = "disabled";
                  switch($question_type) {
                  case 0:                      
                      if($this->show_correct_answers==true && $row['correct_answer']=="1") $correct_answer = "<font color=red>$tabs (".CORRECT_ANSWER.")</font>";
                      if($this->user_quiz_id>-1 && $row['user_answer_id']!="") $answers_val = "checked";
                      $answers_html.= "<tr><td>".$LETTERS[$i].")</td><td align=left style=\"width:99%\" ><span id=s$id class=\"ttip_t\" title=\"$answer_desc\">".qst_viewer::get_answer_text($row['answer_text'])."</span>$correct_answer</td></tr>";
                  break;
                  case 1:
                      if($this->show_correct_answers==true && $row['correct_answer']=="1") $correct_answer = "<font color=red>$tabs (".CORRECT_ANSWER.")</font>";
                      if($this->user_quiz_id>-1 && $row['user_answer_id']!="") $answers_val = "checked";
                      $answers_html.=  "<tr><td >".$LETTERS[$i].")</td><td  align=left style=\"width:99%\" ><span id=s$id  class=ttip_t title=\"$answer_desc\">".qst_viewer::get_answer_text($row['answer_text'])."</span>$correct_answer</td></tr>";
                  break;
                  case 3:
                      if($this->show_correct_answers==true) $correct_answer = "<br><font color=red>".CORRECT_ANSWER." : ".$row['correct_answer_text']."</font>";
                      if($this->user_quiz_id>-1 && $row['user_answer_text']!="") $answers_val = $row['user_answer_text'];
                      $answers_html.=  "<tr><td class=desc_text_bg><textarea $control_disabled style='width:550px;height:200px' id=txtFree  name=txtFree value='".$row['a_id']."'>".$answers_val."</textarea>$correct_answer".
                                       "<input type=hidden name=txtFreeId id=txtFreeId value='".$row['a_id']."'></td></tr>";
                  break;
                  case 4:
                      if($this->show_correct_answers==true) $correct_answer = "$tabs<font color=red>".CORRECT_ANSWER." : ".$row['correct_answer_text']."</font>";
                      if($this->user_quiz_id>-1 && $row['user_answer_text']!="") $answers_val = $row['user_answer_text'];
                      $answers_html.=  "<tr><td  class=desc_text_bg>".qst_viewer::get_answer_text($row['answer_text'])."</td><td class=desc_text_bg align=left ><input  style='width:300px' $control_disabled type=text onkeypress='return onlyNumbers(event);' id=txtMultiAns  name=txtMultiAns value='".$answers_val."' >".
                                       "<input type=hidden id=txtMultiAnsId name=txtMultiAnsId value='".$row['a_id']."' >$correct_answer</td></tr>";
                  break;
                        }
                    $i++;  
             }
             return $answers_html;
        }


        public static function ReadTemplate($str)
        {
            $file = file_get_contents('tmps/question_template_'.$str.'.xml', true);
            return $file;
        }
        
        public static function ReadAnswersTemplate()
        {
            $file = file_get_contents('tmps/answers.xml', true);
            return $file;
        }
        
        public function ReadVideoTemplate()
        {
            $file_name = "tmps/video_flash.xml";
            if($this->mobile ==true ) $file_name = "tmps/video_html5.xml";
            $file = file_get_contents($file_name, true);
            return $file;
        }

        public function SetReadOnly()
        {
            $this->read_only_text="disabled";
        }

        public function GetPriority()
         {
             $priority = 1;
             if(isset($_POST['hdnPriority']))
             {
                 $priority = intval($_POST['hdnPriority']);
             }
             return $priority;
         }

        public function GetNextPriority()
         {
             $priority = 1;
             if(isset($_POST['next_priority']))
             {
                 $priority = $_POST['next_priority'];
             }
             return $priority;
         }
         
          public function GetCurrentPriority()
         {
             $priority = 1;
             if(isset($_POST['current_priority']))
             {
                 $priority = $_POST['current_priority'];
             }
             return $priority;
         }

         public function GetPrevPriority()
         {
             $priority = 1;
             if(isset($_POST['prev_priority']))
             {
                 $priority = $_POST['prev_priority'];
             }
             return $priority;
         }
        
    }

?>
