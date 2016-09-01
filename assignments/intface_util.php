<?php 
  require "lib/util.php";
  require 'config.php';
  
  if(isset($_GET['change_lang']))
  {
      $lang = $_GET['lang'];
      
      $lang_arr = util::translate_array($LANGUAGES);
      if(isset($lang_arr[$_GET['lang']])) 
      {
          $DEFAULT_LANGUAGE_FILE = $lang_arr[$_GET['lang']];      
          $_SESSION['lang_file'] = $DEFAULT_LANGUAGE_FILE;
      }
      
       
      util::redirect("".$_SESSION['home']);
      
      
  }
  
?>