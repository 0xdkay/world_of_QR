<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>Coin Auth Page</title>
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
	if (isset($_GET['cauth']))
	{
		include_once("conn.php");
		foreach($_GET as $key => $val)
		{
			$_GET[$key] = htmlspecialchars(stripslashes($_GET[$key]));
			$_GET[$key] = mysql_escape_string($_GET[$key]);
		}

		$cauth = $_GET['cauth'];

		$query = "select id, name, cauth, team, used from $user_table where cauth='$cauth'";
		$res = mysql_query($query) or die("error");
		$result = mysql_fetch_array($res);

		if($result)
		{
			$name = $result['name'];
			$my_team = $_SESSION['team'];
			$my_uid = $_SESSION['id'];
			$victim_id = $result['id'];
			$used = $result['used'];
			$victim_team = $result['team'];

			if ($my_team == $result['team'])
			{
				echo "<script>alert('friendly fire is not allowed :/');</script>";
				echo "<script>document.location='./'</script>";
			}
			else
			{
				if ($victim_team > 0) //if user coin is taken
				{
					$query = "insert into $score_help(team, uid, score, by_team, bonus_id) 
										select $victim_team, $victim_id, -10, $my_team, 0
										from dual
										where not exists (select team from $score_help
																									where uid=$victim_id and by_team=$my_team);";
					$res = mysql_query($query) or die("ERROR");
					$no = mysql_affected_rows();
					if ($no>0)
					{
						$query = "update score_sum set penalty=penalty-10 where team=$victim_team";
						$res = mysql_query($query) or die("ERROR");
					}
				}

				if ($used == 0)
				{
					$query = "update $user_table set used=1 where name='$name' and cauth='$cauth'";
					$res = mysql_query($query) or die("error");

					$query = "update coin_sum set coin=coin+1 where team=$my_team";
					$res = mysql_query($query) or die("error");

					$query = "insert into $coin_table(team, uid, victimid) values ($my_team, $my_uid, $victim_id)";
					$res = mysql_query($query) or die("error");

				
					echo "<script>alert('your team gets +1 coin!');</script>";
					echo "<script>document.location='./'</script>";
				}
				else
				{
					echo "<script>alert('already taken T_T');</script>";
					echo "<script>document.location='./'</script>";
				}
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
