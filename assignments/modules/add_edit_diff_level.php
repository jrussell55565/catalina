<?php if(!isset($RUN)) { exit(); } ?>
<?php 

access::menu("qst_diff_levels");    

require "lib/libmail.php";
require "lib/rtemplates.php";
require "db/tickets_db.php";
require "d_controls.php";


$val = new validations("btnSave");

$db = new db();
$db->connect();
$c = new d_controls($db,4 , $val);
$c->insert_main_tx=false;
$c->insert_user_info=false;
$c->current_user_id = access::UserInfo()->user_id;
$c->current_branch_id = access::UserInfo()->branch_id;
$c->DrowHtml(true);
$c->SaveData(true,true);
$db->close_connection();

//if(isset($_POST['btnSave'])) util::redirect ("?module=tickets");

function desc_func()
{
    return L_QST_NEW_DIFF_LEVEL;
}

?>