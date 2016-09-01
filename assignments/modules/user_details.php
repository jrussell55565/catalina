<?php if(!isset($RUN)) { exit(); } ?>
<?php 


access::menu("my_balance");

require "db/users_db.php";
require "db/payments_db.php";
//require "lib/fb.php";

$id = util::GetID();

access::set_app_admin();

access::set_app_admin();

$results = db::exec_sql(users_db::GetUserDetails($id));

if(db::num_rows($results)==0) {
    echo NO_RECORDS;
    exit();
}
$row = db::fetch($results);

$utype = $row['user_type'];

check_access($utype);

$user_photo = $row['user_photo'];


$name = $row['NAME'];
$surname = $row['Surname'];
$email = $row['email'];
$login = $row['UserName'];
$branch_name = $row['branch_name'];
$branch_id = $row['branch_id'];

if(isset($_POST['ajax']))
{
    if(isset($_POST['change_balance']))
    {
        $dbt_crd = $_POST['change_type'] == "1" ? "1" : "2";
        $bal = floatval($_POST['bal']);
        if($bal>0) orm::Insert("payment_orders", array("user_id"=>$id, "branch_id"=>$branch_id,"dbt_crd"=>$dbt_crd, "txn_id"=>payments_db::GetTxID(),"payer_email"=>$email, "mc_gross"=>$bal, "currency"=>PAYPAL_CURRENCY, "payment_type"=>"3", "payment_type_id"=>"1", "inserted_by"=>access::UserInfo()->user_id, "inserted_date"=>util::Now()));
    }
}

$current_balance = access_db::GetBalance($id)[0]['balance'];

if(isset($_POST['ajax']) && isset($_POST['change_balance'])) echo $current_balance;


$img = util::get_img($user_photo,false,'user_photos',170);

$payment_methods = db::GetResultsAsArray(orm::GetSelectQuery("payment_methods", array(), array(), "id"));

 for($i=0;$i<count($payment_methods);$i++) { 
     include "payment_methods/".$payment_methods[$i]['short_name']."/".$payment_methods[$i]['page_name']."_admin.php";
 }
function desc_func() { return USER_PROFILE;}

function check_access($utype)
{    
    if($utype=="1"){ 
        access::menu("local_users");
        access::has("local_usr_dtls",2);
    }
    else if($utype=="2") { 
        access::menu("imported_users");    
        access::has("imp_usr_dtls",2);
    }
    else if($utype=="3") {
        access::menu("fb_users"); 
        access::has("fb_usr_dtls",2);    
    }
    else if($utype=="4") {
        access::menu("ldap_users"); 
        access::has("app_usr_dtls",2);    
    }
    else util::redirect ("login.php");
}

$asg_display = "none";
$ph_display="none";
if(access::has("view_local_user_quizzes") || access::has("view_imp_user_quizzes") || access::has("view_fb_user_quizzes") || access::has("view_ldap_user_quizzes")) $asg_display="";
if(access::has("local_usr_dtls") || access::has("imp_usr_dtls") || access::has("fb_usr_dtls") || access::has("app_usr_dtls")) $ph_display = "";

?>