<?php if(!isset($RUN)) { exit(); } ?>
<?php 

$id = util::GetID("?module=default");
//$results = orm::Select("pages",array(),array("id"=>$id), "");
$results = access_db::GetPageList(access::UserInfo()->role_id, " and p.id=$id", "p.*");
$count = sizeof($results);
if($count == 0) util::redirect("?module=default");

$row = $results[0];
$page_name =$row['page_name'];
$page_content =$row['page_content'];

function desc_func()
{
	global $page_name;
	return $page_name;
}

?>
