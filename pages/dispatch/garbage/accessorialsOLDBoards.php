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
body {
	font: 100% Verdana, Arial, Helvetica, sans-serif;
	background: #666666;
	margin: 0; /* it's good practice to zero the margin and padding of the body element to account for differing browser defaults */
	padding: 0;
	text-align: center; /* this centers the container in IE 5* browsers. The text is then set to the left aligned default in the #container selector */
	color: #000000;
}
.twoColHybLtHdr #container {
	width: 100%;  /* this will create a container 80% of the browser width */
	background: #FFFFFF;
	margin: 0 auto; /* the auto margins (in conjunction with a width) center the page */
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
<![endif]-->
</head>

<body class="twoColHybLtHdr">
<div id="container">
  <div id="header">
    <h1>Accessorials Database</h1>
    <a href="logout.php">Logout</a>&nbsp;<a href="orders.php">Orders</a> 
    <!-- end #header --><a href="accessorials.php">Accessorials</a></div>
  <div id="mainContent">
    <table width="662" border="1" id="Users">
      <tr>
        <td width="201">Revenue Charge Name</td>
        <td width="91">Type</td>
        <td width="165">Rev Charge Amount</td>
        <td width="83">Box, txt, or hidden</td>
        <td width="83">Page</td>
        <td width="188">Options</td>
      </tr>
      <form id="accessorials_add" name="accessorials_add" method="post" action="accessorialactions.php">
        <tr>
          <td><input name="revenue_charge" type="text" id="revenue_charge" size="30" /></td>
          <td><label for="revenue_charge"></label>
            <select name="acc_type" id="acc_type">
              <option selected="selected">PU</option>
              <option>DEL</option>
              <option>REVENUE</option>
            </select></td>
          <td><input name="revenue_amount" type="text" id="revenue_amount"/></td>
          <td><select name="checkortext" id="checkortext">
              <option selected="selected">Check Box</option>
              <option>Text Field</option>
              <option>Hidden Field</option>
            </select></td>
          <td><select name="srcPage" id="srcPage">
              <option selected="selected"></option>
              <option>puconfirmed.php</option>
              <option>delconfirmed.php</option>
              <option>TrailerDroppedPU.php</option>
              <option>TrailerDroppedDEL.php</option>
              <option>ArrivedtoConsignee.php</option>
              <option>AttemptDEL.php</option>
              <option>Refused.php</option>
              <option>AttemptPU.php</option>
              <option>ArrivedtoShipper.php</option>
            </select></td>
          <td><input name="btn_submit" value="Add" type="submit" id="btn_submit" /></td>
      </form>
      <?php
                $result = mysql_query("SELECT * FROM accessorials ORDER BY acc_type,revenue_charge");
                $counter = 0;
                while ($row = mysql_fetch_array($result, MYSQL_BOTH))
                {
	?>
      <form id="accessorials_del" name="accessorials_del" method="post" action="accessorialactions.php">
        <tr>
          <td><input type="hidden" name="acc_itemnum" value="<?php echo "$row[acc_itemnum]";?>">
            <input name="revenue_charge" type="text" id="revenue_charge" size="30" value="<?php echo "$row[revenue_charge]";?>"></td>
          <td><label for="revenue_charge"></label>
            <select name="acc_type" id="acc_type">
              <?php
			switch ($row['acc_type'])
			{
			case "DEL"
			?>
              <option>PU</option>
              <option>REVENUE</option>
              <option selected="selected">DEL</option>
              <?php
			break;
			case "PU"
			?>
              <option>DEL</option>
              <option>REVENUE</option>
              <option selected="selected">PU</option>
              <?php
			break;
			case "REVENUE"
			?>
              <option>DEL</option>
              <option>PU</option>
              <option selected="selected">REVENUE</option>
              <?php
			break;
			}
			?>
            </select></td>
          <td><label>
              <input name="revenue_amount" type="text" id="revenue_amount" value=<?php echo "$row[revenue_amount]";?>>
            </label></td>
          <td><select name="checkortext" id="checkortext">
              <?php
			if (preg_match('/^ck_/',$row['input_type']))
			{ ?>
              <option selected="selected">Check Box</option>
              <option >Text Field</option>
              <option >Hidden Field</option>
              <?php
			}elseif (preg_match('/^bx_/',$row['input_type'])){
			?>
              <option selected="selected">Text Field</option>
              <option >Check Box</option>
              <option >Hidden Field</option>
              <?php
			}else{
			?>
              <option selected="selected">Hidden Field</option>
              <option >Text Field</option>
              <option >Check Box</option>
              <?php } ?>
            </select></td>
          <td><select name="src_page" id="src_page">
              <option selected="selected"><?php echo $row['src_page'];?></option>
              <option>puconfirmed.php</option>
              <option>delconfirmed.php</option>
              <option>TrailerDroppedPU.php</option>
              <option>TrailerDroppedDEL.php</option>
              <option>ArrivedtoConsignee.php</option>
              <option>AttemptDEL.php</option>
              <option>Refused.php</option>
              <option>AttemptPU.php</option>
              <option>ArrivedtoShipper.php</option>
            </select></td>
          <td><input name="btn_submit" value="Delete" type="submit" id="btn_submit" />
            <input name="btn_submit" value="Update" type="submit" id="btn_submit" /></td>
      </form>
      <?php
		}
	?>
    </table>
    <h2>&nbsp;
      <?php if (isset($_SESSION['dberror'])) { $error = $_SESSION['dberror']; echo "$error\n"; } ?>
    </h2>
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
