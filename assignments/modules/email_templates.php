<?php if(!isset($RUN)) { exit(); } ?>
<?php 

access::menu("email_templates");
access::has("view_mail_templates", 2);

$selected = "-1";
$results = orm::Select("email_templates", array(), array(),"");
$name_options = webcontrols::GetOptions($results, "id", "name", $selected);

if(isset($_POST["ajax"]))
{
	if(isset($_POST['load_temp']))
	{
		$id = $_POST['tempid'];
		$results = orm::Select("email_templates", array(), array("id"=>$id), "");
		$count = db::num_rows($results);
		if($count == 0 ) 
		{
			echo "-1";
			exit();
		}
		$row = db::fetch($results);
		echo $row['subject']."[{sep}]".$row['body']."[{sep}]".$row['vars'];
	}
	else if(isset($_POST['save_temp']) && access::has("edit_mail_templates") )
	{
		$id = $_POST['tempid'];
		$body = $_POST['body'];
		$subject = $_POST['subject'];
		orm::Update("email_templates", array("body"=>$body, "subject"=>$subject), array("id"=>$id));
		echo TEMP_SAVED; 
	}
}

function desc_func () { return EMAIL_TEMPLATES; }

?>
