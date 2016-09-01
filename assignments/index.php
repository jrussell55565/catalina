<?php
 
  require "lib/util.php";  
  require 'config.php';
  require 'db/mysql2.php';
  require 'db/access_db.php';  
  require "lib/access.php";
  require "db/orm.php";
  require "lib/validations.php";
  require "lib/webcontrols.php";
  include "lib/ip_util.php";
  include "lib/sec_check.php";
  require "lib/iutil.php";
    
 //$mobile=false;

  if(!isset($LANGUAGES)) { util::redirect("install/index.php") ; exit() ; }

  if(USE_MATH=="yes") include("mathpublisher/mathpublisher.php") ;


  $RUN = 1;
  $print = false;

   //@session_start();

  
  $module_name = GetModuleName();
  $expand =false;
  $autorized = false;
  $current_module = array();
  $report_display = "none";

    if(access::UserInfo()->imported==2) $modules = access_db::GetModulesByAppUser(access::UserInfo()->email, access::UserInfo()->email,'',false);              
    else $modules = access_db::GetModules(access::UserInfo()->login, access::UserInfo()->password,access::UserInfo()->imp_password,true);
    $has_result = sizeof($modules);  

    if($has_result==0)
    {         
            util::redirect("login.php");
            exit;
    }

    if($has_result!=0) {
            $_SESSION['KCFINDER'] = array();
            $_SESSION['KCFINDER']['disabled'] = false;
    }
    $autorized = true;
    $expand  = true;
    ExpandModules($modules);
 
  $mobile = $_SESSION['is_mobile'] =="false" ? false : true;  
  
  $home=$_SESSION['home'];
  
  function ExpandModules($modules)
  {
      global $child_modules,$main_modules,$current_module,$report_display;      
      for($i=0;$i<sizeof($modules);$i++)
      {	            
          $row = $modules[$i];
          
          if($row['parent_id']=="0")
          {		  
              $main_modules[] = $row;
          }
          else
          {              
              if($row['file_name']==GetModuleName())
              {                        
                  $current_module=$row;
                  if($row['enable_reports']==1) $report_display="";
              }              
              if($row['is_visible']==1)
              $child_modules[$row['parent_id']][]=$row;
          }
      }
  }   

//  $menus = orm::Select("pages", array("page_name","id"), array(), "priority");

  ShowModule();

  function ShowModule()
  {
        global $module_name,$module_t_name,$Util;

       // $module_name= GetModuleName() ;

        if(!file_exists("modules/$module_name".".php") || $module_name=="" || strpos($module_name,"../")!=0 &&  preg_match('/^[A-Za-z]+[A-Za-z_]*$/', $module_name))
            $module_name="default";               

        $module_t_name=$module_name."_tmp";

  }   
  
  
  //$pages = db::GetResultsAsArray(orm::GetSelectQuery("pages", array(), array(), "priority")); 
  $pages = access::UserInfo()->page_list;
 
  $html = "";
  AddTopMenu("0");
//  echo $html;
//  exit();   
  function AddTopMenu($parent_id)
  {
      global $pages,$html;
      for($i=0;$i<count($pages);$i++)
      {                
          if($pages[$i]['parent_id']==$parent_id)
          {                      
              $child_items = GetChildMenu($pages[$i]['id']);
              $dropdown_icon = "";
              $href = "#";
              $class="";       
              $target="";
              if($child_items!="") 
              {
                  $dropdown_icon="<b class=\"caret\"></b>";      
                  $class = "data-toggle=\"dropdown\" class=\"dropdown-toggle\" ";
              }
              else 
              {
                  $href= "?module=show_page&id=".$pages[$i]['id'];                  
                  if($pages[$i]['page_type']=="2")
                  {
                      $href=$pages[$i]['link_url'];
                      $target="target=\"_blank\"";
                  }
              }
                           
              $page_name=$pages[$i]['page_name'];            
              $html.="<li class=\"dropdown\"><a $target $class href=\"$href\"><i class=\"icon-book icon-white\"></i> $page_name $dropdown_icon</a>\n";                          
              $html.=$child_items;
              $html.="</li>";
          }
      }     
  }
  
  function GetChildMenu($parent_id)
  {
      global $pages;
      $child_html = "";
      for($i=0;$i<count($pages);$i++)
      {                
          if($pages[$i]['parent_id']==$parent_id)
          {              
              $page_name=$pages[$i]['page_name'];
              $href= "?module=show_page&id=".$pages[$i]['id'];
              $child_html.="<li><a href=\"$href\">$page_name</a></li>";
          }
      }     
      if($child_html!="")
      {
          $child_html="<ul class=\"dropdown-menu\">$child_html</ul>\n";
      }
      return $child_html;
  }
  
  $fullname = access::UserInfo()->name." ".access::UserInfo()->surname;
  if(SHOW_BRANCH_INFO=="yes") $fullname.=" ( ".access::UserInfo()->branch_name." )";
  $currentlang = $_SESSION['lang_file'];
  $currentlang = $LANGUAGES[$currentlang];
  $flag = $FLAGS[$currentlang];
  $languages_html = "<a href=\"#\" class=\"dropdown-toggle nav_condensed\" data-toggle=\"dropdown\"><i class=\"$flag\"></i> <b class=\"caret\"></b></a>";
  $languages_html.="<ul class=\"dropdown-menu\">";
  $languages_html.=GetLanguageHtml();
  $languages_html.="</ul>";
  
  $mid=0;
  if(isset($_GET['mid']))
  {
      $mid = $_GET['mid'];
      $_SESSION['mid'] = $mid;
  }
  else if(isset($_SESSION['mid'])) 
  {
      $mid = $_SESSION['mid'];
  }

 // echo $LANGUAGES[$currentlang];
  function GetLanguageHtml()
  {
      global $languages_html,$LANGUAGES,$FLAGS;
     // $lang_arr = util::translate_array($LANGUAGES);      
      foreach($LANGUAGES as $key=>$value)
      {
        //  $currentlang=$arr[$key];
         // echo $key;
          $flag = $FLAGS[$value];
          $lnghref ="intface_util.php?change_lang=true&lang=$value";
          $languages_html.="<li><a href=\"$lnghref\"><i class=\"$flag\"></i> $value</a></li>";
      }
  }

  function GetModuleName()
  {
	return isset($_GET["module"]) ? $_GET["module"] : "default" ;
  }

  $hide_mobile = $mobile == true ? "none" : "";
  
  include "modules/".$module_name.".php";
  
  $mobile_modules = $mobile == true ? ".mobile" : "";  
  $module_template_file = $mobile == true  && file_exists("modules_mobile/$module_name"."_tmp.php") ? "modules_mobile/$module_name"."_tmp.php" : "modules/$module_name"."_tmp.php";

  
  if(!isset($_POST["ajax"]) && !isset($_GET["expgrid"]) && !isset($_POST["content"]))
  {
        $print_tmp = $print == true ? "_print" : "";
        $queries = debug::GetSQLs();		
        $mobile_main = $mobile == true ? ".mobile" : "";
        $main_template_file = "index_".SITE_TEMPLATE."_tmp".$mobile_main.$print_tmp.".php";      
	
        include $main_template_file ;
  }  
  
  if(isset($_POST['content']))
  {
      include "modules/".$module_name."_tmp.php";
  }


?>
