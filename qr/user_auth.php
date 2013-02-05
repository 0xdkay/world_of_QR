<?php
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>User Auth Page</title>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
	<script src='js/custom.js'></script>
</head>
<body>


<?php

session_save_path('./session/');
session_start();
session_destroy();
session_start();


if (isset($_SESSION['name']))
{
	echo "<script>alert('you already authenticated as $_SESSION[name] for Team $_SESSION[team]');</script>";
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

		$query = "select id, name, auth, team from $user_table where auth='$auth'";
		$res = mysql_query($query) or die("ERROR");
		$result = mysql_fetch_array($res);

		if($result)
		{
			$_SESSION['id'] = $result['id'];
			$_SESSION['name'] = $result['name'];
			$_SESSION['auth'] = $auth;
			$_SESSION['team'] = $result['team'];

			echo "<script>alert('you are successfully authenticated');</script>";
			echo "<script>document.location='./'</script>";
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
