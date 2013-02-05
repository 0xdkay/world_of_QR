<?php
if (isset($_SESSION['admin']))
{
	include_once "conn.php";
	foreach($_POST as $key => $val)
	{
		$_POST[$key] = htmlspecialchars(stripslashes($_POST[$key]));
		$_POST[$key] = mysql_escape_string($_POST[$key]);
	}

	if (isset($_POST['name']) && isset($_POST['team']))
	{
		$name = $_POST['name'];
		$team = $_POST['team'];

		$query = "insert into $user_table(name, auth, cauth, team, used) select '$name',(select md5(rand())),(select md5(rand())), '$team', 0 from dual where not exists (select name from $user_table where name='$name');";
		$result = mysql_query($query) or die("ERROR");
		$no = mysql_affected_rows();
		if($no>0){
			echo "<script>alert('user $name enrolled as Team $team.');</script>";
		}else{
			echo "<script>alert('already exist.');</script>";
		}
	}
	else if (isset($_POST['coin']))
	{
		$name = "coin";
		$team = 0;

		$query = "insert into $user_table(name, auth, cauth, team, used) select '$name',(select md5(rand())),(select md5(rand())), '$team', 0 from dual";
		$result = mysql_query($query) or die("ERROR");
		$no = mysql_affected_rows();
		if($no>0){
			echo "<script>alert('bonus coin is inserted.');</script>";
		}else{
			echo "<script>alert('something wrong.');</script>";
		}
	}
	else if (isset($_POST['bonus']) && isset($_POST['score']))
	{
		$score = $_POST['score'];

		$query = "insert into $bonus_table(auth, used, score) select (select md5(rand())), 0, $score from dual";
		$result = mysql_query($query) or die("ERROR");
		$no = mysql_affected_rows();
		if($no>0){
			echo "<script>alert('bonus score is inserted.');</script>";
		}else{
			echo "<script>alert('something wrong.');</script>";
		}
	}
	else if (isset($_POST['prob']))
	{
		$prob = $_POST['prob'];
		$content = $_POST['content'];
		$answer = $_POST['answer'];
		$price = $_POST['price'];
		$score = $price*100;

		$query = "insert into $prob_table(name, content, answer, price, auth, score) values ('$prob', '$content', '$answer', $price, (select md5(rand())), $score);";
		$result = mysql_query($query) or die("ERROR");
		$no = mysql_affected_rows();
		if($no>0){
			echo "<script>alert('Problem is inserted');</script>";
		}else{
			echo "<script>alert('something wrong.');</script>";
		}
	}



?>
	Enroll User
	<form method='post'>
		Team: 
		<select name='team'>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
		</select><br>
		Name: <input type='text' name='name' autofocus>
		<input type='submit' value='enrol'>
	</form>

	<hr>
	Enroll Coin
	<form method='post'>
		<input type='submit' name='coin' value='enrol'>
	</form>

	<hr>
	Enroll Bonus Score
	<form method='post'>
		Score: <input type='text' name='score'>
		<input type='submit' name='bonus' value='enrol'>
	</form>

	<hr>
	Enroll Prob
	<form method='post'>
		Name: <input type='text' name='prob'><br>
		Content: <textarea type='text' name='content'></textarea><br>
		Answer: <input type='text' name='answer'><br>
		Price: <input type='text' name='price'><br>
		<input type='submit' name='bonus' value='enrol'>
	</form>
<?

}
else
{
	echo "<script>alert('you should be authenticated as admin');</script>";
	echo "<script>document.location='admin_auth.php'</script>";
}
?>

