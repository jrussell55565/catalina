<?php if(!isset($RUN)) { exit(); } ?>
<?php

 access::menu("tickets");
 access::has("read_ticket",2);

 require "extgrid.php";
 require "db/tickets_db.php";
 require "lib/tickets_util.php";
 require "db/questions_db.php";
 require "modules/qst_viewer.php";
 require "lib/rtemplates.php";
 require "lib/libmail.php";
 
 $ID = util::GetKeyID("id", "?module=tickets");

 $results = db::GetResultsAsArray(tickets_db::GetTicketsQuery("and t.id=$ID ".au::view_where(),true));

 if(sizeof($results)==0) util::redirect ("?module=tickets");
 
 tickets_util::MarkTicketAfterView(access::UserInfo()->user_id, $ID);
 
 $row = $results[0];  
 
 //[question_text]
 $tbody = util::replace_n(htmlspecialchars($row["t_body"]));
 //$tbody = $row["t_body"];
 if($row['question_id']!="")
 {
    $qst_viewer = new qst_viewer("#");
    $qst_viewer->mobile=$mobile;
    //$qst_viewer->video_enabled=false;

    $qst_viewer->show_prev=false;

    $qst_viewer->show_next=false;
    $qst_viewer->show_finish=false;
     $qst_viewer->SetReadOnly();

    $qst_query = questions_db::GetQuestionsByID($row['question_id']);      
    $row_qst = db::fetch(db::exec_sql($qst_query));  
    
    $qst_viewer->BuildQuestionWithResultset($row_qst);
    $qst_html = $qst_viewer->html;
    $qst_html="<table style='width:100%'>".$qst_html."</table>";
    $tbody = "<br />".str_replace("[question_text]", $qst_html, $tbody);    
 }

 if(isset($_POST["add_reply"]) && access::has("reply_ticket"))
 {     
     
     $comments = $_POST['txtComments'];
     if(trim($comments)!="")
     {
        $db = new db();
        $db->connect();
        $reply_id = $db->insert_query(orm::GetInsertQuery("ticket_replies", au::add_insert(array("ticket_id"=>$ID,"tr_body"=>$comments))));        
        
        if(!isset($_POST['ajax']))
        {           
            
               for($i=1;;$i++)
               {

                   $file_name = 'fileFiles'.$i;

                   if(!isset($_FILES[$file_name]['tmp_name'])) break;

                   $f_name = $_FILES[$file_name]['name'];
               //    $ext = pathinfo($f_name, PATHINFO_EXTENSION);

              //     $real_file_name = md5($f_name).".".$ext;
                   
                   if($f_name!="")
                   {
                        $real_file_name = md5($f_name). md5(util::Guid());
                        move_uploaded_file($_FILES[$file_name]['tmp_name'], 'uploads/d_controls/'.$real_file_name);
                        $db->query(orm::GetInsertQuery("treply_files", array("reply_id"=>$reply_id,"ticket_id"=>$ID, "file_name"=>$f_name, "real_file_name"=>$real_file_name)));
                   }
               }
        }
        
        if(isset($_POST['chkSendCopy']))
        {
            if($_POST['chkSendCopy']!="false")
            {
                $emails = array();
                get_user_emails($row['inserted_by']);
                get_user_emails($row['tech_user_id']);

                $replier_name = "";
                $reply_mail_results = $db->query(tickets_db::GetReplyUsers($ID));

                //$reply_rows_num = db::num_rows($reply_mail_results);
                while($reply_row = db::fetch($reply_mail_results))
                {
                    $emails[]=$reply_row['email'];
                    $replier_name=$reply_row['FullName'];
                }

                $res_temp=$db->query(orm::GetSelectQuery("email_templates", array(), array("name"=>"ticket_replied"), ""));
                $row_temp = db::fetch($res_temp);

                $mail_subject = rtemplates::t_replace_values($row_temp['subject'], $row, 35);
                $mail_body = rtemplates::t_replace_values($row_temp['body'], $row);

                $mail_subject = str_replace("[replier_name]", $replier_name, $mail_subject);
                $mail_body = str_replace("[replier_name]", $replier_name, $mail_body);

                if(count($emails)>0) util::SendMail($emails, array(), $mail_subject, $mail_body);
            }
        }
        
        $db->commit();
        $db->close_connection();
        
        tickets_util::MarkTicketAfterReply(access::UserInfo()->user_id, $ID);
        
        if(!isset($_POST['ajax'])) util::redirect (util::GetCurrentUrl ());
     }
 } 
 
 function get_user_emails($user_id)
 {     
     global $db,$emails;
     $results = $db->query(orm::GetSelectQuery("v_all_users", array("email"), array("UserID"=>$user_id, "disabled"=>"0","approved"=>"1"), ""));
     while($row=db::fetch($results))
     {
         $emails[]=$row['email'];
     }
 }
  
 
 $hedaers = array("&nbsp;");
 $columns = array("tr_body"=>"text");

 $url = "index.php?module=read_ticket&id=$ID";
 $grd = new extgrid($hedaers,$columns, $url);
 
 if($grd->IsClickedBtnDelete() && access::has("delete_reply_ticket"))
 {
     tickets_util::DeleteReply($grd->process_id);
 }
 $arr_reply_files  = db::GetResultsAsArray(orm::GetSelectQuery("treply_files", array(), array("ticket_id"=>$ID), "id"));    
 $arr_ticket_files  = db::GetResultsAsArray(orm::GetSelectQuery("d_files", array(), array("tx_id"=>$row['tx_id']), "id"));  
 $ticket_files_list = get_tickets_file_list($row);
  
 $grd->edit=false;
 $grd->delete=false;
 $grd->edit_link="index.php?module=add_edit_ticket";
 $grd->id_column="id";
 $grd->column_override = array("tr_body"=>"body_override");
 $grd->auto_id=false;
 $grd->show_header=false;
 $grd->PAGING=10000000;
 $grd->empty_data_text=NO_REPLIES;
 $query = tickets_db::GetTicketRepliesQuery("and ticket_id=$ID"); 
       
 $grd->search = true;
 $grd->DrowTable($query);
 $grid_html = $grd->table; 
  

 function desc_func()
 {
    return "Read ticket";
 }
 
 if(isset($_POST["ajax"]))
 {
    echo $grid_html;
 }
 if(isset($_GET["expgrid"]))
 {
    $grd->Export();
 }

 
 function body_override($row)
 {     
     global $grd;
     $file_list = get_replies_file_list($row);
     $delete_link = "&nbsp;<a href='javascript:jsProcessDelete(\"$grd->message\",".$row['id'].", \"$grd->page_name\", \"$grd->grid_control_name\" )'>(".REMOVE.")</a>";
     if(!access::has("delete_reply_ticket") || isset($_GET['expgrid'])) $delete_link="";
     return "<span class=comment_body>".util::replace_n(htmlspecialchars($row["tr_body"]), true)."</span><br><span class=commented_by>".$file_list."&nbsp;".COMMENTED_BY." : ".$row["Name"]." ".$row["Surname"]." ".ON." ".$row["inserted_date"]." ".$delete_link."</span>" ;
 }
 function get_replies_file_list($row)
 {
     global $arr_reply_files,$HELPDESK_FILE_FORMATS;     
     
     $arr = db::Select($arr_reply_files, "reply_id", $row['id']);
     
     if(count($arr)==0) return "";
     //echo count($arr)."<br />";
            $file_list = "<table border=1 style='border-width:2px'><tr ><td class='simple_text'>&nbsp;".ATTACHMENTS." : </td>";          
          
            for($i=0;$i<count($arr);$i++)
            {
                $row = $arr[$i];
                $ext =  strtolower(pathinfo($row["file_name"], PATHINFO_EXTENSION));
                if(in_array($ext,$HELPDESK_FILE_FORMATS)) $link = "<a href='d_download.php?type=2&id=".$row["id"]."'>".$row["file_name"]."</a>";
                else $link = "<a class='d_nfile'>".$row["file_name"]."</a>";  
                $file_list.="<td>$link&nbsp;&nbsp;</td>";
            }
            $file_list.="</tr></table>";
     
     return $file_list;
 }
 
 function get_tickets_file_list($row)
 {
     global $arr_ticket_files,$HELPDESK_FILE_FORMATS;     
     
     $arr = db::Select($arr_ticket_files, "tx_id", $row['tx_id']);
     
     if(count($arr)==0) return "";
     //echo count($arr)."<br />";
            $file_list = "<br><br><table cellpadding=5 cellspacing=5 border=1 style='border-width:2px'><tr ><td class='simple_text'>".ATTACHMENTS." : </td>";          
            
            for($i=0;$i<count($arr);$i++)
            {
                $row = $arr[$i];                
                $ext =  strtolower(pathinfo($row["file_name"], PATHINFO_EXTENSION));
                if(in_array($ext,$HELPDESK_FILE_FORMATS)) $link = "<a href='d_download.php?id=".$row["id"]."'>".$row["file_name"]."</a>";
                else $link = "<a class='d_nfile'>".$row["file_name"]."</a>";                
                $file_list.="<td>$link&nbsp;&nbsp;</td>";
            }
            $file_list.="</tr></table>";
     
     return $file_list;
 }
  
 
?>