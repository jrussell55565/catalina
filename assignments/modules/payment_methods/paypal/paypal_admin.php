<?php if(!isset($RUN)) { exit(); } ?>
<?php 

if(!isset($_SESSION['app_admin'])) exit();

check_access($utype);

$id = util::GetID();

$p_account = db::exec_sql_single_value(orm::GetSelectQuery("user_payment_accounts", array("p_account"), array("user_id"=>$id), ""), "p_account");       

if(isset($_POST['ajax']))
{    
    if(isset($_POST['savemail']))
    {
        //, "user_id"=>access::UserInfo()->user_id
        $mail = db::clear(trim($_POST['email']));
        $account_res = db::exec_sql(payments_db::GetPayorDetailsByEmail($mail));      
        
        if(db::num_rows($account_res)>0)
        {
            $row = db::fetch($account_res);
            echo json_encode(array("mtype"=>1,"msg"=>ACCOUNT_ALREADY_EXIST_TO." - ".$row['UserID']." - ".$row['FullName']));
        }
        else 
        {
            orm::Delete("user_payment_accounts", array("user_id"=>$id));
            orm::Insert("user_payment_accounts", array("p_account"=>$mail,"pm_id"=>1,"user_id"=>$id));
            echo json_encode(array("mtype"=>1,"msg"=>SAVED));
        }
    }
} 

?>
