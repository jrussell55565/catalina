<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

$vir_search_po = $_GET['vir_search_po'];
$vir_search_wo = $_GET['vir_search_wo'];
$vir_search_un = $_GET['vir_search_un'];
$vir_search_sd = $_GET['vir_search_sd'];
$vir_search_ed = $_GET['vir_search_ed'];

$predicate = null;

// Make our sql
if (!empty($vir_search_po)) {
  $predicate .= ' AND vir_itemnum = '.$vir_search_po;
}
if (!empty($vir_search_wo)) {
  $predicate .= ' AND work_order = '.$vir_search_wo;
}
if (!empty($vir_search_un)) {
  $predicate .= ' AND (truck_number = '.$vir_search_un.' OR trailer_number = '.$vir_search_un.')';
}
if (!empty($vir_search_sd)){
  $predicate .= ' AND insp_date BETWEEN date_format('.$vir_search_sd.',\'%m/%d/%Y\') AND date_format('.$vir_search_sd.',\'%m/%d/%Y\')';
}

$statement = "SELECT * FROM virs WHERE 1=1 ".$predicate;

try {
  if ($result = $mysqli->query($statement)) {
      $counter = 0;
      $sql_output = array();
     while($obj = $result->fetch_object()){ 
        $sql_output[$counter]['vir_itemnum'] = $obj->vir_itemnum;
        $sql_output[$counter]['insp_date'] = $obj->insp_date;
        $sql_output[$counter]['truck_number'] = $obj->truck_number;
        $sql_output[$counter]['trailer_number'] = $obj->trailer_number;
        $sql_output[$counter]['work_order'] = $obj->work_order;
     }
   }else{
    throw new Exception($mysqli->$error, 1);
   }
}catch (Exception $e){
   $sql_output['error'] = $e;
}finally{
  $result->close();  
}

print json_encode($sql_output);
?>
