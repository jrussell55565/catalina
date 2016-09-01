<?php if(!isset($RUN)) { exit(); } ?>
<?php 

if(!isset($_GET['id'])) access::menu("payment_history");
else check_access(true);

require "extgrid.php";
require "db/payments_db.php";

$hedaers = array("&nbsp;",TRAN_ID,  PAYER_EMAIL, PAYMENT_AMOUNT, CURRENCY, PAYMENT_SOURCE, PAYMENT_TYPE);
$columns = array("txn_id"=>"text", "payer_email"=>"text","mc_gross"=>"Surname","currency"=>"text","payment_type"=>"text","dbt_crd"=>"text");

$exp_hedaers = array(TRAN_ID,  PAYER_EMAIL, PAYMENT_AMOUNT, CURRENCY, PAYMENT_SOURCE, PAYMENT_TYPE);  

$grd = new extgrid($hedaers,$columns, "index.php?module=payment_history");
$grd->sort_headers = array(TRAN_ID=>"txn_id",  PAYER_EMAIL=>"payer_email", PAYMENT_AMOUNT=>"mc_gross", CURRENCY=>"currency", PAYMENT_SOURCE=>"payment_type", PAYMENT_TYPE=>"dbt_crd");
$grd->row_info_table="payment_orders";
$grd->exp_headers = $exp_hedaers;
$grd->exp_columns = $columns;
$grd->edit=false;
$grd->delete=false;
$grd->id_column="UserID";
$grd->column_override=array("dbt_crd"=>"dbt_crd_override", "mc_gross"=>"mc_gross_override","payment_type"=>"payment_type_override");
$grd->auto_id=true;
$grd->id_column="order_id";

$user_id = access::UserInfo()->user_id;
if(isset($_GET['id'])) 
{
    if(check_access!=true) util::redirect ("login.php");
    $user_id = util::GetKeyID("id");
}

$grd->default_sort = "order_id desc";
$query = payments_db::GetPaymentsHistory(" WHERE user_id = $user_id ",$grd->GetSortQuery());

$grd->DrowTable($query);
$grid_html = $grd->table;

function dbt_crd_override($row)
{
    return $row['dbt_crd'] == "1" ? webcontrols::AddColor(DEBIT, "red") : webcontrols::AddColor(CREDIT, "blue");
}

function mc_gross_override($row)
{
    return $row['dbt_crd'] == "1" ? "-".webcontrols::AddColor($row['mc_gross'], "red") : "+".webcontrols::AddColor($row['mc_gross'], "blue");
}

function payment_type_override($row)
{
    $source ="";
    if($row['payment_type']=="1") return $row['display_name'];
    else if($row['payment_type']=="2") return $row['assignment_name'];
    else if($row['payment_type']=="3") return ADDED_BY_ADMIN;
}

if(isset($_POST["ajax"]))
{
      if(isset($_POST['info_id'])) echo $grd->LoadUserInfo ($_POST['info_id']) ;
     else echo $grid_html;
}

if(isset($_GET["expgrid"]))
{
    $grd->Export();
}

function desc_func()
{
    return PAYMENT_HISTORY;
}

function check_access($redirect = false)
{
    $access = true ; 
    
    if(isset($_GET['id']))
    {
        $access = false;
        if(access::has("local_usr_dtls") || access::has("imp_usr_dtls") || access::has("fb_usr_dtls") || access::has("app_usr_dtls")) $access = true ;
    }
    
    if($redirect==true && $access==false) util::redirect("login.php");
    
    return $access;
        
}

?>