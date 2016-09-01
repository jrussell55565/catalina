<?php if(!isset($RUN)) { exit(); } ?>
<?php 

access::menu("dashboard");

include("Includes/FusionCharts.php");
//We've also included ../Includes/FC_Colors.asp, having a list of colors
//to apply different colors to the chart's columns. We provide a function for it - getFCColor()
include("Includes/FC_Colors.php");

access::menu("dashboard");

require "db/reports_db.php";
require "db/avg_reports.php";

$db = new db();
$db->connect();

$country_res = $db->query_as_array(avg::GetUsersByCountry());
$country_js = "";
//while($country_row = db::fetch($country_res))
$total_user_count = 0;
for($i=0;$i<sizeof($country_res);$i++)
{
    $country_row = $country_res[$i];
    $country_name = $country_row['country_name'];
    $user_count = $country_row['user_count'];
    $total_user_count+=$user_count;
    $country_js.=",{label: \"$country_name - ($user_count) \",data: $user_count}";
}

if($country_js!="")$country_js=substr($country_js,1);

$users_res=$db->exec_sql(avg::GetUsersCountQuery());
$users_row = db::fetch($users_res);
$users_count = $users_row['qcount'];

$qst_res=$db->exec_sql(avg::GetQuestionsCountQuery());
$qst_row = db::fetch($qst_res);
$qst_count = $qst_row['qcount'];

$exams_res=$db->exec_sql(avg::GetExamsCountQuery());
$exams_row = db::fetch($exams_res);
$exams_count = $exams_row['qcount'];

$surveys_res=$db->exec_sql(avg::GetSurveyCountQuery());
$surveys_row = db::fetch($surveys_res);
$surveys_count = $surveys_row['qcount'];

$pages_res=$db->exec_sql(avg::GetPagesCountQuery());
$pages_row = db::fetch($pages_res);
$pages_count = $pages_row['qcount'];

$users_res=$db->exec_sql(avg::GetLastExamUsers());
//$users_row = db::fetch($users_res);



    require "extgrid.php";
    require "db/users_db.php";

    $hedaers = array("&nbsp;",LOGIN,  USER_NAME, USER_SURNAME, DATE_REGISTERED, EMAIL);
    $columns = array("UserName"=>"text", "Name"=>"text","Surname"=>"Surname","added_date"=>"short date","email"=>"text");

    $grd = new extgrid($hedaers,$columns, "index.php?module=default_page1");
    $grd->exp_enabled=false;
    $grd->edit_link="index.php?module=add_edit_user";
    $grd->id_column="UserID";
    $grd->column_override=array("UserName"=>"login_override");
    $grd->auto_id=true;
    $grd->delete=false;
    $grd->edit=false;
    
    $query = users_db::LastUsersQuery();
    $grd->DrowTable($query);
    $grid_html = $grd->table;
    
     function login_override($row)
    {
        $login = $row['UserName'];
        $user_photo_file = util::get_img_file($row['user_photo']);
        $href= "index.php?module=add_edit_user&id=".$row['UserID'];
       // $thumb = util::get_thumb($user_photo_file);
     //   $res = "<a href=\"$href\" class=\"ttip_b\" title=\"<img style='width:200px' src='user_photos/$user_photo_file' />\">$login</a>";
           $res = "<a href=\"$href\"  \">$login</a>";
        //class="ttip_b" title="<b><i>salam</i></b>" 
      //  echo "user_photos/$user_photo_file";
        return $res;
    }



$data = array();
$tableSize = 300;

$percent = 0;

    $i=0;
    while ($row_users=db::fetch($users_res))
    {        
        $arrData[$i][1] = $row_users['Name']." ".$row_users['Surname']."(".$row_users['pass_score_point']." )";
	$arrData[$i][2] = $row_users['pass_score_point'];
        $i++;
	//Now, we need to convert this data into XML. We convert using string concatenation.
	//Initialize <graph> element	
    }
 
        $strXML = "<graph  numberPrefix='' formatNumberScale='0' decimalPrecision='2'>";
	//Convert data to XML and append
        if($i>0)
        {
            foreach ($arrData as $arSubData)
		$strXML .= "<set name='" . $arSubData[1] . "' value='" . $arSubData[2] ."' color='". getFCColor() ."' />";
        }
	//Close <graph> element
	$strXML .= "</graph>";       


$db->close_connection();


  if(isset($_POST["ajax"]))
    {
         echo $grid_html;
    }


function get_percent($rate,$count)
{
    global $percent,$total_user_count;
    //$percent = round($rate*100/$count,2);
    $percent = round($rate*100/$total_user_count,2);    
    return $percent;
}    
    
function desc_func () { return $MODULES['Dashboard']; }
?>