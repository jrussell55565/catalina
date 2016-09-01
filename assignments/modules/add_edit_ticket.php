<?php if(!isset($RUN)) { exit(); } ?>
<?php 

access::menu("create_ticket");

require "lib/libmail.php";
require "lib/rtemplates.php";
require "db/tickets_db.php";
require "d_controls.php";

if(isset($_GET["id"])) access::has("edit_ticket",2);
else access::has("create_ticket",2);

$val = new validations("btnSave");

$db = new db();
$db->connect();
$c = new d_controls($db,1 , $val);
$edit_id = isset($_GET["id"]) ? util::GetID() : -1;
$c->edit_query = tickets_db::GetTicketsQuery(" and tx.id=".$edit_id.au::view_where(), true);
$c->current_user_id = access::UserInfo()->user_id;
$c->current_branch_id = access::UserInfo()->branch_id;
$c->insert_user_info=false;
$c->DrowHtml(true);
$c->after_save_event = "send_mail";
$c->before_insert_override = "before_insert";
$c->SaveData(true,true);
$db->close_connection();

//if(isset($_POST['btnSave'])) util::redirect ("?module=tickets");

function send_mail()
{
    if(isset($_GET['id'])) return;
   
    global $c,$db;
   
    $results = $db->exec_sql(tickets_db::GetTicketsQuery(" and t.tx_id=".$c->tx_id, true));
    $row = db::fetch($results);
    
    $dep_results = $db->exec_sql(orm::GetSelectQuery("v_all_users", array("email"), au::arr_where_brn(array("rec_mails"=>"1", "disabled"=>"0","approved"=>"1"), ""),""));
   
    $users = array();   
    while($row_dep=db::fetch($dep_results))
    {
        $users[] = $row_dep['email'];
    }
    
    $res_temp=$db->exec_sql(orm::GetSelectQuery("email_templates", array(), array("name"=>"ticket_created"), ""));
    $row_temp = db::fetch($res_temp);
    
    $mail_subject = rtemplates::t_replace_values($row_temp['subject'], $row, 35);
    $mail_body = rtemplates::t_replace_values($row_temp['body'], $row);
    
    if(count($users)>0)
    util::SendMail($users, array(), $mail_subject, $mail_body);
}

function get_ticket_body()
{
    $body = "";
    $qst_id=-1;
    if(isset($_SESSION['issue_qstid'])) 
    {
        $qst_id = intval($_SESSION['issue_qstid']);
    }
    
    if($qst_id!=-1)
    {
        $body="[question_text] \n\n".$body;
    }
    
    return $body;
}

function before_insert($columns)
{
    if(isset($_SESSION['issue_qstid'])) 
    {
        $qst_id = intval($_SESSION['issue_qstid']);
        $columns['question_id'] = $qst_id;
        unset($_SESSION['issue_qstid']);
    }
    
    return $columns;
}

function desc_func()
{
    return CREATE_TICKET;
}

?>