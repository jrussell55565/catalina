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
  include "lib/access.php";
  include "db/users_db.php";
  include "db/asg_db.php";  
  require "lib/logs.php";
  if(FACEBOOK_INTEGRATE=="yes") require 'facebook-php-sdk-master/src/facebook.php';
  include "lib/ip_util.php";
  include "lib/ldap_helper.php";
  include "lib/sec_check.php";  
  include "3d_party/mobile_detect/Mobile_Detect.php";
  
//  $res = ldap_helper::CheckAuthentication("user.37", "admin123");

  $RUN = 1;
   
  $user_full_name = ""; 
  if(FACEBOOK_INTEGRATE=="yes" )
  {
    $facebook = new Facebook(array(
          'appId'  => FACEBOOK_APP_ID,
          'secret' => FACEBOOK_SECRET,
          ));  

    //$user_full_name = get_facebook_name();
  }

 if(!isset($LANGUAGES)) { util::redirect("install/index.php") ; exit() ; } 

 $val = new validations("btnSend");
 $val->AddValidator("txtEmail", "email", EMAIL_VAL,"");

  $msg = "";
  $autorized =false;
  
  $_SESSION['login_attempts'] = isset($_SESSION['login_attempts']) ? intval($_SESSION['login_attempts']) : 0;

  if(isset($_POST['btnSubmit']))
  {   
    
      $txtLogin = db::esp(trim($_POST['txtLogin']),true);
      $txtPass = Local_Users_Password_Hash(trim($_POST['txtPass']));
      $password="";
      //$txtPassImp= Imported_Users_Password_Hash(trim($_POST['txtPass']));
      $results = access_db::GetModules($txtLogin, "", "", false);
      $has_result = sizeof($results);      
      
	  if(isset($_POST['hdnRes'])) $_SESSION['screen_width']=$_POST['hdnRes'];
	  
      $trusted = check_captcha();
      $ldap_user = false;
      if($has_result==0 && $trusted==true && LDAP_ENABLED=="yes")
      {          
       
          $ldap_res = ldap_helper::CheckAuthentication($txtLogin, trim($_POST['txtPass']));        
          if($ldap_res[0]=="0") $msg=LOGIN_INCORRECT."<br />".$ldap_res[1];
          else
          {                    
              users_db::AddAppUser("0", $ldap_res[1][LDAP_NAME_STR][0], $ldap_res[1][LDAP_SURNAME_STR][0], $ldap_res[1][LDAP_LOGIN_STR][0], $ldap_res[1][LDAP_MAIL_STR][0], 0, 4, 1, 1, 2);
              $results = access_db::GetModulesByAppUser($ldap_res[1][LDAP_MAIL_STR][0], $ldap_res[1][LDAP_MAIL_STR][0],'',false);  
              $has_result = sizeof($results); 
              $ldap_user = true;
          }
      }
      
      if($has_result!=0 && isset($LANGUAGES[$_POST['drpLang']]) && $trusted==true)
      {
          $row = $results[0];

          if($row['imported']=="0") $password = $txtPass ;
          else $password = Imported_Users_Password_Hash(trim($_POST['txtPass']), $row['password']);

          $ip_has_access = ip_util::ip_has_access($row['UserID']);
          if($ip_has_access==false)
          {
              $msg = IP_NO_ACCESS;
          }
          else if($password==$row['password'] || $ldap_user == true)
          {

            access::SetCredentials($results);
            
            if($ldap_user==true) asgDB::AcceptLDAPUser(access::UserInfo()->user_id, access::UserInfo()->branch_id);
               
            $_SESSION['lang_file']=$_POST['drpLang'];      
                       
            $home = "index.php?module=".$row["default_page"];            
      
            $_SESSION['home']  = $home;
            $_SESSION['is_mobile'] = util::isMobile();            
            
            unset($_SESSION['login_attempts']);
            unset($_SESSION['captcha_enabled']);
            
            logs::add_log(1, "");
            
            if(isset($_GET['u'])) $home = urldecode ($_GET['u']);                              
            
            util::redirect($home);
            exit();
          }
      }
      else if($trusted==false)
      {
          $msg = ENTER_NUMBERS_CRCT;
      }
      if($msg=="") $msg = LOGIN_INCORRECT;
      $_SESSION['login_attempts'] = intval($_SESSION['login_attempts'] ) + 1;
  }
  else if(FACEBOOK_INTEGRATE=="yes" && isset($_POST['btnPost']))
  {                        
        $user = $facebook->getUser();
        if ($user) {
          try {            
            $user_profile = $facebook->api('/me');     
          // echo sizeof($user_profile);
          // exit();           
            if(sizeof($user_profile)>0)
            {
                users_db::AddAppUser($user_profile['id'], $user_profile['first_name'], $user_profile['last_name'], isset($user_profile['username']) ? $user_profile['username'] : "", $user_profile['email'], 0, 3, 1, 1, 2);
                $results = access_db::GetModulesByAppUser($user_profile['email'], $user_profile['email'],'',false);              
                
                if(sizeof($results)>0 && $user_profile['email']!="")
                {
                    $row = $results[0];
                    
                    $ip_has_access = ip_util::ip_has_access($row['UserID']);
                    if($ip_has_access==false)
                    {
                        $msg = IP_NO_ACCESS;
                    }
                    else
                    {
                        access::SetCredentials($results);

                        asgDB::AcceptFacebookUser(access::UserInfo()->user_id, access::UserInfo()->branch_id);

                        $_SESSION['lang_file']=$DEFAULT_LANGUAGE_FILE; 

                        $home = "index.php?module=".$row["default_page"];            

                        $_SESSION['home']  = $home;
                        $_SESSION['is_mobile'] = util::isMobile();						

                        logs::add_log(1, "");
                        
                        if(isset($_GET['u'])) $home = urldecode ($_GET['u']);                              

                        util::redirect($home);
                        exit();
                    }
                }
                else 
                {
                    $msg = NO_ACCESS;
                }
            }
          } catch (FacebookApiException $e) {
           // echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
            $user = null;
          }
        }
  }

  $language_options = webcontrols::BuildOptions($LANGUAGES, $DEFAULT_LANGUAGE_FILE);

  $menus = orm::Select("pages", array("page_name","id"), array(), "priority");

  if(REGISTRATION_ENABLED=="yes") $register_display = "";
  else $register_display="none";
  
  function get_facebook_name()
  {
    global $facebook;
    $user = $facebook->getUser();
    $user_full_name="";
        
    if ($user)
    {
        try
        {
            $user_profile = $facebook->api('/me'); 
            $user_full_name = $user_profile["first_name"]." ".$user_profile["last_name"];
        } catch (Exception $ex) {
            
        }

    }
    return $user_full_name;
  }
  
  function check_captcha()
  {
       $trusted = false;
       if($_SESSION['captcha_enabled']=="1") 
       {
           if($_SESSION['_RANDOM']==$_POST['txtCaptcha']) $trusted =true;
       }
       else $trusted = true;
       
       return $trusted;
  }
  
  $captcha_display= "none";
  $_SESSION['captcha_enabled']= "0";
  if(intval($_SESSION['login_attempts'])>2)
  {
      $captcha_display="";
      $_SESSION['captcha_enabled']="1";
  }
  
  $branch_url = "";
  if(isset($_GET['b']))
  {
      $bid = util::GetInt($_GET['b']);
      $branch_url = "?b=$bid";
  }

  include "login_".SITE_TEMPLATE."_tmp.php";


?>
