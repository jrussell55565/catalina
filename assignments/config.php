<?php

  /*  This code has been developed by Els . Els11@yandex.ru    
		In God I trust 				*/

  define("SQL_IP", "localhost"); // ip address of mysql database 
  define("SQL_USER", "catalina");  // username for connecting to mysql
  define("SQL_PWD","vawmIss6"); // password for connecting to mysql
  define("SQL_DATABASE","assignments"); // database where you have executed sql scripts

  define("WEB_SITE_URL","http://dispatch.catalinacartage.com/assignments/"); // the url where you installed this script . do not delete last slash  
  define("USE_MATH", "yes"); // yes , no . if you want to use math symbols , you have to enable it
  define("DEBUG_SQL","no"); // enable it , if you want to view sql queries .
  define("PAGING","30");  // paging for all grids

  define("MAIL_FROM", "drivers@catalinacartage.com"); //  Your mail address 
  define("MAIL_CHARSET", "UTF-8"); // Charset of your mail 
  define("MAIL_USE_SMTP", "yes");	// Authentication via smtp server
  define("MAIL_SERVER", "vip.acghosting.com"); // your mail server , only if smtp enabled
  define("MAIL_USER_NAME", "fsirelay@acghosting.com"); // your email address , only if smtp enabled
  define("MAIL_PASSWORD", "FS!relay"); // password of your email address ,only if smtp enabled
  define("MAIL_PORT", "25"); // port of your smtp server, only if smtp enabled

  define("REGISTRATION_ENABLED", "yes"); // yes ,no - enables self registration for new users
  define("ALLOW_AVATAR_CHANGE", "yes"); // allows user to change their avatars
  define("SITE_TEMPLATE", "metro");  // only metro , cannot be changed
  define("ENABLE_CALCULATOR", "no"); // no - this template doesn't have calculator
  define("DEFAULT_COUNTRY","241"); // default country id for new registered users . id from "countries" table
  define("SHOW_BRANCH_INFO","yes");	// shows branch info new the user name and surname
  define("POINT_CALCULATION", "COMPLETE"); // available variables - COMPLETE / PARTLY
  define("ANSWER_MODE","SIMPLE"); // available variables - EXTENDED / SIMPLE
  define("IP_CHECK_ENABLED","yes");
  define("LOG_DATA","yes");
  define("LOG_QUESTION_VIEW","no");
  
  define("FACEBOOK_INTEGRATE","no"); // "no" = all facebook features will be disabled . "yes" = fb functions will be enabled
  define("FACEBOOK_APP_ID",""); // id of your facebook application
  define("FACEBOOK_SECRET",""); // secret of your facebook application
  define("FACEBOOK_PROFILE_URL", "https://graph.facebook.com/[USERID]/picture?type=large&width=[WIDTH]"); // url for reading facebook profile image
  define("FACEBOOK_ACCESS","email,publish_stream"); // access for facebook
  
  
  define("LDAP_ENABLED","no"); // no/yes  - enable and disable LDAP login
  define("LDAP_SERVER","localhost"); // LDAP server ip address 
  define("LDAP_PORT","389"); // port for connecting to LDAP
  define("LDAP_STRING","uid=[USER_NAME],ou=People,dc=example,dc=com");  // string for logining in to LDAP
  define("LDAP_PROTOCOL_VERSION","3"); // LDAP protocol version
  define("LDAP_FILTER_STRING","(uid=[USER_NAME])"); // Searching for user details in LDAP
  define("LDAP_NAME_STR","givenname"); // the name of user in ldap
  define("LDAP_SURNAME_STR","sn"); // the surname of user in ldap
  define("LDAP_LOGIN_STR","uid"); // the login of user in ldap
  define("LDAP_MAIL_STR","mail"); // the mail of user in ldap

  define("PAYPAL_ENABLED","yes"); // no/yes enable disable integration with paypal
  define("PAYPAL_SELLER_EMAIL",""); // email of your business paypal account 
  define("PAYPAL_NOTIFY_SUCCESS_PAYMENT","yes"); // yes/no receive e-mails on success payment
  define("PAYPAL_NOTIFY_FAIL_PAYMENT","yes"); // yes/no receive e-mails on fail payment
  define("PAYPAL_CURRENCY","USD"); // currency of your paypal account 
  define("PAYPAL_DATA_NAME","Load balance"); // Data name during the payment
  define("PAYPAL_USE_SANDBOX","no"); // yes/no .  yes = sandbox , no = production
  
  define("MAX_COURSE","6");
  define("WORD_COUNT",7);
  
  define("ORDER_NUMBER","105998540055");

  $SYSTEM_NAME="PHP Web Quiz"; // the name of your system that will be displayed at login page 
  $PAGE_TITLE = "PHP Web Quiz"; // title of your html page
  
  $allowed_avatar_formats=array("jpg","gif","jpeg","png"); // allowed avatar file formats that can be uploaded
  $max_avatar_size="500"; // kbs  
  
  // method for hashing password of local users
  function Local_Users_Password_Hash($entered_password)
  {
      return md5($entered_password);
  }
  
  // function that you need to modify if you want to import users from another application . Read help for more info
  function Imported_Users_Password_Hash($entered_password,$password_from_db)
  {
      return $entered_password;
  }

  @session_start();

  // LANGUAGE CONFIGURATION
  $LANGUAGES = array("english.php"=>"English","russian.php"=>"Russian");
  $DEFAULT_LANGUAGE_FILE="english.php";
  
  $FLAGS['English'] = "flag-gb";
  $FLAGS['Russian'] = "flag-ru";
  
  $HELPDESK_FILE_FORMATS = array("jpg","jpeg","gif","pdf","txt","xls","xlsx","doc","docx","mpeg","mpg","mp4","mp3","tif","tiff","png");

  //----------------------------do not touch the code below--------------------------------
  
  if(isset($_SESSION['lang_file']))
  {
      $DEFAULT_LANGUAGE_FILE = $_SESSION['lang_file'];
  }
  
  if(isset($_GET['lang']))
  {
      $lang_arr = util::translate_array($LANGUAGES);
      if(isset($lang_arr[$_GET['lang']])) $DEFAULT_LANGUAGE_FILE = $lang_arr[$_GET['lang']];
  }

  require "lang/".$DEFAULT_LANGUAGE_FILE;

  ini_set ('magic_quotes_gpc', 0);
  ini_set ('magic_quotes_runtime', 0);
  ini_set ('magic_quotes_sybase', 0);
  ini_set('session.bug_compat_42',0);
  ini_set('session.bug_compat_warn',0);  
  
  $avatar_width="200"; // px
  
  $fb_files = FACEBOOK_INTEGRATE=="yes" ? "xmlns:fb=\"http://www.facebook.com/2008/fbml\"" : "";
  $fb_display = FACEBOOK_INTEGRATE=="yes" ? "" : "none";
  $ldap_display = LDAP_ENABLED == "yes" ? "" : "none";
  
  define("LC","0");
  
  define("ASG_ENABLE_HEADER_OPTIONS","yes");
  define("RATINGS_BOX_MODE","0");
  
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  
  //----------------------------------------------------------------------
  
?>
