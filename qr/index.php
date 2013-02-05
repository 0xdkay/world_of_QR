<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>World of QR</title>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>

<?php
session_save_path('./session/');
session_start();

if (isset($_SESSION['name']))
{
?>
	<script src='js/custom.js'></script>
<?
	if (isset($_SESSION['admin']))
	{
?>
	Hi, admin!<br>
	<h2 id='enroll'><a href='./?mode=enroll'>Enroll</a></h2>
<?
	}
	else
	{
		echo "hi, $_SESSION[name]";
		die("<script>alert('server is closed');</script>");
	}
?>
	<h2 id='Prob_info'><a href='./?mode=prob_info'>Problems Opened</a></h2>
	<h2 id='coin_info'><a href='./?mode=coin_info'>Coin Info</a></h2>
	<h2 id='score_detail'><a href='./?mode=score_detail'>Score Detail</a></h2>
	<h2 id='total_score'><a href='./?mode=score_graph'>Total Score</a></h2>
	<hr>
<?
	if(isset($_GET['mode']))
	{
		foreach($_GET as $key => $val)
		{
			$_GET[$key] = htmlspecialchars(stripslashes($_GET[$key]));
			$_GET[$key] = mysql_escape_string($_GET[$key]);
		}
		$mode = $_GET['mode'];
		include_once "$mode.php";
	}
	else
	{
		include_once "score_graph.php";
	}
	//team info with coin
	//user info

}
else
{
?>
	<div align='right'><a href='./admin'>admin</a></div>
	<script>alert('you should be authenticated first');</script>
	<h1>Get auth with your name tag</h1>
<?
}

?>

</body>
</html>
