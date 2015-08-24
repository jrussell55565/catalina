<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: driverlogin.php');
}

include('config.inc');
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$username = $_SESSION['userid'];
#print "Here " . $_SESSION['login']."\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Refresh" CONTENT="420; URL=http://dispatch.catalinacartage.com/orders.php">
<title>HWB Not Found</title>
  <style type="text/css">
<!--
.style1 {
	font-size: 16pt;
}
-->
  </style>
  <script language="JavaScript">
  function toggle(source) {
  checkboxes = document.getElementsByName('chk_hawb[]');
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
}
</script>
<script src="./formvalidate.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/jsDatePick_ltr.css" />
<script type="text/javascript" src="jscalendar/jsDatePick.full.1.3.js"></script>
<script type="text/javascript">
			window.onload = function(){
				new JsDatePick({
					useMode:2,
					target:"beginDateExport"
				});
				new JsDatePick({
					useMode:2,
					target:"endDateExport"
				});
			};
</script>

<!doctype html>
<html lang="en-US">
<head>
<meta charset="UTF-8" />
<meta name="google-site-verification" content="Df_CpGSbb-SbsXqHJuSRIsNlNQdAFgfyYfZQfoFWauw" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta http-equiv="Refresh" CONTENT="420;
<title>Driver Login</title>
<script type="text/javascript" src="http://use.typekit.com/uzj3iee.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript">jQuery(document).ready(function($) {$(".article_title").lettering("words").fitText(0.41);});</script>

<style type="text/css" media="screen">
.banner h1{display: inline-block;}
body {
	background-color: #CCC;
}

.banner {
	position: relative;
	margin: 0 auto;
	}


::-moz-selection{text-shadow: none;background-color: #fff68c; color: #414141;}
::selection {text-shadow: none;background-color: #fff68c; color: #414141;}
body, #nav a, a.article_title, h3, a.fusiontext{color: #414141;}

.article_title  {
	position: relative;
	font-size: 50px;
	line-height: 0.8;
	font-weight: bold;
	padding:12.25% 0 10%;
	margin-left: auto;
	margin-right: auto;
	display: block;
	float: none;
}

.article blockquote p{
	padding-left: 2.25em;
	color: #666;
	background:url('http://pcdn.paravel.netdna-cdn.com/wp-content/uploads/2014/03/bq-bg.png') repeat-y;
}

hr{
	border: none;
}

hr:before{
	content:'***';
	text-align: center;
	display: block;
	margin-bottom:1.5em;
	letter-spacing:0.125em;
}

.article .footnotes{
	color: #666;
	text-indent: 0;
	margin-top: 0;
	padding: 2em 0 0;
}

@media screen and (min-width: 25.000em) { /*400px*/
	.article_title{
		width: 80%;
		max-width: 30rem;
	}

@media screen and (min-width: 50.000em) { /*800px*/
	.article_title{
		width: 70%;
		max-width: 36rem;
	}
}

@media screen and (min-width: 64.375em){ /*1030px*/
	.article_title{
		width: 60%;
		max-width: 42.5rem;
	}
}
</style>

<!-- All in One SEO Pack 2.0.4.1 by Michael Torbert of Semper Fi Web Design[235,255] -->
<meta name="keywords" content="drivers login" />
<link rel='next' href='http://www.catalinacartage.com' />

<link rel="canonical" href="http://catalinacartage.com" />
<!-- /all in one seo pack -->
<!-- /wordpress head --> 

  <script language="JavaScript">
  function toggle(source) {
  checkboxes = document.getElementsByName('chk_hawb[]');
  for(var i in checkboxes)
    checkboxes[i].checked = source.checked;
}
</script>
<script src="./formvalidate.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/jsDatePick_ltr.css" />
<script type="text/javascript" src="jscalendar/jsDatePick.full.1.3.js"></script>
<script type="text/javascript">
			window.onload = function(){
				new JsDatePick({
					useMode:2,
					target:"beginDateExport"
				});
				new JsDatePick({
					useMode:2,
					target:"endDateExport"
				});
			};
</script>


<script src="/wp-content/themes/elguapo/js/paravelplugins.js"></script>
<script>
  jQuery(document).ready(function($){
    // Target your .container, .wrapper, .post, etc.
    $(".container").fitVids();
  });
</script>

</script>
<script src="./formvalidate.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/jsDatePick_ltr.css" />
<script type="text/javascript" src="jscalendar/jsDatePick.full.1.3.js"></script>
<script type="text/javascript">
			window.onload = function(){
				new JsDatePick({
					useMode:2,
					target:"beginDateExport"
				});
				new JsDatePick({
					useMode:2,
					target:"endDateExport"
				});
			};
</script>

<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

</head>
<div id="pagewrap" class="clearfix">
  <div class="container clearfix">
    <header id="header">
      <div id="nav">
      <a title="Dispatch Board" class="tw" href="orders.php"><i data-icon="" aria-hidden="true"></i><img src="swoosh only.gif" width="100" height="10"><span class="screen-reader-text">Catalina Cartage</span></a>
      <ul>
        <form id="logout" name="logout" method="post" action="logout.php">
              <table width="212" border="1">
                <tr>
                  <td width="53" height="30"><input type=button onClick=window.open("orders.php","orders"); value="Orders"></td>
                  <td width="43"><input type=button onClick=window.open("tires.php","tires"); value="Tires"></td>
                  <td width="34"><input type=button onClick=window.open("VIR.php","vir"); value="VIR"></td>
                  <td width="54"><a title="Search" href="/search/">
                    <input type="submit" name="Log Out" id="Log Out" value="Log Out" />
                  </a></td>
                </tr>
              </table>
          </form>
          </a></li>
        </ul>
      </div>
    </header>
  </div> 
  <!-- end container -->

  <section class="clearfix">
    <div class="container">


    <article class="post-6591 post type-post status-publish format-standard hentry category-articles tag-design tag-opinion tag-responsive article" id="post-6591">
        <header class="postheader">
            <div align="center">
            <div class="banner">
            <h1>            HWB Not Found</h1>
            &quot;<?php echo "$username"; ?>&quot; Logged in
            </div> 
            </div>
        </header>
      
        <div class="grid-row centered">
<form id="SingleHWBEmail" name="SingleHWBEmail" method="post" action="email.php">
    <table width="281" border="1">
      <tr>
        <td width="255">Enter Ship Name</td>
        </tr>
      <tr>
        <td><input type="text" name="Drivertext_Shiper" id="Drivertext_Shiper" /></td>
        </tr>
      <tr>
        <td>Enter Cons Name</td>
      </tr>
      <tr>
        <td><input type="text" name="Drivertext_Consignee" id="Drivertext_Consignee" /></td>
      </tr>
      <tr>
        <td><input style="height: 25px; width: 200px" type="submit" name="btn_submit" id="btn_submit" value="Email Dispatch" />
          <input type="hidden" id="hawbsearch" name="hawbsearch" value='<?php echo "$hawbsearch";?>' /></td>
      </tr>
      </table>
</form>
<form id="ManualGetHawb" method="get" action="singlehwb.php" target="_blank">
<table width="284" border="1">
<tr>
<td width="267" border="1"><input name="bx_manualgethawb" type="text" id="bx_manualgethawb" value="" size="15"/>
  Try Searching Again??</td>
</tr>
<tr>
  <td border="1"><input type="submit" name="btn_manualgethawb" id="btn_manualgethawb" value="Search for HAWB" /></td>
</tr>
</table>
</form>
<p>&nbsp;
  <audio>
  <!-- <source src="audio/Tweeter_Sms_2011.mp3" type="audio/mpeg" /> -->
<div align='center'></div>
        </div>
    </article>
    
    <nav class="pagination"></nav>
    </div><!-- end .container -->
</section><!-- end clearfix -->
<!-- wordpress footer -->  <!-- /wordpress footer -->
  <div class="container">
    <footer id="footer">
      <div class="grid-unit"><span class="twitter"><a href="dispatch.catalinacartage.com">Drivers Login Page</a></span></div><div class="grid-unit"><span class="paravel"><a href="www.catalinacartage.com">Catalina Cartage Home</a></span></div>
    </footer>
  </div>
</div><!-- end pagewrap -->


<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-3299532-15");
pageTracker._trackPageview();
} catch(err) {}</script>
<script type="text/javascript" src="http://include.reinvigorate.net/re_.js"></script>
<script type="text/javascript">
try {
reinvigorate.track("2540w-03j8d551b7");
} catch(err) {}
</script>
<script type="text/javascript">
(function(){
  var fusion = document.createElement('script');
  fusion.src = window.location.protocol + '//adn.fusionads.net/api/1.0/ad.js?zoneid=130&rand=' + Math.floor(Math.random()*9999999);
  fusion.async = true;
  document.getElementsByTagName('head')[0].appendChild(fusion);
})();</script>

</body>
</html>
<!-- Performance optimized by W3 Total Cache.

Page Caching using disk: enhanced
Database Caching using memcached
Object Caching 492/536 objects using memcached
Content Delivery Network via pcdn.paravel.netdna-cdn.com

 Served from: catalinacartage.com @ 2014-04-27 01:18:10 by W3 Total Cache -->
