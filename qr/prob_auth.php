<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <title>Prob Auth Page</title>
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
	if (isset($_GET['auth']) && isset($_POST['answer']))
	{
		include_once("conn.php");
		foreach($_GET as $key => $val)
		{
			$_GET[$key] = htmlspecialchars(stripslashes($_GET[$key]));
			$_GET[$key] = mysql_escape_string($_GET[$key]);
		}
		foreach($_POST as $key => $val)
		{
			$_POST[$key] = htmlspecialchars(stripslashes($_POST[$key]));
			$_POST[$key] = mysql_escape_string($_POST[$key]);
		}

		$my_team = $_SESSION['team'];
		$my_uid = $_SESSION['id'];
		$auth = $_GET['auth'];
		$answer = $_POST['answer'];

		$query = "select a.id, a.name, a.score, a.auth, a.answer, b.solved
							from $prob_table a, $prob_auth b
							where team=$my_team and a.id=b.pid and a.auth='$auth'";
		$res = mysql_query($query) or die("ERROR");
		$result = mysql_fetch_array($res);

		if($result)
		{
			$prob_id = $result['id'];
			$prob_name = $result['name'];
			$prob_score = $result['score'];
			$prob_answer = $result['answer'];
			$prob_solved = $result['solved'];


			if (!strcmp($answer, $prob_answer))
			{
				if ($prob_solved)
				{
					echo "<script>alert('Your team already solved $prob_name');</script>";
					echo "<script>document.location='./'</script>";
				}
				else
				{
					$query = "update $prob_auth set solved=1, solved_uid=$my_uid where pid=$prob_id and team=$my_team";
					$res = mysql_query($query) or die("error");

					$query = "update score_sum set prob_score=prob_score+$prob_score where team=$my_team";
					$res = mysql_query($query) or die("error");

					echo "<script>alert('You solved $prob_name and gets +$prob_score points');</script>";
					echo "<script>document.location='./'</script>";
				}
			}
			else
			{
				echo "<script>alert('wrong answer.');</script>";
				echo "<script>history.back(-1);</script>";
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
