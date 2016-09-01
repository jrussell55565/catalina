<?php
  
  require "lib/util.php";
  require 'config.php';
  require 'db/mysql2.php';
  require 'db/access_db.php';  
  require "lib/access.php";
  require "db/orm.php";
  require "db/tickets_db.php";
  
  access::menu("tickets");    
  
  $id = util::GetID("?module=no_access");

  $sql = "";
  if(!isset($_GET['type']))
  {
      $sql = "SELECT real_file_name, file_name FROM d_files df
                INNER JOIN d_txs tx ON tx.id=df.tx_id
                INNER JOIN tickets t ON t.tx_id = tx.id 
                WHERE df.id=$id ".au::view_where();
  }
  else 
  {
      $sql = "SELECT real_file_name, file_name FROM treply_files df
                INNER JOIN ticket_replies tr ON tr.id=df.reply_id
                INNER JOIN tickets t on t.id=tr.ticket_id
                INNER JOIN d_txs tx on tx.id = t.tx_id
                WHERE df.id=$id ".au::view_where();
  }
  
  $is_file_exist = true;
  
  $results = db::exec_sql($sql);
  if(db::num_rows($results)==0)
  {
      $is_file_exist=false;      
  }
  
  $row = db::fetch($results);
  $file_name = 'uploads/d_controls/'.$row['real_file_name'];
  
  if (!file_exists($file_name)) {
    $is_file_exist=false;
  }
  
  if($is_file_exist==false)
  {
      echo FILE_NOT_FOUND;
      exit();
  }

 // $ext = pathinfo($row['file_name'], PATHINFO_EXTENSION);
 // $real_file_name = $row['file_name'].".".$ext;
  
  header('Content-disposition: attachment; filename='.$row['file_name']);  
  readfile($file_name);

?>