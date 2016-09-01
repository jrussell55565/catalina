<?php

 require "config.php";
 require "lib/util.php"; 
 require "db/mysql2.php";
 require "db/orm.php"; 
 if(FACEBOOK_INTEGRATE=="yes") require 'facebook-php-sdk-master/src/facebook.php';
 require "lib/access.php";
 require "lib/logs.php";

 if(access::check_autorize(false)) logs::add_log(2, ""); 

 access::Clear();
 unset($_SESSION['issue_qstid']);
 
 foreach($_SESSION as $key=>$value)
 {
     unset($_SESSION[$key]);
 }
 
 util::redirect("login.php");

/*

if(access::UserInfo()->imported == 2)
{
  access::Clear();
 
  
   $facebook = new Facebook(array(
  'appId'  => FACEBOOK_APP_ID,
  'secret' => FACEBOOK_SECRET,
  ));
  
    $user = $facebook->getUser();
    $access_token = $facebook->getAccessToken();
    
    $logoutUrl=$facebook->getLogoutUrl(array( 'next' =>WEB_SITE_URL.'login.php','access_token'=>$access_token));        
    
    util::redirect("$logoutUrl");
}
else
{
    access::Clear();
    util::redirect("login.php");
}    

    */
    
?>

