<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>Bonus Auth Page</title>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
	<script src='js/custom.js'></script>
</head>
<body>

<?php

session_save_path('./session/');
session_start();

if (!isset($_SESSION['name']))
{
	echo "<script>alert('first, you shall be authenticated.');</script>";
	echo "<script>document.location='./'</script>";
}
else
{
	if (isset($_GET['auth']))
	{
		include_once("conn.php");
		foreach($_GET as $key => $val)
		{
			$_GET[$key] = htmlspecialchars(stripslashes($_GET[$key]));
			$_GET[$key] = mysql_escape_string($_GET[$key]);
		}

		$auth = $_GET['auth'];

		$query = "select id, auth, used, score from $bonus_table where auth='$auth'";
		$res = mysql_query($query) or die("error");
		$result = mysql_fetch_array($res);

		if($result)
		{
			$my_team = $_SESSION['team'];
			$my_uid = $_SESSION['id'];
			$score_id = $result['id'];
			$score = $result['score'];
			$used = $result['used'];

			if ($used == 0)
			{
				$query = "update $bonus_table set used=1 where auth='$auth'";
				$res = mysql_query($query) or die("error");

				$query = "insert into $score_help(team, uid, score, by_team, bonus_id) values ($my_team, $my_uid, $score, 0, $score_id)";
				$res = mysql_query($query) or die(mysql_error());

				$query = "update score_sum set add_score=add_score+$score where team=$my_team";
				$res = mysql_query($query) or die("error");
			
				echo "<script>alert('your team gets +$score point!');</script>";
				echo "<script>document.location='./'</script>";
			}
			else
			{
				echo "<script>alert('already taken T_T');</script>";
				echo "<script>document.location='./'</script>";
			}
		}
		else
		{
			echo "<script>alert('please use proper way.');</script>";
			echo "<script>document.location='./'</script>";
		}
	}
	else 
	{
		echo "<script>alert('please use proper way.');</script>";
		echo "<script>document.location='./'</script>";
	}
}

?>

</body>
</html>
