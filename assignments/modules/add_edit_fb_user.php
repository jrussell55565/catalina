<?php if(!isset($RUN)) { exit(); } ?>
<?php

    require "db/asg_db.php";
    require "db/users_db.php";
   // require "events/users_events.php";

    access::menu("fb_users");
    access::has("view_fb_users",2);

    $val = new validations("btnSave");        
    $val->AddValidator("drpUserType", "notequal", USER_TYPE_VAL , "-1");    
    $val->AddValidator("drpBranches", "notequal", BRANCH_VAL , "-1");
    if(!isset($_GET["id"])) $val->AddValidator("txtEmail", "empty", R_EMAIL_VAL , "-1");
   // $val->AddValidator("txtEmail", "email", R_EMAIL_VAL , "-1");

    $selected="-1";
    $selected_group = "-1";    
    $selected_branch = "-1";

    $login_disabled="";
    $mode="add";    
    
    $user_photo_file = "";
    $photo_thumb = "";
    $row_users = 0;
    $id = "-1";
    
    if(isset($_GET["id"]))
    {
        access::has("edit_fb_user",2);        
        $login_disabled="disabled='disabled' ";
        $mode="edit";
        $id = util::GetID("?module=fb_users");
        $rs_users=orm::Select("app_users", array(), au::arr_where(array("UserID"=>$id)), "");

        if(db::num_rows($rs_users) == 0 ) util::redirect("?module=fb_users");

        $row_users=db::fetch($rs_users);
        $txtName = $row_users["Name"];
        $txtSurname = $row_users["Surname"];
        $txtEmail = $row_users["email"];        
        $selected = $row_users["user_type"];
        $txtComments = $row_users["comments"];        
        $selected_branch = $row_users["branch_id"];
        $selected_group = $row_users["group_id"];

	$chkDisabled = $row_users["disabled"] == "1" ? "checked" : "";
    }
    else access::has("add_fb_user",2);        
           
    $branch_where = access::UserInfo()->access_type != 1 ? array("id"=>access::UserInfo()->branch_id) : array();
    $branches_res= orm::Select("branches", array(), $branch_where, "branch_name");
    $group_res= orm::Select("user_groups", array(), array(), "group_name");
    //$user_type_options = webcontrols::GetOptions($results, "id", "type_name",$selected);
    
    $user_type_options = webcontrols::GetOptions(db::exec_sql(users_db::GetRolesByAccess()), "id","role_name", $selected);    
    $branch_options =  webcontrols::GetOptions($branches_res, "id", "branch_name", $selected_branch);
    $user_group_options =  webcontrols::GetOptions($group_res, "id", "group_name", $selected_group);

    if(isset($_POST["btnSave"]) && $val->IsValid())
    {
        if(!isset($_GET["id"]))
        {                        
            $role_id = trim($_POST["drpUserType"]);
            $roles_res = db::exec_sql(users_db::GetRolesByAccess("id=$role_id"));
            $roles_count = db::num_rows($roles_res);
            
            if(get_users_count("txtEmail")=="0" && $roles_count>0)
            {
               
                $arr_insert = au::add_insert(array(
                                         "added_date"=>util::Now(),
                                         "email"=>trim($_POST["txtEmail"]),                                         
                                         "disabled"=>isset($_POST["chkDisabled"]) ? 1:0,
                                         "user_type"=>$role_id,                                         
                                         "branch_id"=>trim($_POST["drpBranches"]),
                                         //"group_id"=>trim($_POST["drpGroup"]),
                                         "app_id"=>3,
                                        "comments"=>trim($_POST["txtComments"])                                        
                                       ));
                $arr_insert["branch_id"] = trim($_POST["drpBranches"]);                
                       
                $new_user_id=db::exec_insert(orm::GetInsertQuery("app_users", $arr_insert));

               // asgDB::AcceptNewUser($new_user_id, trim($_POST["drpBranches"]));
                               
            }
        }
        else
        {
            $role_id = trim($_POST["drpUserType"]);
            $roles_res = db::exec_sql(users_db::GetRolesByAccess("id=$role_id"));
            $roles_count = db::num_rows($roles_res);
           
            if($roles_count>0)
            {                                
                $arr_columns=array(    //  "email"=>trim($_POST["txtEmail"]),                                         
                                         "disabled"=>isset($_POST["chkDisabled"]) ? 1:0,
                                         "user_type"=>$role_id,
                                         "branch_id"=>trim($_POST["drpBranches"]),
                                        // "group_id"=>trim($_POST["drpGroup"]),
                                         "comments"=>trim($_POST["txtComments"]),                                         
                                       );

                         
                orm::Update("app_users", au::add_update($arr_columns), array("UserID"=>$id));
             
            }
        }

        util::redirect("?module=fb_users");
    }


    if(isset($_POST["ajax"]))
    {
         $count = get_users_count("email_to_check");
         echo $count;
    }
    
    function get_users_count($var)
    {
        $results = orm::Select("app_users", array(), array("email"=>trim($_POST[$var])) , "");
        $count = db::num_rows($results);
        return $count;
    }

    function desc_func()
    {
        return ADD_EDIT_FB_USER;
    }
    
    $filename = "";
    $thumb = "";
    

?>
