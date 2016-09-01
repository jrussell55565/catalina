<?php if(!isset($RUN)) { exit(); } ?>
<?php

    require "db/asg_db.php";
    require "db/users_db.php";
    require "events/users_events.php";

    access::menu("local_users");
    access::has("view_local_users",2);

    $val = new validations("btnSave");
    $val->AddValidator("txtLogin", "empty", LOGIN_VAL,"");
    $val->AddValidator("txtPassword", "empty", PASSWORD_VAL,"");
    $val->AddValidator("drpUserType", "notequal", USER_TYPE_VAL , "-1");
    $val->AddValidator("drpCountries", "notequal", COUNTRY_VAL , "-1");
    $val->AddValidator("drpBranches", "notequal", BRANCH_VAL , "-1");
    $val->AddValidator("txtEmail", "email", EMAIL_VAL, "", "1");

    $selected="-1";
    $selected_group = "-1";
    $selected_country = DEFAULT_COUNTRY;
    $selected_branch = "-1";
    $pswlbl_display="none";
    $psw_display="";
    $login_disabled="";
    $mode="add";
    $chkApproved = "checked";
    $buttons_display= "";
    
    $user_photo_file = "";
    $photo_thumb = "";
    $row_users = 0;
    $id = "-1";
    
    if(isset($_GET["id"]))
    {
        access::has("edit_local_user",2);
        $pswlbl_display="";
        $psw_display="none";
        $login_disabled="readonly";
        $mode="edit";
        $id = util::GetID("?module=local_users");
        $rs_users=orm::Select("users", array(), au::arr_where(array("UserID"=>$id)), "");

        if(db::num_rows($rs_users) == 0 ) util::redirect("?module=local_users");

        $row_users=db::fetch($rs_users);
        $txtName = $row_users["Name"];
        $txtSurname = $row_users["Surname"];
        $txtEmail = $row_users["email"];
        $txtLogin = $row_users["UserName"];
        $selected = $row_users["user_type"];
        $txtComments = $row_users["comments"];
        $selected_country = $row_users["country_id"];
        $selected_branch = $row_users["branch_id"];
        $selected_group = $row_users["group_id"];
        
        $user_photo_file=trim($row_users["user_photo"]);
        
        if($user_photo_file!="")
        $photo_thumb=util::get_img($user_photo_file,true);

        $txtPasswordValue="********";

	$txtAddr = $row_users["address"];
	$txtPhone = $row_users["phone"];
        $chkApproved = $row_users["approved"] == "1" ? "checked" : "";
	$chkDisabled = $row_users["disabled"] == "1" ? "checked" : "";
        
        if($row_users['system_row']=="-1") $buttons_display= "none";
    }
    else access::has("add_local_user",2);
    
    user_add_page_loading($id,$row_users);
    
   // $results = orm::Select("user_types", array(), array() , "id");
    $countries_res = orm::Select("countries", array(), array(), "country_name");
    $branch_where = access::UserInfo()->access_type != 1 ? array("id"=>access::UserInfo()->branch_id) : array();
    $branches_res= orm::Select("branches", array(), $branch_where, "branch_name");
    $group_res= orm::Select("user_groups", array(), au::arr_where(array()), "group_name");
    //$user_type_options = webcontrols::GetOptions($results, "id", "type_name",$selected);
    
    $user_type_options = webcontrols::GetOptions(db::exec_sql(users_db::GetRolesByAccess()), "id","role_name", $selected);
    $country_options = webcontrols::GetOptions($countries_res, "id", "country_name", $selected_country);
    $branch_options =  webcontrols::GetOptions($branches_res, "id", "branch_name", $selected_branch);
    $user_group_options =  webcontrols::GetOptions($group_res, "id", "group_name", $selected_group);

    if(isset($_POST["btnSave"]) && $val->IsValid())
    {
        if(!isset($_GET["id"]))
        {                        
            $role_id = trim($_POST["drpUserType"]);
            $roles_res = db::exec_sql(users_db::GetRolesByAccess("id=$role_id"));
            $roles_count = db::num_rows($roles_res);
            
            if(get_users_count("txtLogin")=="0" && get_mail_count($_POST["txtEmail"])==0 && $roles_count>0)
            {
                add_file();         
                
                $arr_insert = au::add_insert(array("Name"=>trim($_POST["txtName"]),
                                        "Surname"=>trim($_POST["txtSurname"]),
                                        "UserName"=>trim($_POST["txtLogin"]),
                                         "Password"=>Local_Users_Password_Hash(trim($_POST["txtPassword"])),
                                         "added_date"=>util::Now(),
                                         "email"=>trim($_POST["txtEmail"]),
                                         "address"=>trim($_POST["txtAddr"]),
                                         "phone"=>trim($_POST["txtPhone"]),
                                         "approved"=>isset($_POST["chkApproved"]) ? 1:0,
                                         "disabled"=>isset($_POST["chkDisabled"]) ? 1:0,
                                         "user_type"=>$role_id,
                                         "country_id"=>trim($_POST["drpCountries"]),
                                         "branch_id"=>trim($_POST["drpBranches"]),
                                         "group_id"=>trim($_POST["drpGroup"]),
                                        "comments"=>trim($_POST["txtComments"]),
                                        "user_photo"=>$filename
                                       ));
                $arr_insert["branch_id"] = trim($_POST["drpBranches"]);
                user_adding($arr_insert);
                       
                $new_user_id=db::exec_insert(orm::GetInsertQuery("users", $arr_insert));

                asgDB::AcceptNewUser($new_user_id, trim($_POST["drpBranches"]));
                
                user_added($new_user_id, $arr_insert);
            }
        }
        else
        {
            $role_id = trim($_POST["drpUserType"]);
            $roles_res = db::exec_sql(users_db::GetRolesByAccess("id=$role_id"));
            $roles_count = db::num_rows($roles_res);
           
            if($roles_count>0)
            {                
                add_file();
                $arr_columns=array("Name"=>trim($_POST["txtName"]),
                                        "Surname"=>trim($_POST["txtSurname"]),
                                         "email"=>trim($_POST["txtEmail"]),
                                         "address"=>trim($_POST["txtAddr"]),
                                         "phone"=>trim($_POST["txtPhone"]),
                                         "approved"=>isset($_POST["chkApproved"]) ? 1:0,
                                         "disabled"=>isset($_POST["chkDisabled"]) ? 1:0,
                                         "user_type"=>$role_id,
                                         "branch_id"=>trim($_POST["drpBranches"]),
                                         "group_id"=>trim($_POST["drpGroup"]),
                                         "comments"=>trim($_POST["txtComments"]),
                                         "country_id"=>trim($_POST["drpCountries"])
                                       );

                if(trim($filename)!="")  
                {
                    $arr_columns["user_photo"]=$filename;
                    if(trim($user_photo_file)!="")
                    {
                            @unlink("user_photos".DIRECTORY_SEPARATOR.$user_photo_file);
                            @unlink("user_photos".DIRECTORY_SEPARATOR.util::get_thumb($user_photo_file));
                    }
                }

                if(isset($_POST["chkEdit"]))
                {
                    $arr_columns["Password"]=Local_Users_Password_Hash(trim($_POST["txtPassword"]));
                }
                $add_check = " AND NOT EXISTS (SELECT UserID FROM v_all_users WHERE email='".db::clear($_POST["txtEmail"])."' AND UserID<>$id ) ";
                user_editing($id,$arr_columns);
                orm::Update("users", au::add_update($arr_columns), array("UserID"=>$id), $add_check);
                user_edited($id,$arr_columns);
            }
        }

        util::redirect("?module=local_users");
    }


    if(isset($_POST["ajax"]))
    {
         $count = get_users_count("login_to_check");
         echo $count;
    }
    
    function get_users_count($var)
    {
        $results = orm::Select("users", array(), array("UserName"=>trim($_POST[$var])) , "");
        $count = db::num_rows($results);
        return $count;
    }
    
    function get_mail_count($mail)
    {
        $results = orm::Select("users", array(), array("email"=>$mail) , "");
        $count = db::num_rows($results);
        return $count;
    }

    function desc_func()
    {
        return ADD_EDIT_USER;
    }
    
    $filename = "";
    $thumb = "";
    
    function add_file()
    {    
        global $filename,$thumb;  
        if($_FILES['userphoto']['size']>0)
        {
                $filename=basename( $_FILES['userphoto']['name']);
                $arr = explode(".", $filename);
                $ext = end($arr);                
                $filename=md5(util::GUID()).".".$ext;
                $target_path = "user_photos/";
                $target_path = $target_path . $filename;

                move_uploaded_file($_FILES['userphoto']['tmp_name'], $target_path);     
               
                util::createThumbnail($target_path,90);
                $thumb=".thumb.".$ext;
        }

    }

?>
