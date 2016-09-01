<?php if(!isset($RUN)) { exit(); } ?>
<?php 

access::menu("change_password");

$pass_display = "";
$msg_display = "none";

if(access::UserInfo()->imported != 0)
{
	$pass_display = "none";
	$msg_display = "";		
}

$val = new validations("btnRegister");
$val->AddValidator("txtOldPass", "an", OLD_PASS_VAL,"");
$val->AddValidator("txtNewPass", "an", NEW_PASS_VAL,"");

if(isset($_POST["ajax"]))
{
	if($val->IsValid() && access::UserInfo()->imported=="0")
	{
		$old_pass = trim($_POST['old_password']);
		$new_pass = trim($_POST['new_password']);
		$old_pass_hash=Local_Users_Password_Hash($old_pass);
		$new_pass_hash=Local_Users_Password_Hash($new_pass);
		
		$results = orm::Select("users", array() , array("UserName"=>access::UserInfo()->login, "Password"=>$old_pass_hash, "approved"=>1 , "disabled"=>0) , "");
		$count = db::num_rows($results);
		if($count == 0 )
		{
			echo WRONG_PASS;
		}
		else
		{
			orm::Update("users", array("Password"=>$new_pass_hash),array("UserName"=>access::UserInfo()->login));
			//$_SESSION['txtPass'] = $new_pass_hash;
                        $user_info = access::UserInfo();
                        $user_info->password =$new_pass_hash;
                        access::Save($user_info);
			echo PASS_CHANGED;
		}
	}
}


function desc_func () { return CHNG_PASS; }

?>
