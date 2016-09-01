<?php if(!isset($RUN)) { exit(); } ?>
<?php

    access::menu("local_users");
    access::has("view_local_users",2);

    require "extgrid.php";
    require "db/users_db.php";
    require "events/users_events.php";

    $hedaers = array("&nbsp;",LOGIN,  USER_NAME, USER_SURNAME, ADDED_DATE, USER_TYPE, EMAIL,"&nbsp;","&nbsp;","&nbsp;","&nbsp;");
    $columns = array("UserName"=>"text", "Name"=>"text","Surname"=>"Surname","added_date"=>"short date","type_name"=>"text","email"=>"text");
    
    $exp_hedaers = array(LOGIN,  USER_NAME, USER_SURNAME, ADDED_DATE, USER_TYPE, EMAIL);    

    $grd = new extgrid($hedaers,$columns, "index.php?module=local_users");
    $grd->sort_headers = array(LOGIN=>"UserName",USER_NAME=>"Name",USER_SURNAME=>"Surname", ADDED_DATE=>"added_date", USER_TYPE=>"user_type");
    $grd->row_info_table="users";
    $grd->exp_headers = $exp_hedaers;
    $grd->exp_columns = $columns;
    $grd->edit_link="index.php?module=add_edit_user";
    $grd->id_column="UserID";
    $grd->column_override=array("type_name"=>"user_type_override", "UserName"=>"login_override");
    $grd->auto_id=true;
    $grd->modify_system_rows=false;
    $user_quizzes = access::has("local_usr_dtls") ? DETAILS : "";
    $ip_rests  = access::has("local_ip_res") ? IP_RESTRCITIONS : "";
    $grd->id_links=array($ip_rests=>"?module=ip_res&type=1",$user_quizzes=>"?module=user_details");
    
    if(!access::has("delete_local_user")) $grd->delete_text = "";
    if(!access::has("edit_local_user")) $grd->edit_text = "";

    function login_override($row)
    {
        if(isset($_GET['expgrid'])) return $row['UserName'];
        $login = $row['UserName'];
        $user_photo_file = util::get_img_file($row['user_photo']);
        $href= "index.php?module=add_edit_user&id=".$row['UserID'];
       // $thumb = util::get_thumb($user_photo_file);
        $res = "<a href=\"$href\"   title=\"<img style='width:200px' src='user_photos/$user_photo_file' />\">$login</a>"; //data-toggle=\"tooltip\"
        //class="ttip_b" title="<b><i>salam</i></b>" 
      //  echo "user_photos/$user_photo_file";
        return $res;
    }
    function user_type_override($row)
    {
      //  global $USER_TYPE;
     //   return $USER_TYPE[$row['user_type']];
        return $row["role_name"];
    }
    
    function user_photo_override($row)
    {
        $user_photo_file = $row['user_photo'];
        $thumb = util::get_thumb($user_photo_file);
        $res = "<a href=\"user_photos/$user_photo_file\"  class=\"cbox_single thumbnail cboxElement\">";
        $res.="<img style='width:200px' src=\"user_photos/$thumb\" ></a>";
       // $res.="Photo</a>";
       // return "salam";
        return $res;
    }

    if($grd->IsClickedBtnDelete() && access::has("delete_local_user"))
    {
       $resultsd = orm::Select("users", array("user_photo"), au::arr_where(array("UserID"=>$grd->process_id)), "UserID");
       $rowd=db::fetch($resultsd);
       if($rowd['user_photo']!="")
       {
           $user_photo=$rowd['user_photo'];
           $arrold = explode(".", $user_photo);
           $extold = end($arrold);  
           @unlink("user_photos".DIRECTORY_SEPARATOR.$user_photo);
           @unlink("user_photos".DIRECTORY_SEPARATOR.$user_photo.".thumb.".$extold);
       }
       user_deleting($grd->process_id);
       orm::Delete("users", au::arr_where(array("UserID"=>$grd->process_id)));
       orm::Delete("user_payment_accounts", array("user_id"=>$grd->process_id));
       user_deleted($grd->process_id);
    }

    $grd->default_sort = "added_date desc";
    $query = users_db::GetUsersQuery("",au::get_where(true), $grd->GetSortQuery());    
  //  $grd->search= access::UserInfo()->access_type == 1 ? false :  true;
    $grd->search=true;
    $grd->DrowTable($query);
    $grid_html = $grd->table;

    $search_html = $grd->DrowSearch(array(LOGIN, USER_NAME, USER_SURNAME),array("UserName", "Name", "Surname"));

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
        return LOCAL_USERS;
    }

?>
