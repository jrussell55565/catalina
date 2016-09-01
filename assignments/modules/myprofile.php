<?php if(!isset($RUN)) { exit(); } ?>
<?php 

require "lib/fb.php";

$user_id = access::UserInfo()->user_id;
$user_photo = access::UserInfo()->user_photo;
$filename = "";
$thumb = "";

if(isset($_POST['btnSave']))
{        
    if(access::UserInfo()->imported=="0" && ALLOW_AVATAR_CHANGE=="yes") 
    add_file();
  //  echo $filename;
    if(trim($filename)!="")
    {
        orm::Update("users", array("user_photo"=>trim($filename)), array("UserID"=>$user_id));
        $user_info = access::UserInfo();
        $user_info->user_photo=$filename;    
        access::Save($user_info);
        
        if(trim($user_photo)!="")
        {
             $arrold = explode(".", $user_photo);
             $extold = end($arrold);           
           //   echo $user_photo."da";
             try {
             @unlink("user_photos".DIRECTORY_SEPARATOR.$user_photo);
             @unlink("user_photos".DIRECTORY_SEPARATOR.$user_photo.".thumb.".$extold);
             }
             catch(Exception $err)
             {
                 
             }
        } 
        $user_photo = $filename;
    }
}

$allow_change="";
if(access::UserInfo()->imported=="1" || access::UserInfo()->imported=="2" || ALLOW_AVATAR_CHANGE=="no")
{
    $allow_change="none";    
}

$name = access::UserInfo()->name;
$surname = access::UserInfo()->surname;
$email = access::UserInfo()->email;
$login = access::UserInfo()->login;
$branch_name = access::UserInfo()->branch_name;

if(access::UserInfo()->app_id==3) $img = fb::get_profile_photo (access::UserInfo()->login, 200, 200);
else $img = util::get_img($user_photo,false);

$allowed_formats_str = implode(",", $allowed_avatar_formats);
$restriction = UP_FILE_SIZE_MSG1." ".$max_avatar_size." ".UP_FILE_SIZE_MSG2." ".$allowed_formats_str.". ";
$restriction.=UP_FILE_SIZE_MSG3." ".$avatar_width." px";
    
function add_file()
{    
        global $filename,$thumb,$error_msg,$max_avatar_size,$allowed_avatar_formats;  
        $file_size= ($_FILES['userphoto']['size']/1024);
        $file_type = $_FILES['userphoto']['type'];
        
        if($file_size>$max_avatar_size) return;
        
        if($_FILES['userphoto']['size']>0)
        {
                $filename=basename($_FILES['userphoto']['name']);                
              
                $arr = explode(".", $filename);
                $ext = end($arr);                 
                if(!in_array($ext, $allowed_avatar_formats)) return;                
                $filename=md5(util::GUID()).".".$ext;
                $target_path = "user_photos/";
                $target_path = $target_path . $filename;

                move_uploaded_file($_FILES['userphoto']['tmp_name'], $target_path);     
               
                util::createThumbnail($target_path,90);
                $thumb=".thumb.".$ext;
        }

}

function desc_func() { return MY_PROFILE;}

?>