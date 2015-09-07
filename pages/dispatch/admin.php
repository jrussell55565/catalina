<?php
session_start();
if ($_SESSION['login'] != 1)
{
	header('Location: orders.php');
}
include('global.php');
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dispatch Admin Page</title>
<style type="text/css"> 
<!-- 
body  {
	font: 100% Verdana, Arial, Helvetica, sans-serif;
	background: #666666;
	margin: 0; /* it's good practice to zero the margin and padding of the body element to account for differing browser defaults */
	padding: 0;
	text-align: center; /* this centers the container in IE 5* browsers. The text is then set to the left aligned default in the #container selector */
	color: #000000;
	background-color: #DDDDDD;
}
.twoColHybLtHdr #container {
	width: auto;  /* this will create a container 80% of the browser width */
	background: #DDDDDD;
	margin: auto; /* the auto margins (in conjunction with a width) center the page */
	border: 1px solid #000000;
	text-align: left; /* this overrides the text-align: center on the body element. */
} 
.twoColHybLtHdr #header { 
	background: #DDDDDD; 
	padding: 0 10px;  /* this padding matches the left alignment of the elements in the divs that appear beneath it. If an image is used in the #header instead of text, you may want to remove the padding. */
} 
.twoColHybLtHdr #header h1 {
	margin: 0; /* zeroing the margin of the last element in the #header div will avoid margin collapse - an unexplainable space between divs. If the div has a border around it, this is not necessary as that also avoids the margin collapse */
	padding: 10px 0; /* using padding instead of margin will allow you to keep the element away from the edges of the div */
}

/* Tips for sidebar1:
1. Since we are working in relative units, it's best not to use padding on the sidebar. It will be added to the overall width for standards compliant browsers creating an unknown actual width. 
2. Since em units are used for the sidebar value, be aware that its width will vary with different default text sizes.
3. Space between the side of the div and the elements within it can be created by placing a left and right margin on those elements as seen in the ".twoColHybLtHdr #sidebar1 p" rule.
*/
.twoColHybLtHdr #sidebar1 {
	float: left;
	width: auto; /* since this element is floated, a width must be given */
	background: #EBEBEB; /* top and bottom padding create visual space within this div  */
	padding-top: 15px;
	padding-right: 0;
	padding-bottom: 15px;
	padding-left: 0;
}
.twoColHybLtHdr #sidebar1 h3, .twoColHybLtHdr #sidebar1 p {
	margin-left: 5px; /* the left and right margin should be given to every element that will be placed in the side columns */
	margin-right: 5px;
}

/* Tips for mainContent:
1. The space between the mainContent and sidebar1 is created with the left margin on the mainContent div.  No matter how much content the sidebar1 div contains, the column space will remain. You can remove this left margin if you want the #mainContent div's text to fill the #sidebar1 space when the content in #sidebar1 ends.
2. Be aware it is possible to cause float drop (the dropping of the non-floated mainContent area below the sidebar) if an element wider than it can contain is placed within the mainContent div. WIth a hybrid layout (percentage-based overall width with em-based sidebar), it may not be possible to calculate the exact width available. If the user's text size is larger than average, you will have a wider sidebar div and thus, less room in the mainContent div. You should be aware of this limitation - especially if the client is adding content with Contribute.
3. In the Internet Explorer Conditional Comment below, the zoom property is used to give the mainContent "hasLayout." This may help avoid several IE-specific bugs.
*/
.twoColHybLtHdr #mainContent {
	width: auto;
	margin-top: 0;
	margin-right: auto;
	margin-bottom: 0;
	margin-left: auto;
} 
.twoColHybLtHdr #footer { 
	padding: 0 10px; /* this padding matches the left alignment of the elements in the divs that appear above it. */
	background:#DDDDDD;
} 
.twoColHybLtHdr #footer p {
	margin: 0; /* zeroing the margins of the first element in the footer will avoid the possibility of margin collapse - a space between divs */
	padding: 10px 0; /* padding on this element will create space, just as the the margin would have, without the margin collapse issue */
}

/* Miscellaneous classes for reuse */
.fltrt { /* this class can be used to float an element right in your page. The floated element must precede the element it should be next to on the page. */
	float: right;
	margin-left: 8px;
}
.fltlft { /* this class can be used to float an element left in your page */
	float: left;
	margin-right: 8px;
}
.clearfloat { /* this class should be placed on a div or break element and should be the final element before the close of a container that should fully contain a float */
	clear:both;
    height:0;
    font-size: 1px;
    line-height: 0px;
}
--> 
</style>
<!--[if IE]>
<style type="text/css"> 
/* place css fixes for all versions of IE in this conditional comment */
.twoColHybLtHdr #sidebar1 { padding-top: 30px; }
.twoColHybLtHdr #mainContent { zoom: 1; padding-top: 15px; }
/* the above proprietary zoom property gives IE the hasLayout it may need to avoid several bugs */
</style>
<![endif]--></head>

<body class="twoColHybLtHdr">

<div id="container">
  <div id="header">
    <h1>Admin Page</h1>
    <a href="dashboard.php">Dashboard</a> /&nbsp;<a href="orders.php">Shipments</a> /&nbsp;
  <!-- end #header --><a href="accessorials.php">Accessorials </a>/&nbsp;
    <a href="location.php">DriverLocation</a> /&nbsp;
  <a href="logout.php">Logout</a></div>
  <div id="mainContent">
    <table width="878" border="1" id="Users">
      <tr>
          <td width="72">Driver Name</td>
          <td width="72">Login</td>
          <td width="72">Pass</td>
          <td>Email</td>
          <td>AltE</td>
          <td width="120">V Text</td>
          <td width="34">AltV</td>
          <td width="144">Driver ID</td>
          <td width="51">Admin</td>
          <td width="38">Add</td>
          <td width="281">Delete</td>
      </tr>
        <?php
		$result = mysql_query("SELECT * FROM users ORDER BY drivername");
		$counter = 0;
		while ($row = mysql_fetch_array($result, MYSQL_BOTH)) 
		{
			echo "<form id=\"usermanagement\" name=\"usermanagement\" method=\"post\" action=\"useractions.php\">\n";
			echo "<tr>\n";
			echo "<td><label>\n";
			echo "<input name=\"driver_name\" type=\"text\" id=\"driver_name\" value=\"$row[drivername]\"/>\n";
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_login\" type=\"text\" id=\"driver_login\" value=\"$row[username]\" />\n";
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_password\" type=\"text\" id=\"driver_password\" value=\"$row[password]\" />\n";
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_email\" type=\"text\" id=\"driver_email\" size=\"40\" value=\"$row[email]\" />\n";
			echo "<td><label>\n";
			echo "<input type=\"checkbox\" name=\"ck_emailupdate\" id=\"ck_emailupdate\" "; 
			if ($row[emailupdate] == "1")
			{		
				echo "checked />\n";
			}else{
				echo "/>\n";
			}
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_vtext\" type=\"text\" id=\"driver_vtext\" size=\"40\" value=\"$row[vtext]\" />\n";
			echo "<td><label>\n";
			echo "<input type=\"checkbox\" name=\"ck_vtextupdate\" id=\"ck_vtextupdate\" "; 
			if ($row[vtextupdate] == "1")
			{		
				echo "checked />\n";
			}else{
				echo "/>\n";
			}
			echo "</label></td>\n";
			echo "<td><label>\n";
			echo "<input name=\"driver_ID\" type=\"text\" id=\"driver_ID\" value=\"$row[driverid]\" />\n";
			echo "</label></td>\n";
			
			echo "<td><label>\n";
			echo "<input name=\"driver_admin\" type=\"checkbox\" id=\"driver_admin\" ";
			// Get admin checkbox status
			if ($row[admin] == "1")
			{		
				echo "checked />\n";
			}else{
				echo "/>\n";
			}
			echo "</label></td>\n";

			echo "<td>\n";
			echo "<input name=\"btn_submit\" value=\"Update\" type=\"submit\" id=\"btn_submit\" />\n";
			echo "<td><label>\n";
			echo "<input name=\"btn_submit\" value=\"Delete\" type=\"submit\" id=\"btn_submit\" />\n";
			echo "</td>\n";
			
			$counter++;
			echo "</form>\n";
			echo "</tr>\n";
			
		}
		?>
        <form id="usermanagement_add" name="usermanagement_add" method="post" action="useractions.php">
			<tr>
			<td><label>
			<input name="driver_name" type="text" id="driver_name" size="12" />
			</label></td>
			
			<td><label>
			<input name="driver_login" type="text" id="driver_login" size="12"  />
			</label></td>
			
			<td><label>
			<input name="driver_password" type="text" id="driver_password" size="12"/>
			</label></td>
			
			<td width="120"><label>
			<input name="driver_email" type="text" id="driver_email" size="20" />
			</label></td>
			<td width="33"><input type="checkbox" name="ck_emailupdate" id="ck_emailupdate" /></td>
			
			<td><label>
			<input name="driver_vtext" type="text" id="driver_vtext" size="20" />
			</label></td>
			<td><input type="checkbox" name="ck_vtextupdate" id="ck_vtextupdate" /></td>

			<td><label>
			  <input name="driver_ID" type="text" id="driver_ID" size="20"  />
			  </label></td>
			
			<td><label>
			<input name="driver_admin" type="checkbox" id="driver_admin" />
			</label></td>
			
			<td>
			<input name="btn_submit" value="Add" type="submit" id="btn_submit" /></td>
			<td>&nbsp;</td>
	  </form>
			</tr>
    </table>
      <form id="usermanagement_vtext" name="usermanagement_vtext" method="post" action="vtextupdate.php">
      <?php
	  $data = file_get_contents(constant('VTEXTFILE'));
	  ?>
      <table width="781" height="86" border="1" id="Options">
        <tr>
          <td width="574" height="22">VText Message</td>
          <td width="191">&nbsp;</td>
        </tr>
        <tr>
          <td><textarea name="vtext_comments" id="vtext_comments" cols=100 rows=1><?php echo $data;?></textarea></td>
          <td width="191"><input type="submit" /></td>
        </tr>
      </table>
    </form>
<form id="broadcast_vtext" name="broadcast_vtext" method="post" action="vtextbroadcast.php">
      <table width="780" border="1" id="Broadcast">
        <tr>
          <td width="574">VText Broadcast</td>
          <td width="190">&nbsp;</td>
        </tr>
        <tr>
          <td height="53"><textarea name="vtext_broadcast" id="vtext_broadcast" cols=100 rows=1></textarea></td>
          <td><input type="submit" /></td>
        </tr>
      </table>
    </form>

    <h2>&nbsp;<?php if (isset($_SESSION['dberror'])) { $error = $_SESSION['dberror']; echo "$error\n"; } ?></h2>
  <!-- end #mainContent --></div>
<!-- This clearing element should immediately follow the #mainContent div in order to force the #container div to contain all child floats -->
<div id="footer">
    <p>
      <!-- end #footer -->
    </p>
  </div>
<!-- end #container --></div>
</body>
</html>
