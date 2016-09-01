<?php 

  require "lib/util.php";
  require 'config.php';
  require 'db/mysql2.php';  
  require "db/orm.php";
  require "db/access_db.php";
  require "lib/validations.php";
  require "lib/webcontrols.php";
  include "lib/libmail.php";
  include "lib/cmail.php";

 if(REGISTRATION_ENABLED!="yes") exit();


$val = new validations("btnSend");
$val->AddValidator("txtEmail", "email", R_EMAIL_VAL,"");

if(isset($_POST["ajax"]))
{
   	 $random_pass=rand(10000, 90000);
	 $random_pass_hash = Local_Users_Password_Hash($random_pass);
         $results = orm::Select("users", array(), array("email"=>trim($_POST["email_for_restoring"])) , "");
         $count = db::num_rows($results);
	 if($count < 1)
	 {
		echo EMAIL_NOT_EXISTS;
	 }
	 else
	 {
		orm::Update("users", array ("password"=>$random_pass_hash),array("email"=>trim($_POST["email_for_restoring"])));

		$row = db::fetch($results);
		$cmail = new cmail("forgot_password", $row);	
		$body =str_replace("[url]", WEB_SITE_URL , $cmail->body);
		$body =str_replace("[random_password]", $random_pass, $body);
		$m= new Mail; 
		$m->From(MAIL_FROM ); 
		$m->To( trim($_POST["email_for_restoring"]) );
		$m->Subject( $cmail->subject );
		$m->Body($body);    	 	
		$m->Priority(3) ;   	
		if(MAIL_USE_SMTP=="yes")
		{
			$m->smtp_on(MAIL_SERVER, MAIL_USER_NAME, MAIL_PASSWORD ) ;    
		}
		$m->Send(); 				
	 	echo PASSWORD_RESETED;
	 }

}

function desc_func () { return "Restoring password"; }

?>
