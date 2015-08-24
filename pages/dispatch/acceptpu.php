<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: driverlogin.php');
}

include('global.php');
include('functions.php');

mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$username = $_GET['username'];
$hawbnumber = $_GET['hawbnumber'];
$userid = $_GET['userid'];
$drivername = $_SESSION['drivername'];
$exportdest = $_GET['exportdest'];
$recordid = $_GET['recordid'];
$trailer = $_SESSION['trailer'];

$sql = mysql_query("select pieces,pallets from dispatch WHERE recordID=$recordid");

        while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
        {
        $pieces = $row[pieces];
        $pallets = $row[pallets];
        }

$sql = mysql_query("select FROM_UNIXTIME(arrivedShipperTime),arrivedShipperTime from dispatch WHERE recordID=$recordid");

        while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
        {
        	$arrivedTime = $row[0];
        	$arrivedTimeUnix = $row[1];
        }
	$splitArrivedTime = explode(" ",$arrivedTime);
	$duration = round((time() - $arrivedTimeUnix) / 60);
?>

<!doctype html>
<html lang="en-US">
<head>
<meta charset="UTF-8" />
<meta name="google-site-verification" content="Df_CpGSbb-SbsXqHJuSRIsNlNQdAFgfyYfZQfoFWauw" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Arrived to Shipper</title>
<script type="text/javascript" src="http://use.typekit.com/uzj3iee.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript">jQuery(document).ready(function($) {$(".article_title").lettering("words").fitText(0.41);});</script>

<style type="text/css" media="screen">
.banner h1{display: inline-block;}
body {background-color: #CCC;}

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
<script>
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement("style");
    msViewportStyle.appendChild(
        document.createTextNode(
            "@-ms-viewport{width:auto!important}"
        )
    );
    document.getElementsByTagName("head")[0].
        appendChild(msViewportStyle);
}
</script>
<script src="/wp-content/themes/elguapo/js/paravelplugins.js"></script>
<script>
  jQuery(document).ready(function($){
    // Target your .container, .wrapper, .post, etc.
    $(".container").fitVids();
  });
</script>

<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</head>
    <body onLoad="waitTimePU()">
    <body class="home blog">
<div id="pagewrap" class="clearfix">
  <div class="container clearfix">
    <header id="header">
      <div id="nav">
        <a title="Dispatch Board" class="tw" href="orders.php"><i data-icon="" aria-hidden="true"></i><img src="swoosh only.gif" width="100" height="60"><span class="screen-reader-text">Catalina Cartage</span></a>
        <ul>
          <li class="articles"><a title="Articles" href="orders.php">Home</a></li>
          <li class="notes"><a title="Notes" href="/category/notes/">Notes</a></li>
          <li class="info"><a title="Info" href="/info">Info</a></li>
          <li class="search"><a title="Search"  href="/search/">Search</a></li>
        </ul>
      </div>
    </header>
  </div> <!-- end container -->

  <section class="clearfix">
    <div class="container">


    <article class="post-6591 post type-post status-publish format-standard hentry category-articles tag-design tag-opinion tag-responsive article" id="post-6591">
        <header class="postheader">
            <div class="banner">
                <h1>Accept Pick Up</h1>
                            </div>
        </header>
      
        <div class="grid-row centered">
            <form id="arrivedtoshipper" name="arrivedtoshipper" method="post" action="export.php">
      <table width="350" border="1">
        <tr>
          <td width="239">HWB
            <input name="<?php echo constant('BX_HAWB'); ?>" type="text" id="<?php echo constant('BX_HAWB'); ?>" value="<?php echo $hawbnumber; ?>" size="12" readonly/>
              <td colspan="2">
              <td width="42">                  <td width="88" hidden="hidden"><label>
                <input name="Status" type="text" id="Status" value="Accepted PU" size="15" readonly/>
                <input type=hidden id="recordid" name="recordid" value="<?php echo "$recordid";?>" />
                <input type=hidden id="exportdest" name="exportdest" value="<?php echo "$exportdest";?>" />
                <input type=hidden id="formname" name="formname" value="<?php echo basename(__FILE__);?>" />
                </label></td>
        </tr>
        <tr>
          <td colspan="4">  <div align="center"></div>            <div align="center"><input type="submit" name="btn_sourceform" id="btn_sourceform" value="Accepted PU" /></div></td>
        </tr>
        <tr>
          <td height="-2">Trace Notes:
  <input name="<?php echo constant('BX_PUDN'); ?>" type="text" id="<?php echo constant('BX_PUDN'); ?>" value="<?php echo $drivername; ?>" size="12" /></td>
          <td height="-2" colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="5" colspan="4"><textarea name="Remarks" id="Remarks" cols="50" rows="5"></textarea></td>
        </tr>
        <tr>
          <td colspan="2" ><label><span class="">Check below if service provided:</span></label></td>
          <td width="53">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>

                <tr>
          <td colspan="3"><div align="center"><input type="submit" name="btn_sourceform" id="btn_sourceform" value="Accepted PU" /></td>
          </tr>
        </table>
</form>

  <footer class="footer-meta">
    <time pubdate></time>
  </footer>
        </p>
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
