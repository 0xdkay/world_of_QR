<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Admin Auth</title>
  <link rel="stylesheet" type="text/css" href="css/style.css" />
	<script src='js/custom.js'></script>
</head>
<body>



<?php

session_save_path('./session/');
session_start();

if (isset($_SESSION['admin']))
{
	echo "<script>alert('you already authenticated');</script>";
	echo "<script>history.back(-1);</script>";
}
else
{

	if (isset($_POST['pw']))
	{
		if (!strcmp($_POST['pw'], "qrrunn"))
		{
			session_destroy();
			session_start();

			$_SESSION['admin'] = true;
			$_SESSION['id'] = 0;
			$_SESSION['name'] = 'admin';
			$_SESSION['team'] = 0;
			echo "<script>alert('You successfully authenticated');</script>";
			echo "<script>document.location='./';</script>";
		}
		else
		{
		?>
			Wrong passowrd.
			<form method='post'>
				Password: <input type='password' name='pw' autofocus>
				<input type='submit' value='auth'>
			</form>
		<?
		}
	}
	else
	{
	?>
	This page is only for admin
	<form method='post'>
		Password: <input type='password' name='pw' autofocus>
		<input type='submit' value='auth'>
	</form>

	<?
	}
}

?>
</body>
</html>
