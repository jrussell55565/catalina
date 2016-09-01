<?php

class tickets_db
{
    public static function GetTicketsQuery($where="",$where_empty=false,$orderby = "tx.id desc")
    {
        $sql = "SELECT t.*,u.FullName as cr_full_name, 
                dc.value_text AS CatName,
                ds.value_text AS StatusName,
                ds.system_code as status_system_code,
                dp.value_text AS PriorityName ,
                tu.FullName as tech_full_name ,
                IFNULL(tr.viewed,0) AS viewed ,
                tx.inserted_date,tx.inserted_by
                FROM tickets t 
                INNER JOIN d_txs tx on tx.id= t.tx_id
                LEFT JOIN v_all_users u ON tx.inserted_by = u.UserID
                LEFT JOIN v_all_users tu on tu.UserID = t.tech_user_id                
                LEFT JOIN d_dics dc ON dc.dic_id = 3 AND dc.id = cat_id
                LEFT JOIN d_dics ds ON ds.dic_id = 2 AND ds.id = status_id
                LEFT JOIN d_dics dp ON dp.dic_id = 4 AND dp.id = t_priority 
                LEFT JOIN ticket_read tr ON tr.ticket_id = t.id 
                AND tr.user_id= ".access::UserInfo()->user_id." WHERE 1=1 ".au::get_where(true, "tx")." $where [{where}] order by $orderby";
        if($where_empty==true) $sql = str_replace ("[{where}]", "", $sql);
        return $sql;
        
        // tr.viewed,tx.id desc
    }
    
    public static function GetTicketRepliesQuery($where="")
    {
        $sql = "SELECT tr.*,
                       u.Name,u.Surname
                FROM ticket_replies tr
                left JOIN users u ON tr.inserted_by = u.UserID 
                where 1=1 $where";
        return $sql;
    }
    
    public static function GetRoleTicketViews($role_id, $where="")
    {
        $sql = "SELECT tv.* FROM ticket_views tv
                INNER JOIN tview_role_xreff trx ON trx.tview_id = tv.id
                INNER JOIN roles r on r.id=$role_id
                WHERE trx.role_id = $role_id $where
                ORDER BY (CASE r.default_view WHEN tv.id THEN 0 else 1 END) ";
        return $sql;
                
    }
    
    public static function GetReplyUsers($ticket_id)
    {
        $sql = "SELECT DISTINCT(tr.inserted_by) AS r_inserted_by, vau.FullName , vau.email
                FROM ticket_replies tr 
                INNER JOIN v_all_users vau ON vau.UserID = tr.inserted_by
                WHERE tr.ticket_id=$ticket_id
                ORDER BY tr.inserted_date";
        return $sql;
    }
    
    public static function GetReplyFiles($ticket_id)
    {
        $sql = "SELECT tf.real_file_name FROM treply_files tf 
                LEFT JOIN ticket_replies tr ON tf.reply_id = tr.id
                WHERE tf.ticket_id= $ticket_id";
        return $sql;
    }
    
    public static function DeleteReplyFilesByTicketID($ticket_id)
    {
        $sql ="delete from treply_files where reply_id in (select id from ticket_replies where ticket_id=$ticket_id)";
        return $sql;
    }
    
}

?>
