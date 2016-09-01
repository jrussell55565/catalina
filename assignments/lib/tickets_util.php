<?php
class tickets_util
{
    public static function GetTicketViewQuery($view_id)
    {
        $and_sql = "";
        $results = db::exec_sql(tickets_db::GetRoleTicketViews(access::UserInfo()->role_id, "and tv.id=$view_id ".au::view_in_where("tv.")));
        if(db::num_rows($results)>0)
        {
            $row = db ::fetch($results);
            $and_sql = $row['sql_query'];
            $and_sql = str_replace("[user_id]" ,access::UserInfo()->user_id, $and_sql);
            $and_sql = str_replace("[branch_id]" ,access::UserInfo()->branch_id, $and_sql);
        }
        if($and_sql!="") $and_sql = "and $and_sql";
        return $and_sql;
    }
    
    public static function MarkTicketAfterView($user_id,$ticket_id,$viewed=1)
    {
        $db = new db();
        $db->connect();
        $results = $db->query(orm::GetSelectQuery("ticket_read", array("viewed"), array("ticket_id"=>$ticket_id,"user_id"=>$user_id), ""));        
        if(db::num_rows($results)==0) $db->exec_insert (orm::GetInsertQuery ("ticket_read", array("ticket_id"=>$ticket_id,"viewed"=>$viewed,"user_id"=>$user_id)));
        else $db->exec_insert (orm::GetUpdateQuery ("ticket_read", array("viewed"=>$viewed) , array("ticket_id"=>$ticket_id,"user_id"=>$user_id)));
        $db->commit();
        $db->close_connection();
    }
    
    public static function MarkTicketAfterReply($user_id,$ticket_id)
    {
        db::exec_sql("update ticket_read set viewed=0 where ticket_id=$ticket_id and user_id<>$user_id ");        
    }
    
    public static function AssignTicket($tech_user_id, $ticket_id)
    {
        $db = new db();
        $db->connect();
        
        $results = $db->GetResultsAsArray(tickets_db::GetTicketsQuery("and t.id=$ticket_id ".au::view_where(),true));
        if(count($results)>0)
        {
            $row = $results[0];
            $db->query(orm::GetUpdateQuery("tickets", array("tech_user_id"=>$tech_user_id) ,array("id"=>$ticket_id)));
            
            $mailto_results = $db->query(orm::GetSelectQuery("v_all_users", array(), array("UserID"=>$tech_user_id, "disabled"=>"0","approved"=>"1"), ""));
            $users = array();   
            $technichan_name="";
            while($row_user=db::fetch($mailto_results))
            {                
                $users[] = $row_user['email'];
                $technichan_name = $row_user['FullName'];
            }
            
            $res_temp=$db->query(orm::GetSelectQuery("email_templates", array(), array("name"=>"ticket_assigned"), ""));
            $row_temp = db::fetch($res_temp);

            $mail_subject = rtemplates::t_replace_values($row_temp['subject'], $row, 35);
            $mail_body = rtemplates::t_replace_values($row_temp['body'], $row);

            $assigner_name = access::UserInfo()->name.' '.access::UserInfo()->surname;

            $mail_subject = str_replace("[assigner_name]", $assigner_name, $mail_subject);
            $mail_body = str_replace("[assigner_name]", $assigner_name, $mail_body);
            $mail_subject = str_replace("[technican_name]", $technichan_name, $mail_subject);
            $mail_body = str_replace("[technican_name]", $technichan_name, $mail_body);

            if(count($users)>0) util::SendMail($users, array(), $mail_subject, $mail_body);
            
        }
        $db->commit();
        $db->close_connection();
    }
    
    public static function DeleteReply($reply_id)
    {
        $db = new db();
        $db->connect();
        
        $results = $db->query(orm::GetSelectQuery("ticket_replies", array("ticket_id"), array("id"=>$reply_id), ""));
        $row = db::fetch($results);
        $ticket_id = $row['ticket_id'];
        $results = $db->GetResultsAsArray(tickets_db::GetTicketsQuery("and t.id=$ticket_id ".au::view_where(),true));
        if(count($results)>0)
        {        
            $reply_files_res = $db->query(orm::GetSelectQuery("treply_files", array("real_file_name"), array("reply_id"=>$reply_id), ""));
            while($reply_file_row=db::fetch($reply_files_res))
            {
                @unlink("uploads".DIRECTORY_SEPARATOR."d_controls".DIRECTORY_SEPARATOR.$reply_file_row['real_file_name']);
            }
            $db->query(orm::GetDeleteQuery("treply_files", array("reply_id"=>$reply_id)));

            $db->query(orm::GetDeleteQuery("ticket_replies", array("id"=>$reply_id)));
        }
        
        $db->commit();
        $db->close_connection();
    }
    
    public static function DeleteTicket($ticket_id)
    {
        $db = new db();
        $db->connect();
        
        $results = $db->GetResultsAsArray(tickets_db::GetTicketsQuery("and t.id=$ticket_id ".au::view_where(),true));
        if(count($results)>0)
        {
            $row = $results[0];
            
            $reply_files_res = $db->query(tickets_db::GetReplyFiles($ticket_id));
            while($reply_file_row=db::fetch($reply_files_res))
            {
                @unlink("uploads".DIRECTORY_SEPARATOR."d_controls".DIRECTORY_SEPARATOR.$reply_file_row['real_file_name']);
            }
            $db->query(tickets_db::DeleteReplyFilesByTicketID($ticket_id));
            
            $tx_files_res = $db->query(orm::GetSelectQuery("d_files", array("real_file_name"), array("tx_id"=>$row['tx_id']), ""));
            while($tx_file_row=db::fetch($tx_files_res))
            {
                @unlink("uploads".DIRECTORY_SEPARATOR."d_controls".DIRECTORY_SEPARATOR.$tx_file_row['real_file_name']);
            }
            
            $db->query(orm::GetDeleteQuery("d_files", array("tx_id"=>$row['tx_id'])));
            $db->query(orm::GetDeleteQuery("ticket_read", array("ticket_id"=>$ticket_id)));                        
            $db->query(orm::GetDeleteQuery("ticket_replies", array("ticket_id"=>$ticket_id)));
            $db->query(orm::GetDeleteQuery("tickets", array("id"=>$ticket_id)));
            $db->query(orm::GetDeleteQuery("d_txs", array("id"=>$row['tx_id'])));
            
        }
        
        $db->commit();
        $db->close_connection();
    }
    
    public static function CloseTicket($ticket_id)
    {
        $db = new db();
        $db->connect();
        
        $results = $db->GetResultsAsArray(tickets_db::GetTicketsQuery("and t.id=$ticket_id ".au::view_where(),true));
        if(count($results)>0)
        {
            $row = $results[0];
            $cls_sql = orm::GetSelectQuery("d_dics", array("id"), array("system_code"=>100), "",false,false);              
            $db->query(orm::GetUpdateQuery("tickets", array("status_id"=>array("($cls_sql)",false)) ,array("id"=>$ticket_id), false));

            $mailto_results = $db->query(orm::GetSelectQuery("v_all_users", array("email"), array("UserID"=>$row['inserted_by'], "disabled"=>"0","approved"=>"1"), ""));
            $users = array();   
            while($row_user=db::fetch($mailto_results))
            {                
                $users[] = $row_user['email'];
            }
            
            $mailto_results = $db->query(orm::GetSelectQuery("v_all_users", array("email"), array("UserID"=>$row['tech_user_id'], "disabled"=>"0","approved"=>"1"), ""));
            while($row_user=db::fetch($mailto_results))
            {                
                $users[] = $row_user['email'];
            }
            
            $res_temp=$db->query(orm::GetSelectQuery("email_templates", array(), array("name"=>"ticket_closed"), ""));
            $row_temp = db::fetch($res_temp);

            $mail_subject = rtemplates::t_replace_values($row_temp['subject'], $row, 35);
            $mail_body = rtemplates::t_replace_values($row_temp['body'], $row);

            $closer_name = access::UserInfo()->name.' '.access::UserInfo()->surname;

            $mail_subject = str_replace("[closer_name]", $closer_name, $mail_subject);
            $mail_body = str_replace("[closer_name]", $closer_name, $mail_body);

            if(count($users)>0) util::SendMail($users, array(), $mail_subject, $mail_body);
        }
        $db->commit();
        $db->close_connection();
        //closer_name
    }
    
 
    
}

?>