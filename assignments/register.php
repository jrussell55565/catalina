<?php
  $RUN = 1;
  require "lib/util.php";
  require 'config.php';
  require 'db/mysql2.php';
  require 'db/access_db.php';  
  require "lib/access.php";
  require "db/orm.php";
  require "lib/validations.php";
  require "lib/webcontrols.php";
  require "db/asg_db.php";
  require "events/users_events.php";

  if(!isset($LANGUAGES)) util::redirect("install/index.php");
  
   if(REGISTRATION_ENABLED!="yes") exit();
  
  include "lib/libmail.php";
  include "lib/cmail.php";

  $val = new validations("btnRegister");
  $val->AddValidator("txtName", "empty", R_NAME_VAL,"");
  $val->AddValidator("txtSurname", "empty", R_SURNAME_VAL,"");
  $val->AddValidator("txtLogin", "an", R_LOGIN_VAL , "");
  $val->AddValidator("txtPass", "an", R_PASSWORD_VAL , "");
  $val->AddValidator("txtEmail", "email", R_EMAIL_VAL , "");
  $val->AddValidator("drpCountries", "empty", COUNTRY_VAL , "");
  
  
  $countries_res = orm::Select("countries", array(), array(), "country_name");
  $country_options = webcontrols::GetOptions($countries_res, "id", "country_name", DEFAULT_COUNTRY);
  
  $step1_display="";
  $step2_display = "none";
  
  if(isset($_GET['step']))
  {
      $step1_display="none";          
      $step2_display = "";
      if($_GET['step']=="2")
      {
          $msg = R_EMAIL_SENT;        
      }
      else if($_GET['step']=="3")
      {
              if(!isset($_GET['g']))
              {
                      util::redirect("login.php");
              }

              $guid = trim($_GET['g']);

              $results = orm::Select("users",array(), array("random_str"=>$guid), "");
              $count = db::num_rows($results);
              if($count >0 ) 
              {
                      $row = db::fetch($results);
                      if($row['approved']=="0")
                      {
                              user_approving($guid);
                              orm::Update("users", array("approved"=>1), array("random_str"=>$guid));
                              asgDB::AcceptNewUser($row["UserID"],$row["branch_id"]);
                              user_approved($guid);
                              $msg = R_REG_APPROVED." ".R_GO_TO." <a href='login.php'>".R_LOGIN_PAGE."</a>";
                      } else $msg = R_REG_ALREADY_APPROVED." ".R_GO_TO." <a href='login.php'>".R_LOGIN_PAGE."</a>";
              }
              else $msg = R_URL_WRONG;
      }
  }
  

if(isset($_GET['b']))
{
    $brn_id = util::GetKeyID("b", "register.php");
    $brn_res = orm::Select("branches", array(), array("id"=>$brn_id,"self_reg"=>1), "");
    if(db::num_rows($brn_res)<1)
    {
        die(CANNOT_REG_BRN);
    }
}
  
if(isset($_POST["txtName"]) && $val->IsValid())
{
        if(get_login_count($_POST['txtLogin'])>0 || get_mail_count($_POST['txtEmail'])>0 || get_captcha($_POST['txtCaptcha'])==false) exit() ;
        
        if(isset($_GET['b'])) $branch_id = $brn_id;
        else $branch_id = array("(SELECT id FROM branches WHERE system_row=1 LIMIT 0,1)",true);
                
	$guid = md5(util::Guid());
        user_self_registering($arr_insert);
        $arr_insert = array("UserName"=>trim($_POST['txtLogin']),
						"Password"=>Local_Users_Password_Hash(trim($_POST['txtPass'])),					
						"Name"=>trim($_POST['txtName']),
						"Surname"=>trim($_POST['txtSurname']),
						"added_date"=>util::Now(),
						"user_type"=>array("(SELECT id FROM roles WHERE system_row=2 LIMIT 0,1)",true),
						"email"=>trim($_POST['txtEmail']),
						"address"=>trim($_POST['txtAddr']),
						"phone"=>trim($_POST['txtPhone']),
                                                "country_id"=>trim($_POST['drpCountries']),
						"approved"=>0,
						"disabled"=>0,
						"random_str"=>$guid,
                                                "self_registered"=>"0",
                                                "group_id"=>array("(SELECT id FROM user_groups WHERE is_default=1 LIMIT 0,1)",true),
                                                "branch_id"=>$branch_id
					);	
        $last_id = db::exec_insert(orm::GetInsertQuery("users" , $arr_insert));
        user_self_registered($last_id,$arr_insert,$guid);
	$url = WEB_SITE_URL."register.php?step=3&g=".$guid;	

	$results = orm::Select("users", array(),array("UserName"=>$_POST['txtLogin']), "");
	$row = db::fetch($results);
	$cmail = new cmail("register_user",$row);

	$m= new Mail; 
	$m->From(MAIL_FROM ); 
	$m->To( trim($_POST['txtEmail']) );
	$m->Subject( $cmail->subject );
	$m->Body( str_replace("[url]", $url , $cmail->body) );    	
	$m->Priority(3) ;    
	//$m->Attach( "asd.gif","", "image/gif" ) ;
	
	if(MAIL_USE_SMTP=="yes")
	{
		$m->smtp_on(MAIL_SERVER, MAIL_USER_NAME, MAIL_PASSWORD ) ;    
	}
	$m->Send(); 


	util::redirect("register.php?step=2");
}

 $branch_url = "";
  if(isset($_GET['b']))
  {
      $bid = util::GetInt($_GET['b']);
      $branch_url = "?b=$bid";
  }

if(isset($_POST["ajax"]))
{
         $count=get_login_count(trim($_POST["login_to_check"]));
	 $msg = 0;
         if($count > 0)
	 {
		$msg= LOGIN_ALREADY_EXISTS ;
         }

 	 $count = get_mail_count(trim($_POST["email"]));
         if($count > 0)
	 {
		$msg= EMAIL_ALREADY_EXISTS ;
         }
         
         $cap = get_captcha(trim($_POST["captcha"]));
         if($cap == false)
	 {
		$msg= ENTER_NUMBERS_CRCT ;
         }

	 echo $msg;
}
else
{  
  include "register_tmp.php";
}

function get_login_count($login)
{
    $results = orm::Select("users", array(), array("UserName"=>$login) , "");
    $count = db::num_rows($results);
    return $count;
}

function get_mail_count($mail)
{
    $results = orm::Select("users", array(), array("email"=>$mail) , "");
    $count = db::num_rows($results);
    return $count;
}

function get_captcha($captcha)
{
    if(!isset($_SESSION['_RANDOM'])) return false;
    
    if($_SESSION['_RANDOM']==$captcha) return true;
    else return false;
}

?>