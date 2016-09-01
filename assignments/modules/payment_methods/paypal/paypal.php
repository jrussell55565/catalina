<?php if(!isset($RUN)) { exit(); } ?>
<?php 

access::menu("my_balance");

$txt_display = "";
$buy_display = "none";

$p_account = db::exec_sql_single_value(orm::GetSelectQuery("user_payment_accounts", array("p_account"), array("user_id"=>access::UserInfo()->user_id), ""), "p_account");       

if($p_account!="")
{
    $txt_display = "none";
    $buy_display = "";
}

if(isset($_POST['ajax']))
{    
    if(isset($_POST['savemail']))
    {
        //, "user_id"=>access::UserInfo()->user_id
        $mail = db::clear(trim($_POST['email']));
        $account = db::exec_sql_single_value(orm::GetSelectQuery("user_payment_accounts", array("p_account"), array("p_account"=>$mail), ""), "p_account");       
        if($account != "")
        {
            echo json_encode(array("mtype"=>0,"msg"=>ACCOUNT_ALREADY_EXIST));
        }
        else 
        {
            orm::Insert("user_payment_accounts", array("p_account"=>$mail,"pm_id"=>1,"user_id"=>access::UserInfo()->user_id));
            echo json_encode(array("mtype"=>1,"msg"=>$mail));
        }
    }
} 

?>
