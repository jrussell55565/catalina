<?php

@session_start();
if(isset($_GET['download']))
{
if($_GET['download']=="1")
{
  header("Content-type: text/plain");
  header("Content-Disposition: attachment; filename='config.php'");
  echo $_SESSION['fcontent'];
  exit();
}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Quizzes and Surveys</title>
<meta http-equiv="Content-Language" content="English" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script language ="javascript" src="../jquery.js"></script>
<script language ="javascript" src="../extgrid.js"></script>
<script src="cms.js" type="text/javascript"></script>
 <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="../bootstrap/css/bootstrap-responsive.min.css" />
        <!-- theme color-->
            <link rel="stylesheet" href="../css/blue.css" />
        <!-- tooltip -->    
			<link rel="stylesheet" href="../lib/qtip2/jquery.qtip.min.css" />
        <!-- main styles -->
            <link rel="stylesheet" href="../css/style.css" />
    
        <!-- Favicon -->
            <link rel="shortcut icon" href="../favicon.ico" />

<script language="javascript">
function install()
{

	document.getElementById('btnInstall').disabled="disabled";
	var str = $("form").serialize();	

	 $.post("install.php", { formstr : str , install : 1 },
         function(data){            
            alert(data);
	    document.getElementById('btnInstall').disabled="";
        });
}
</script>

</head>
<body>

    <script language="javascript">

         window.onscroll = function()
         {
            MoveLoadingMessage("loadingDiv");
         }

         jQuery.ajaxSetup({
            beforeSend: function() {            
            $('#loadingDiv').show()
         },
            complete: function(){
            $('#loadingDiv').hide()
         },
            success: function() {}
         });
         
        </script>
        
              <table style="display:none" id="loadingDiv" style="position: absolute; left: 10px; top: 10px">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td bgcolor="red">
                                        <font color="white" size="3"><b>&nbsp;Please, wait&nbsp;</b></font>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
               </table>

	<script language="javascript">
            MoveLoadingMessage("loadingDiv");
        </script>


<div id="wrap" >

<div id="header">
    <br>
<h3 align="center">els PHP Web Quiz Subjects installation</h3>
</div>

<div id="menu">
<ul>

</ul>
</div>

<?php 



$deleted=true;
$writeable =true;
if (!is_writable("../config.php")) 
{
	$writeable =false;				
}
else
{
	$file_content = $_SESSION['fcontent'];

	$filename = "../config.php";

	 if (!$handle = fopen($filename, 'w')) {
         echo "Cannot open config.php file ($filename)";
         exit;
    	}

    	if (fwrite($handle, $file_content) === FALSE) {
        	echo "Cannot write to file config.php)";
        	exit;
    	}

    	fclose($handle) ;
}

$dir = "../install"; // directory name

foreach (scandir($dir) as $item) {
    if ($item == '.' || $item == '..') continue;
    @unlink($dir.DIRECTORY_SEPARATOR.$item);
}
@rmdir($dir) or notdeleted();

//"<br /><font color=red>Cannot delete 'install' folder . Please, delete this folder manually <br></font>"

function notdeleted()
{
global $deleted;
$deleted=false;
}

$success= "els PHP Web Quiz has ben succesfully installed . <br> ";
$login_text = "go to <a href='../login.php'>Login page</a>";
$login_text .= "<br />Login : sysadmin <br>Password : sysadmin<br />";




?>

<div id="content">
<div  align=center> <br /><br />

<?php 
echo $success;
$text = "";
if($writeable==false)
{
$text = "Then ";
	echo "<font color=red>Your config.php file is not writeable . Please <a href='installed.php?download=1'><font color=blue>download config.php</font></a> and upload it to your ftp server and replace the file . </br></font>";
}

if($deleted == false)
{
$text = "Then ";
	echo "<font color=red>Cannot delete 'install' folder . Please, delete this folder manually . <br></font>";
}


echo $text.$login_text
?>

	
</div>

<div class="left"> 



</div>

<div style="clear: both;"> </div>

</div>

<div id="bottom"> </div>
<div id="footer" align="center">
Created by <a href="http://www.phpexamscript.net/">Php Web Quiz team</a>
</div>

</div>

</body>
</html>
