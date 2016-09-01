<?php

  
	require "../lib/util.php";
	require '../config.php';
	require '../db/mysql2.php';
        require '../db/asg_db.php';
	require '../db/access_db.php';  
	require "../lib/access.php";
	require "../db/orm.php";	
	require "../lib/webcontrols.php";	
      
        $has_access = false;
        if(isset($_POST['login']))
        {
            $login = db::esp(trim($_POST['login']));			
            $password = Local_Users_Password_Hash(trim($_POST['password']));			
            $results=access_db::GetModules($login, $password, $password, true);
            $has_result = sizeof($results)>0 ? true : false;
            if($has_result!=false )
            {
                $row = $results[0];
                if($row["system_row"]=="1")
                {
                    $has_access=true;
                    $data = $_POST['data'];
                }
            }
                        
        }
        
        if($has_access==false)
        {
            echo "Login or password is incorrect !";
            exit();            
        }
        
        $insert_user_id = $row['user_id'];
        $users = simplexml_load_string($data);
        
        $db = new db();
        $db->connect();	        
        
        try
        {
            for($i=0;$i<count($users->user);$i++)
            {                    
		$name = trim($users->user[$i]->name);                                
                $surname = trim($users->user[$i]->surname);
                $email = trim($users->user[$i]->email);
                $login = trim($users->user[$i]->login);
                $password = Local_Users_Password_Hash(trim($users->user[$i]->password));
                $country_name = trim($users->user[$i]->country);
                $branch_name = trim($users->user[$i]->branch_name);
                $group_name = trim($users->user[$i]->group_name);
                $role_name = trim($users->user[$i]->role);
                $address = trim($users->user[$i]->address);
                $phone = trim($users->user[$i]->phone);
                $comments = trim($users->user[$i]->comments);
                $approved = trim($users->user[$i]->approved);
                $disabled = trim($users->user[$i]->disabled);
                
                if($name=="") break;
                
                if($login == "")
                {
                        $db->rollback();
                        echo "Login cannot be empty";
                        exit();
                }
                else
                {
                    $results = orm::Select("users", array(), array("UserName"=>$login) , "");
                    $count = db::num_rows($results);
                    if($count>0)
                    {
                        $db->rollback();
                        echo "This login already exists ".$login;
                        exit();
                    }
                }
                
                if($email == "")
                {
                    $email = util::GUID()."@example.com";
                }
                else
                {
                    $results = orm::Select("users", array(), array("email"=>$email) , "");
                    $count = db::num_rows($results);
                    if($count>0)
                    {
                        $db->rollback();
                        echo "This email already exists ".$email;
                        exit();
                    }
                }
                
                if($country_name=="") $country_id = DEFAULT_COUNTRY;
                else 
                {
                    $country_id = $db->query_single_value(orm::GetSelectQuery("countries", array("id"), array("country_name"=>$country_name), ""), "id");
                    if($country_id=="") 
                    {
                        $db->rollback();
                        echo "Country not found";
                        exit();
                    }
                }
                
                if($branch_name=="")
                {
                    $branch_id = $db->query_single_value(orm::GetSelectQuery("branches", array("id"), array("system_row"=>"1"), ""), "id");
                }
                else
                {
                    $branch_id = $db->query_single_value(orm::GetSelectQuery("branches", array("id"), array("branch_name"=>$branch_name), ""), "id");
                    if($branch_id=="") 
                    {
                        $db->rollback();
                        echo "Branch not found ".$branch_name;
                        exit();
                    }
                }
                
                if($group_name=="")
                {
                    $group_id = $db->query_single_value(orm::GetSelectQuery("user_groups", array("id"), array("is_default"=>"1"), ""), "id");
                }
                else
                {
                    $group_id = $db->query_single_value(orm::GetSelectQuery("user_groups", array("id"), array("group_name"=>$group_name), ""), "id");
                    if($group_id=="") 
                    {
                        $group_id = $db->insert_query(orm::GetInsertQuery("user_groups", array("group_name"=>$group_name)));
                    }
                }
                
                if($role_name=="")
                {
                    $role_id = $db->query_single_value(orm::GetSelectQuery("roles", array("id"), array("system_row"=>"2"), ""), "id");
                }
                else
                {
                    $role_id = $db->query_single_value(orm::GetSelectQuery("roles", array("id"), array("role_name"=>$role_name), ""), "id");
                    if($role_id=="") 
                    {
                        $db->rollback();
                        echo "Role not found ".$role_name;
                        exit();
                    }
                }
                
                if($approved=="") $approved = 1 ;
                if($disabled=="") $disabled = 0 ; 
                
                $sql = orm::GetInsertQuery("users", array("UserName"=>$login,
                                                          "Password"=>$password,
                                                          "Name"=>$name,
                                                          "Surname"=>$surname,
                                                          "added_date"=>util::Now(),
                                                          "user_type"=>$role_id,
                                                          "email"=>$email,
                                                          "address"=>$address,
                                                          "phone"=>$phone,
                                                          "approved"=>$approved,
                                                          "disabled"=>$disabled,
                                                          "country_id"=>$country_id,
                                                          "branch_id"=>$branch_id,
                                                          "group_id"=>$group_id,
                                                          "comments"=>$comments,
                                                          "inserted_by"=>$insert_user_id,
                                                          "inserted_date"=>util::Now()
                                                          ));
                                
                $user_id = $db->insert_query($sql);
                
                $db->query(asgDB::AcceptNewUser($user_id, $branch_id, false));
                 
            }
            
            $db->commit();
            echo "Success !";
        }
        catch(Exception $e)
        {                  
            $db->rollback();
            echo $e->getMessage().$e->getLine();            
        }

        $db->close_connection();
        
        
?>        