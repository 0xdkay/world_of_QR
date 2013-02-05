<?php
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
		$my_team = $_SESSION['team'];
		$my_uid = $_SESSION['id'];

		$query = "select a.name, a.content, a.score, a.auth, b.solved
							from $prob_table a, $prob_auth b
							where team=$my_team and a.id=b.pid and a.auth='$auth'";
		$res = mysql_query($query) or die("error");
		$result = mysql_fetch_array($res);

		if($result)
		{

			$prob_name = $result['name'];
			$prob_content = $result['content'];
			$prob_auth = $result['auth'];
			$prob_score = $result['score'];
			$prob_solved = $result['solved'];

			echo "<h3>$prob_name - $prob_score points</h3>";
			if ($prob_solved)
				echo "Solved!";
			else
				echo "Not Solved.";
			echo "<br>";
			echo "<br>";
			echo nl2br($prob_content);
			echo "<br>";
			echo "<br>";
?>
		<form method='post' action='prob_auth?auth=<?php echo $prob_auth;?>'>
			Answer: <input type='text' name='answer' autofocus>
			<input type='submit' value='auth'>
		</form>
<?php
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

