<?php 
	
	require "../lib/util.php";
	
	$website_url = str_replace("install/install.php","",util::GetCurrentUrl());	
	
	set_time_limit(90); 

	$view_access=false;
	$procedure_access=false;
	$trigger_access=false;
	$connected = true;

	if(isset($_POST['install'])) 
	{
		$formstr = $_POST['formstr'];								
		$perfs = explode("&", $formstr);	
		$posted = array();		

		foreach($perfs as $perf) 
		{
			$perf_key_values = explode("=", $perf);
			$key = urldecode($perf_key_values[0]);
			$value = urldecode($perf_key_values[1]);	
			$posted[$key]=$value;			
		}
	

		if (!function_exists('mysqli_connect')) {
  			echo "Mysqli library has not been installed on your server . Please, ask service provider to install it \n";
		}  
		else
		{
			
			$link=@mysqli_connect($posted['txtHost'],$posted['txtUser'],$posted['txtPass'],$posted['txtDB']) or databaseerror();						
			@mysqli_close($link);
		}
		
		if($connected==true) // && $writeable==true
		{
		check_privilegies();

		if($view_access==false)
		{
			echo "You don't have access for creating views . Please, ask your service provider to give access for creating views \n";
		}

		if($procedure_access==false)
		{
			echo "You don't have access for creating stored procedures . Please, ask your service provider to give access for creating stored procedures \n";
		}
		
		if($trigger_access==false)
		{
			echo "You don't have access for creating trigger . Please, ask your service provider to give access for creating triggers \n";
		}

		if($view_access==true && $trigger_access == true && $procedure_access == true)
		{
		
			$installation_type = $posted['inpt1'];
			$res = execute_database_scripts($installation_type);
			
			setup_config_file($posted);
			
			echo "1";
		}
		

		}
		

		exit();
	}

function setup_config_file($posted)
{
	$url = $posted['txtURL'];
	if (substr(rtrim($url), -1) != '/')
	{
		$url=$url."/";
	}
	$file_content = file_get_contents("config_install.php");
	$file_content = str_replace("[mysql_host]", trim($posted['txtHost']), $file_content);
	$file_content = str_replace("[mysql_user]", trim($posted['txtUser']), $file_content);
	$file_content = str_replace("[mysql_pass]", trim($posted['txtPass']), $file_content);
	$file_content = str_replace("[mysql_db]", trim($posted['txtDB']), $file_content);
	$file_content = str_replace("[url]", trim($url), $file_content);
	$file_content = str_replace("[use_math]", trim($posted['rdMath']), $file_content);
	$file_content = str_replace("[paging]", trim($posted['txtPaging']), $file_content);	
	$file_content = str_replace("[mail_server]", trim($posted['txtMailServer']), $file_content);
	$file_content = str_replace("[mail_from]", trim($posted['txtMailFrom']), $file_content);
	$file_content = str_replace("[mail_user]", trim($posted['txtMailUser']), $file_content);
	$file_content = str_replace("[mail_pass]", trim($posted['txtMailPass']), $file_content);
	$file_content = str_replace("[mail_charset]", trim($posted['txtMailCharset']), $file_content);
	$file_content = str_replace("[registration_enabled]", trim($posted['rdReg']), $file_content);	
	$file_content = str_replace("[system_name]", trim($posted['txtSystemName']), $file_content);	
	$file_content = str_replace("[page_title]", trim($posted['txtPageTitle']), $file_content);
        $file_content = str_replace("[default_country]", trim($posted['drpCountryID']), $file_content);
        $file_content = str_replace("[enable_calculator]", trim($posted['rdCalc']), $file_content);
        $file_content = str_replace("[allow_avatar_change]", trim($posted['rdAvatar']), $file_content);
		$file_content = str_replace("[mail_port]", trim($posted['txtMailPort']), $file_content);
	//	$file_content = str_replace("[mail_port]", trim($posted['txtMailPort']), $file_content);
	$file_content = str_replace("[FB_INT]", trim($posted['drpFB']) == "1" ? "yes" : "no", $file_content);
	$file_content = str_replace("[APP_ID]", trim($posted['txtFBAppID']), $file_content);
	$file_content = str_replace("[APP_SECRET]", trim($posted['txtFBSecret']), $file_content);
	
	$file_content = str_replace("[LDAP_INT]", trim($posted['drpLDAP']) == "1" ? "yes" : "no", $file_content);
	$file_content = str_replace("[LDAP_SERVER]", trim($posted['txtLDAPServer']), $file_content);
	$file_content = str_replace("[LDAP_PORT]", trim($posted['txtLDAPPort']), $file_content);
	$file_content = str_replace("[LDAP_STRING]", trim($posted['txtLDAPString']), $file_content);
	$file_content = str_replace("[LDAP_FILTER_STRING]", trim($posted['txtLDAPFString']), $file_content);
	$file_content = str_replace("[LDAP_NAME_STRING]", trim($posted['txtLDAPNameStr']), $file_content);
	$file_content = str_replace("[LDAP_SURNAME_STRING]", trim($posted['txtLDAPSurnameStr']), $file_content);
	$file_content = str_replace("[LDAP_LOGIN_STRING]", trim($posted['txtLDAPLoginStr']), $file_content);
	$file_content = str_replace("[LDAP_MAIL_STRING]", trim($posted['txtLDAPMailStr']), $file_content);
	
	$file_content = str_replace("[p_enabled]", trim($posted['drpPaypal']) == "1" ? "yes" : "no", $file_content);
	$file_content = str_replace("[p_email]", trim($posted['txtPMail']), $file_content);
	$file_content = str_replace("[p_n_s]", trim($posted['drpNS']) == "1" ? "yes" : "no", $file_content);
	$file_content = str_replace("[p_n_f]", trim($posted['drpNF']) == "1" ? "yes" : "no", $file_content);
	$file_content = str_replace("[p_currency]", trim($posted['txtPC']), $file_content);
	$file_content = str_replace("[p_data_name]", trim($posted['txtData']), $file_content);
	$file_content = str_replace("[p_sandbox]", trim($posted['drpPS'])=="1" ? "no" : "yes", $file_content);
	
	$file_content = str_replace("[ORDER_NUMBER]", trim($posted['txtOrder']), $file_content);	

	@session_start();

	$_SESSION['fcontent']=$file_content;
	


}

function execute_database_scripts($installation_type)
{
global $posted;
global $mylink;
$mylink=mysqli_connect($posted['txtHost'],$posted['txtUser'],$posted['txtPass'],$posted['txtDB']);

if($installation_type=="1")
{
    runfile("tables_and_data.sql");
    runfilep("procedures.sql");
	runfilep("triggers.sql",1);
}
else if($installation_type=="2")
{
    runfile("removing_from_free_to_gold1.2.sql");
	runfile("from_gold_to_platinum_1.3.sql");
    runfilep("procedures.sql");
}
else
{
    runfile("from_gold_to_platinum_1.3.sql");
	runfilep("procedures.sql");
}
mysqli_close($mylink);
return true;
}

function databaseerror()
{
	global $connected;
	$connected = false;
	echo "Cannot connect to mysql database . \n";
	
}

function check_privilegies()
{
global $view_access,$procedure_access,$trigger_access,$posted;
$link = @mysqli_connect($posted['txtHost'],$posted['txtUser'],$posted['txtPass'],$posted['txtDB']) ;

$results = mysqli_query($link,"SHOW PRIVILEGES");

while($row=mysqli_fetch_array($results))
{
	if($row['Privilege']=="Create view")
	{
		$view_access = true;
	}
	else if($row['Privilege']=="Create routine")
	{
		$procedure_access =true;
	}
	else if($row['Privilege']=="Trigger")
	{
		$trigger_access =true;
	}
}

@mysqli_close($link);
}

function runfile($file, $just_run = 0)
{
global $mylink;	
if($just_run==1)
{
	$file_content= file_get_contents($file);
	mysqli_query($mylink,$file_content)or die(mysqli_error($mylink));
	return ;
}
$file_content = file($file);
$query = "";
foreach($file_content as $sql_line){
if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
 $query .= $sql_line;
 if (substr(rtrim($query), -1) == ';'){
 //  echo $query;
   $result = mysqli_query($mylink,$query)or die(mysqli_error($mylink));
   $query = "";
  }
 }
}
}

function runfilep($file)
{
global $mylink;	
	$file_content = file_get_contents($file);

	$queries = explode("$$", $file_content);
	foreach($queries as $query)
	{
		if(trim($query)=="") continue ;

		$pos = strpos($query,"DELIMITER");
		if($pos!== false) continue;
		//echo $query."<br><br><br>";
		$result = mysqli_query($mylink,$query)or die(mysqli_error($mylink));

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
	    if(data!="1")
	    {     
            	alert(data);
	    	document.getElementById('btnInstall').disabled="";
	    }
	    else
	    {
		window.location.href="installed.php"; 
            }
        });
}
function ShowBackupMsg(inst_type)
{
    if(inst_type=="1")
    {
         document.getElementById('backupmsg').style.display="none";
    }
    else
        {
            document.getElementById('backupmsg').style.display="";
        }
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
<script language=javascript>
function fb_int()
{

var fb = document.getElementById("drpFB");
var strUser = fb.options[fb.selectedIndex].value;
if(strUser=="0")
{
	document.getElementById('trAppID').style.display="none";
	document.getElementById('trAppSecret').style.display="none";
}
else
{
	document.getElementById('trAppID').style.display="";
	document.getElementById('trAppSecret').style.display="";
}
}


function ldap_int()
{
var ldap = document.getElementById("drpLDAP");
var strUser = ldap.options[ldap.selectedIndex].value;
var dsp = "";

if(strUser=="0")
{
	dsp = "none";
}

for (var i = 1 ; i<9; i++)
{
document.getElementById('trLDAP'+i).style.display=dsp;
}

}

function paypal_int()
{
var ldap = document.getElementById("drpPaypal");
var strUser = ldap.options[ldap.selectedIndex].value;
var dsp = "";

if(strUser=="0")
{
	dsp = "none";
}

for (var i = 1 ; i<7; i++)
{
document.getElementById('trP'+i).style.display=dsp;
}
}


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
$yes = "<font color=green>yes</font>";
$no = "<font color=green>no</font>";
$writable = false;
if (is_writable("config.php")) 
{
	$writable = true;
}

?>

<div id="content">
<div  align=center> <br /><br />
   <form method="post" >

	<table style="width:500px">
		<tr>
			<td colspan=2><font size=3>Installation</font><hr /><br /> </td>
		</tr>
		<tr style="display:none">
			<td>Config file writable : </td>
			<td><?php echo $writable == true ?  $yes :  $no ;?></td>
		</tr>
		<tr>
			<td align=right>MYSQL Host : </td>
			<td><input type=text name=txtHost id=txtHost /></td>
		</tr>
		<tr>
			<td align=right>MYSQL User : </td>
			<td><input type=text name=txtUser id=txtUser /></td>
		</tr>
		<tr>
			<td align=right>MYSQL Password : </td>
			<td><input type=text name=txtPass id=txtPass /></td>
		</tr>
		<tr>
			<td align=right>MYSQL Database : </td>
			<td><input type=text name=txtDB id=txtDB /></td>
		</tr>
                <tr>
			<td align=right valign=top>Installation type : </td>
                        <td><input type="radio" name="inpt1" onclick="ShowBackupMsg(1)"  value="1" checked></input>New installation  <br>
							
                        </td>
		</tr>
		<tr>
			<td colspan=2><br /><hr /><br /> </td>
		</tr>
		<tr>
			<td align=right>Are you going to use Mathematic symbols ? : </td>
			<td><input type=radio name=rdMath value="yes" />Yes
				<input type=radio name=rdMath checked value="no" />No
			</td>
		</tr>
		<tr>
			<td align=right>Grid page size : </td>
			<td><input type=text name=txtPaging id=txtPaging value="30" /></td>
		</tr>
<tr>
			<td colspan=2><br /><hr /></td>
		</tr>
		<tr style="display:none">
			<td align=right>Show menu : </td>
			<td><input type=radio name=rdShowmenu checked value="registered" />Only to registered users<br>
				<input type=radio name=rdShowmenu value="all" />To all<br>
				<input type=radio name=rdShowmenu value="nobody" />Do not show menu to anybody
			</td>
		</tr>
<tr style="display:none">
			<td align=right>Show menu on Login page : </td>
			<td><input type=radio name=rdMenulogin value="yes" />Yes
				<input type=radio name=rdMenulogin checked value="no" />No
			</td>
		</tr>
<tr>
			<td align=right>Registration enabled : </td>
			<td><input type=radio name=rdReg checked value="yes" />Yes
				<input type=radio name=rdReg value="no" />No
			</td>
		</tr>
            
            <tr style='display:none'>
			<td align=right>Calculator enabled : </td>
			<td><input type=radio name=rdCalc checked value="yes" />Yes
				<input type=radio name=rdCalc value="no" />No
			</td>
		</tr>
            
            <tr>
			<td align=right>Users can change profile photos : </td>
			<td><input type=radio name=rdAvatar checked value="yes" />Yes
				<input type=radio name=rdAvatar value="no" />No
			</td>
		</tr>

		</tr>
<tr style="display:none">
			<td align=right>Web site template : </td>
			<td><input type=radio name=rdTemp checked  value="gold" />Gold
				<input type=radio name=rdTemp value="standard" />Standard
			</td>
		</tr>
<tr>
			<td colspan=2><br /><hr /><br /> </td>
		</tr>
<tr>
			<td align=right>Smtp server : </td>
			<td><input type=text name=txtMailServer id=txtMailServer /></td>
		</tr>
		<tr>
			<td align=right>Smtp username : </td>
			<td><input type=text name=txtMailUser id=txtMailUser /></td>
		</tr>
		<tr>
			<td align=right>Smtp Password : </td>
			<td><input type=text name=txtMailPass id=txtMailPass /></td>
		</tr>
		<tr>
			<td align=right>Mail charset : </td>
			<td><input type=text name=txtMailCharset id=txtMailCharset value="UTF-8" /></td>
		</tr>
			<tr>
			<td align=right>Mail port : </td>
			<td><input type=text name=txtMailPort id=txtMailPort value="25" /></td>
		</tr>
<tr>
			<td align=right>Smtp from address : </td>
			<td><input type=text name=txtMailFrom id=txtMailFrom /></td>
		</tr>
<tr>
			<td colspan=2><br /><hr /><br /> </td>
		</tr>
<tr>
			<td align=right>Your web site URL : </td>
			<td><input type=text name=txtURL id=txtURL value="<?php echo $website_url ?>" /></td>
		</tr>
<tr>
			<td align=right>System name : </td>
			<td><input type=text name=txtSystemName id=txtSystemName maxlength ="20" value="PHP Web Quiz" /></td>
		</tr>
<tr>
			<td align=right>Page title : </td>
			<td><input type=text name=txtPageTitle id=txtPageTitle value="PHP Web Quiz" /></td>
		</tr>
                
                <tr>
                    <td align="right">
                        Default country :
                    </td>
                    <td>
                        <select id="drpCountryID" name="drpCountryID">
                            
        <option value=241>United States</option>                              
        <option value=2>Afghanistan</option>         
           <option value=3>Egypt</option>            
          <option value=5>Albania</option>           
          <option value=6>Algeria</option>           
       <option value=7>American Samoa</option>       
    <option value=8>Virgin Islands, U.s.</option>    
          <option value=9>Andorra</option>           
          <option value=10>Angola</option>           
         <option value=11>Anguilla</option>          
        <option value=12>Antarctica</option>         
    <option value=13>Antigua And Barbuda</option>    
     <option value=14>Equatorial Guinea</option>     
         <option value=15>Argentina</option>         
          <option value=16>Armenia</option>          
           <option value=17>Aruba</option>           
         <option value=18>Ascension</option>         
        <option value=19>Azerbaijan</option>         
         <option value=20>Ethiopia</option>          
         <option value=21>Australia</option>         
          <option value=22>Bahamas</option>          
          <option value=23>Bahrain</option>          
        <option value=24>Bangladesh</option>         
         <option value=25>Barbados</option>          
          <option value=26>Belgium</option>          
          <option value=27>Belize</option>           
           <option value=28>Benin</option>           
          <option value=29>Bermuda</option>          
          <option value=30>Bhutan</option>           
          <option value=31>Bolivia</option>          
  <option value=32>Bosnia And Herzegovina</option>   
         <option value=33>Botswana</option>          
       <option value=34>Bouvet Island</option>       
          <option value=35>Brazil</option>           
  <option value=36>Virgin Islands, British</option>  
       <option value=37>British Indian Ocean         
                Territory</option>                
     <option value=38>Brunei Darussalam</option>     
         <option value=39>Bulgaria</option>          
       <option value=40>Burkina Faso</option>        
          <option value=41>Burundi</option>          
           <option value=42>Chile</option>           
           <option value=43>China</option>           
       <option value=44>Cook Islands</option>        
        <option value=45>Costa Rica</option>         
       <option value=46>C?te D'ivoire</option>       
          <option value=47>Denmark</option>          
          <option value=48>Germany</option>          
       <option value=49>Saint Helena</option>        
       <option value=50>Diego Garcia</option>        
         <option value=51>Dominica</option>          
    <option value=52>Dominican Republic</option>     
         <option value=53>Djibouti</option>          
          <option value=54>Ecuador</option>          
        <option value=55>El Salvador</option>        
          <option value=56>Eritrea</option>          
          <option value=57>Estonia</option>          
     <option value=58>Europ?ische Union</option>     
         <option value=59>Falkland Islands           
               (malvinas)</option>                
       <option value=60>Faroe Islands</option>       
           <option value=61>Fiji</option>            
          <option value=62>Finland</option>          
          <option value=63>France</option>           
       <option value=64>French Guiana</option>       
     <option value=65>French Polynesia</option>      
          <option value=66>French Southern           
               Territories</option>               
           <option value=67>Gabon</option>           
          <option value=68>Gambia</option>           
          <option value=69>Georgia</option>          
           <option value=70>Ghana</option>           
         <option value=71>Gibraltar</option>         
          <option value=72>Grenada</option>          
          <option value=73>Greece</option>           
         <option value=74>Greenland</option>         
      <option value=75>European Union</option>       
           <option value=76>Guam</option>            
         <option value=77>Guatemala</option>         
         <option value=78>Guernsey</option>          
          <option value=79>Guinea</option>           
       <option value=80>Guinea-bissau</option>       
          <option value=81>Guyana</option>           
           <option value=82>Haiti</option>           
     <option value=83>Heard Island And Mcdonald      
                 Islands</option>                 
         <option value=84>Honduras</option>          
         <option value=85>Hong Kong</option>         
           <option value=86>India</option>           
         <option value=87>Indonesia</option>         
        <option value=88>Isle Of Man</option>        
           <option value=89>Iraq</option>            
 <option value=90>Iran, Islamic Republic Of</option> 
          <option value=91>Ireland</option>          
          <option value=92>Iceland</option>          
          <option value=93>Israel</option>           
           <option value=94>Italy</option>           
          <option value=95>Jamaica</option>          
           <option value=96>Japan</option>           
           <option value=97>Yemen</option>           
          <option value=98>Jersey</option>           
          <option value=99>Jordan</option>           
      <option value=100>Cayman Islands</option>      
         <option value=101>Cambodia</option>         
         <option value=102>Cameroon</option>         
          <option value=103>Canada</option>          
    <option value=104>Kanarische Inseln</option>     
        <option value=105>Cape Verde</option>        
        <option value=106>Kazakhstan</option>        
          <option value=107>Qatar</option>           
          <option value=108>Kenya</option>           
        <option value=109>Kyrgyzstan</option>        
         <option value=110>Kiribati</option>         
 <option value=111>Cocos (keeling) Islands</option>  
         <option value=112>Colombia</option>         
         <option value=113>Comoros</option>          
<option value=114>Congo, The Democratic Republic Of  
                   The</option>                   
          <option value=115>Congo</option>           
    <option value=116>Korea, Democratic People's     
               Republic Of</option>               
    <option value=117>Korea, Republic Of</option>    
         <option value=118>Croatia</option>          
           <option value=119>Cuba</option>           
          <option value=120>Kuwait</option>          
     <option value=121>Lao People's Democratic       
                Republic</option>                 
         <option value=122>Lesotho</option>          
          <option value=123>Latvia</option>          
         <option value=124>Lebanon</option>          
         <option value=125>Liberia</option>          
  <option value=126>Libyan Arab Jamahiriya</option>  
      <option value=127>Liechtenstein</option>       
        <option value=128>Lithuania</option>         
        <option value=129>Luxembourg</option>        
          <option value=130>Macao</option>           
        <option value=131>Madagascar</option>        
          <option value=132>Malawi</option>          
         <option value=133>Malaysia</option>         
         <option value=134>Maldives</option>         
           <option value=135>Mali</option>           
          <option value=136>Malta</option>           
         <option value=137>Morocco</option>          
     <option value=138>Marshall Islands</option>     
        <option value=139>Martinique</option>        
        <option value=140>Mauritania</option>        
        <option value=141>Mauritius</option>         
         <option value=142>Mayotte</option>          
  <option value=143>Macedonia, The Former Yugoslav   
               Republic Of</option>               
          <option value=144>Mexico</option>          
   <option value=145>Micronesia, Federated States    
                   Of</option>                    
         <option value=146>Moldova</option>          
          <option value=147>Monaco</option>          
         <option value=148>Mongolia</option>         
        <option value=149>Montserrat</option>        
        <option value=150>Mozambique</option>        
         <option value=151>Myanmar</option>          
         <option value=152>Namibia</option>          
          <option value=153>Nauru</option>           
          <option value=154>Nepal</option>           
      <option value=155>New Caledonia</option>       
       <option value=156>New Zealand</option>        
      <option value=157>Neutrale Zone</option>       
        <option value=158>Nicaragua</option>         
       <option value=159>Netherlands</option>        
   <option value=160>Netherlands Antilles</option>   
          <option value=161>Niger</option>           
         <option value=162>Nigeria</option>          
           <option value=163>Niue</option>           
 <option value=164>Northern Mariana Islands</option> 
      <option value=165>Norfolk Island</option>      
          <option value=166>Norway</option>          
           <option value=167>Oman</option>           
         <option value=168>Austria</option>          
         <option value=169>Pakistan</option>         
      <option value=170>Palestinian Territory,       
                Occupied</option>                 
          <option value=171>Palau</option>           
          <option value=172>Panama</option>          
     <option value=173>Papua New Guinea</option>     
         <option value=174>Paraguay</option>         
           <option value=175>Peru</option>           
       <option value=176>Philippines</option>        
         <option value=177>Pitcairn</option>         
          <option value=178>Poland</option>          
         <option value=179>Portugal</option>         
       <option value=180>Puerto Rico</option>        
         <option value=181>R?union</option>          
          <option value=182>Rwanda</option>          
         <option value=183>Romania</option>          
    <option value=184>Russian Federation</option>    
     <option value=185>Solomon Islands</option>      
          <option value=186>Zambia</option>          
          <option value=187>Samoa</option>           
        <option value=188>San Marino</option>        
  <option value=189>Sao Tome And Principe</option>   
       <option value=190>Saudi Arabia</option>       
          <option value=191>Sweden</option>          
       <option value=192>Switzerland</option>        
         <option value=193>Senegal</option>          
  <option value=194>Serbien und Montenegro</option>  
        <option value=195>Seychelles</option>        
       <option value=196>Sierra Leone</option>       
         <option value=197>Zimbabwe</option>         
        <option value=198>Singapore</option>         
         <option value=199>Slovakia</option>         
         <option value=200>Slovenia</option>         
         <option value=201>Somalia</option>          
          <option value=202>Spain</option>           
        <option value=203>Sri Lanka</option>         
  <option value=204>Saint Kitts And Nevis</option>   
       <option value=205>Saint Lucia</option>        
<option value=206>Saint Pierre And Miquelon</option> 
      <option value=207>Saint Vincent And The        
               Grenadines</option>                
       <option value=208>South Africa</option>       
          <option value=209>Sudan</option>           
   <option value=210>South Georgia And The South     
            Sandwich Islands</option>             
         <option value=211>Suriname</option>         
  <option value=212>Svalbard And Jan Mayen</option>  
        <option value=213>Swaziland</option>         
   <option value=214>Syrian Arab Republic</option>   
        <option value=215>Tajikistan</option>        
          <option value=216>Taiwan</option>          
    <option value=217>Tanzania, United Republic      
                   Of</option>                    
         <option value=218>Thailand</option>         
       <option value=219>Timor-leste</option>        
           <option value=220>Togo</option>           
         <option value=221>Tokelau</option>          
          <option value=222>Tonga</option>           
   <option value=223>Trinidad And Tobago</option>    
     <option value=224>Tristan da Cunha</option>     
           <option value=225>Chad</option>           
      <option value=226>Czech Republic</option>      
         <option value=227>Tunisia</option>          
          <option value=228>Turkey</option>          
       <option value=229>Turkmenistan</option>       
 <option value=230>Turks And Caicos Islands</option> 
          <option value=231>Tuvalu</option>          
          <option value=232>Uganda</option>          
         <option value=233>Ukraine</option>          
    <option value=234>Union der Sozialistischen      
            Sowjetrepubliken</option>             
         <option value=235>Uruguay</option>          
        <option value=236>Uzbekistan</option>        
         <option value=237>Vanuatu</option>          
      <option value=238>Holy See (vatican City       
                 State)</option>                  
        <option value=239>Venezuela</option>         
   <option value=240>United Arab Emirates</option>                
      <option value=242>United Kingdom</option>      
         <option value=243>Viet Nam</option>         
    <option value=244>Wallis And Futuna</option>     
     <option value=245>Christmas Island</option>     
         <option value=246>Belarus</option>          
      <option value=247>Western Sahara</option>      
 <option value=248>Central African Republic</option> 
          <option value=249>Cyprus</option>          
         <option value=250>Hungary</option>          
        <option value=251>Montenegro</option>        

                            
                        </select>
                    </td>
					
					<tr>
			<td colspan=2><br /><hr /><br /> </td>
		</tr>
		
		<tr>	
			<td align=right>Integration with facebook :</td>
			<td>
				<select id=drpFB name=drpFB onchange="fb_int()">
					<option value=0>No</option>
					<option value=1>Yes</option>					
				</select>
			</td>
		</tr>
		
		<tr style="display:none" id=trAppID>	
			<td align=right>Facebook Application ID :</td>
			<td><input type=text id=txtFBAppID name=txtFBAppID /></td>
		</tr>
		
		<tr  style="display:none" id=trAppSecret>	
			<td align=right>Facebook secret :</td>
			<td><input type=text id=txtFBSecret name=txtFBSecret /></td>
		</tr>
		<tr>
			<td colspan=2><br /><hr /><br /> </td>
		</tr>
		
		<tr>	
			<td align=right>Integration with LDAP :</td>
			<td>
				<select id=drpLDAP name=drpLDAP onchange="ldap_int()">
					<option value=0>No</option>
					<option value=1>Yes</option>					
				</select>
			</td>
		</tr>
		
		<tr style="display:none" id=trLDAP1>	
			<td align=right>LDAP Server :</td>
			<td><input type=text id=txtLDAPServer name=txtLDAPServer value="localhost" /></td>
		</tr>
		
		<tr  style="display:none" id=trLDAP2>	
			<td align=right>LDAP Port :</td>
			<td><input type=text id=txtLDAPPort name=txtLDAPPort value="389" /></td>
		</tr>
		
		<tr  style="display:none" id=trLDAP3 >	
			<td align=right>LDAP String :</td>
			<td><input type=text id=txtLDAPString name=txtLDAPString value="uid=[USER_NAME],ou=People,dc=example,dc=com" /></td>
		</tr>
		
		<tr  style="display:none" id=trLDAP4>	
			<td align=right>LDAP Filter string :</td>
			<td><input type=text id=txtLDAPFString name=txtLDAPFString value="(uid=[USER_NAME])" /></td>
		</tr>
		
		<tr  style="display:none" id=trLDAP5>	
			<td align=right>LDAP Name string :</td>
			<td><input type=text id=txtLDAPNameStr name=txtLDAPNameStr value="givenname" /></td>
		</tr>
		
		<tr  style="display:none" id=trLDAP6>	
			<td align=right>LDAP Surname string :</td>
			<td><input type=text id=txtLDAPSurnameStr name=txtLDAPSurnameStr value="sn" /></td>
		</tr>
		
		<tr  style="display:none" id=trLDAP7>	
			<td align=right>LDAP login string :</td>
			<td><input type=text id=txtLDAPLoginStr name=txtLDAPLoginStr value="uid" /></td>
		</tr>
		
		<tr  style="display:none" id=trLDAP8>	
			<td align=right>LDAP email string :</td>
			<td><input type=text id=txtLDAPMailStr name=txtLDAPMailStr value="mail" /></td>
		</tr>
		
               
                
		<tr>
			<td colspan=2><br /><hr /><br /> </td>
		</tr>
		
		
		
		<tr>	
			<td align=right>Integration with PAYPAL :</td>
			<td>
				<select id=drpPaypal name=drpPaypal onchange="paypal_int()">
					<option value=1>Yes</option>
					<option value=0>No</option>					
				</select>
			</td>
		</tr>
		
		<tr  id=trP1>	
			<td align=right>Paypal seller email :</td>
			<td><input type=text id=txtPMail name=txtPMail value="" /></td>
		</tr>
		
		<tr   id=trP2>	
			<td align=right>Paypal currency :</td>
			<td><input type=text id=txtPC name=txtPC value="USD" /></td>
		</tr>
		
		<tr   id=trP3>	
			<td align=right>Paypal notify on success payment :</td>
			<td>	<select id=drpNS name=drpNS >
					<option value=1>Yes</option>
					<option value=0>No</option>					
				</select></td>
		</tr>
		
		<tr   id=trP4>	
			<td align=right>Paypal notify on failed payment :</td>
			<td	><select id=drpNF name=drpNF >
					<option value=1>Yes</option>
					<option value=0>No</option>					
				</select></td>
		</tr>
		
		<tr   id=trP5>	
			<td align=right>Paypal data name :</td>
			<td	><input type=text id=txtData name=txtData value="Load balance" /></td>
		</tr>
		
		<tr   id=trP6>	
			<td align=right>Paypal production or sandbox ? :</td>
			<td	><select id=drpPS name=drpPS >
					<option value=1>Production</option>
					<option value=0>Sandbox</option>					
				</select></td>
		</tr>
		
		<tr>
			<td colspan=2><br /><hr /><br /> </td>
		</tr>
		<tr>
			<td align=right>Your order number : </td>
			<td><input type=text name=txtOrder id=txtOrder value="" /></td>
		</tr>    
		
		<tr>
			<td colspan=2><br /><hr /><br /> </td>
		</tr>

		<tr><td colspan=2 align=center><input onclick="install()" type=button name=btnInstall id=btnInstall value="Install" style="width:150px"></td></tr>
	</table>

     </form>
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
