<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>Prob Buy Page</title>
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

		$query = "select id, name, price from $prob_table where auth='$auth'";
		$res = mysql_query($query) or die("error");
		$result = mysql_fetch_array($res);

		if($result)
		{
			$my_team = $_SESSION['team'];
			$my_uid = $_SESSION['id'];

			$prob_name = $result['name'];
			$prob_id = $result['id'];
			$prob_price = $result['price'];

			$query = "select pid from $prob_auth where pid=$prob_id and team=$my_team";
			$res = mysql_query($query) or die("error");
			$result = mysql_fetch_array($res);

			if($result)
			{
				echo "<script>alert('Your team already has $prob_name');</script>";
				echo "<script>document.location='./'</script>";
			}
			else
			{
				$query = "select coin, used from coin_sum where team=$my_team";
				$res = mysql_query($query) or die("Error");
				$result = mysql_fetch_array($res);

				$coin = $result['coin'] - $result['used'];

				if ($coin >= $prob_price)
				{
					$query = "insert into $prob_auth(team, uid, pid, solved) values ($my_team, $my_uid, $prob_id, 0)";
					$res = mysql_query($query) or die("error");

					$query = "update coin_sum set used=used+$prob_price where team=$my_team";
					$res = mysql_query($query) or die("Error");
				
					echo "<script>alert('Now you can see $prob_name');</script>";
					echo "<script>document.location='./'</script>";
				}
				else
				{
					echo "<script>alert('You need ".($prob_price-$coin)." more coins T_T');</script>";
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
