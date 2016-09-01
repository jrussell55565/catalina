<?php if(!isset($RUN)) { exit(); } ?>
<?php 

access::menu("my_balance");

require "lib/fb.php";

$user_id = access::UserInfo()->user_id;
$user_photo = access::UserInfo()->user_photo;
$filename = "";
$thumb = "";


$name = access::UserInfo()->name;
$surname = access::UserInfo()->surname;
$email = access::UserInfo()->email;
$login = access::UserInfo()->login;
$branch_name = access::UserInfo()->branch_name;

$display_update = "";
if(isset($_GET['check']))
{
    $display_update = "none";
}

if(!isset($_SESSION['old_balance'])) $_SESSION['old_balance'] = access::UserInfo()->balance;

if(isset($_POST['ajax']))
{
    if(isset($_POST['check_b']))
    {        
        access::UserInfo()->UpdateBalance();        
        if((float)$_SESSION['old_balance']!=(float)access::UserInfo()->balance)
        {
            $_SESSION['old_balance'] = access::UserInfo()->balance;
            echo json_encode(array("mtype"=>1,"balance"=>access::UserInfo()->balance));            
        }
        else { echo json_encode(array("mtype"=>0,"balance"=>access::UserInfo()->balance)); }
            
    }
    elseif(isset($_POST['update_b']))
    {
        access::UserInfo()->UpdateBalance();    
        $_SESSION['old_balance'] = access::UserInfo()->balance;
        echo json_encode(array("mtype"=>1,"balance"=>access::UserInfo()->balance));
    }
}

if(access::UserInfo()->app_id==3) $img = fb::get_profile_photo (access::UserInfo()->login, 200, 200);
else $img = util::get_img($user_photo,false,'user_photos',170);

$payment_methods = db::GetResultsAsArray(orm::GetSelectQuery("payment_methods", array(), array(), "id"));

 for($i=0;$i<count($payment_methods);$i++) { 
     include "payment_methods/".$payment_methods[$i]['short_name']."/".$payment_methods[$i]['page_name'].".php";
 }
function desc_func() { return MY_PROFILE;}

?>