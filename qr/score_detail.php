<?php
if (isset($_SESSION['admin']) || isset($_SESSION['team']))
{
	include_once "conn.php";
?>
		<h3>Team Score Information</h3>
<?php
	if (isset($_SESSION['admin']))
	{
		$query = "select team, prob_score, add_score, penalty from score_sum order by team";
		$res = mysql_query($query) or die ("ERROR");
?>
		<table style="text-align: center" class="ranktable">
			<tr>
				<th width='20px'>Team</th>
				<th width='80px'>Prob</th>
				<th width='80px'>Bonus</th>
				<th width='80px'>Penalty</th>
				<th width='80px'>Sum</th>
			</tr>
<?php
			while (($result = mysql_fetch_array($res)))
			{
				$team_name = $result['team'];
				$prob_score = $result['prob_score'];
				$add_score = $result['add_score'];
				$penalty = $result['penalty'];

				echo "<tr>
								<td>$team_name</td>
								<td>$prob_score</td>
								<td>$add_score</td>
								<td>$penalty</td>
								<td>".($prob_score+$add_score+$penalty)."</td>
							</tr>";
			}
?>
		</table><br>
<?php


		$query = "select b.team, a.name, a.score, c.name as user, b.date
							from $prob_table a, $prob_auth b, $user_table c
							where a.id=b.pid and b.solved_uid=c.id and b.solved=1";
	}
	else
	{
		$my_team = $_SESSION['team'];

		$query = "select team, prob_score, add_score, penalty from score_sum where team=$my_team";
		$res = mysql_query($query) or die ("ERROR");
?>
		<table style="text-align: center" class="ranktable">
			<tr>
				<th width='20px'>Team</th>
				<th width='80px'>Prob</th>
				<th width='80px'>Bonus</th>
				<th width='80px'>Penalty</th>
				<th width='80px'>Total</th>
			</tr>
<?php
			while (($result = mysql_fetch_array($res)))
			{
				$team_name = $result['team'];
				$prob_score = $result['prob_score'];
				$add_score = $result['add_score'];
				$penalty = $result['penalty'];

				echo "<tr>
								<td>$team_name</td>
								<td>$prob_score</td>
								<td>$add_score</td>
								<td>$penalty</td>
								<td>".($prob_score+$add_score+$penalty)."</td>
							</tr>";
			}
?>
		</table><br>
<?php
		$query = "select b.team, a.name, a.score, c.name as user, b.date
							from $prob_table a, $prob_auth b, $user_table c
							where b.team=$my_team and a.id=b.pid and b.solved_uid=c.id and b.solved=1";
	}
	$res = mysql_query($query) or die ("ERROR");
?>
		<table style="text-align: center" class="ranktable">
			<tr>
				<th width='20px'>Team</th>
				<th width='100px'>Prob</th>
				<th width='100px'>Score</th>
				<th width='100px'>Solved_by</th>
				<th width='150px'>Time</th>
			</tr>
<?php
	while (($result = mysql_fetch_array($res)))
	{
		$team_name = $result['team'];
		$prob_name = $result['name'];
		$prob_score = $result['score'];
		$solved_by = $result['user'];
		$solved_date = $result['date'];

		echo "<tr>
						<td>$team_name</td>
						<td>$prob_name</td>
						<td>$prob_score</td>
						<td>$solved_by</td>
						<td>$solved_date</td>
					</tr>";
	}

	if (isset($_SESSION['admin']))
	{
		$query = "select a.team, a.score, c.name as user, a.date, a.by_team
							from $score_help a, $bonus_table b, $user_table c
							where a.bonus_id=b.id and a.uid=c.id";
	}
	else
	{
		$my_team = $_SESSION['team'];

		$query = "select a.team, a.score, c.name as user, a.date, a.by_team
							from $score_help a, $bonus_table b, $user_table c
							where a.team=$my_team and a.bonus_id=b.id and a.uid=c.id";
	}
	$res = mysql_query($query) or die ("ERROR");

	while (($result = mysql_fetch_array($res)))
	{
		$team_name = $result['team'];
		$prob_name = "bonus";
		$prob_score = $result['score'];
		$solved_by = $result['user'];
		$solved_date = $result['date'];

		echo "<tr>
						<td>$team_name</td>
						<td>$prob_name</td>
						<td>$prob_score</td>
						<td>$solved_by</td>
						<td>$solved_date</td>
					</tr>";
	}

?>
		</table><br>
<?php
	if (isset($_SESSION['admin']))
	{
		$query = "select a.team, b.name, a.score, a.by_team, a.date
							from $score_help a, $user_table b
							where a.uid=b.id and a.score<0 order by a.team";
	}
	else
	{
		$query = "select a.team, b.name, a.score, a.by_team, a.date
							from $score_help a, $user_table b
							where a.team=$my_team and a.uid=b.id and a.score<0";
	}
	$res = mysql_query($query) or die ("ERROR");
?>
		<table style="text-align: center" class="ranktable">
			<tr>
				<th width='20px'>Team</th>
				<th width='100px'>User</th>
				<th width='100px'>Penalty</th>
				<th width='100px'>Taken_by</th>
				<th width='150px'>Time</th>
			</tr>
<?php
	while (($result = mysql_fetch_array($res)))
	{
		$team_name = $result['team'];
		$victim = $result['name'];
		$penalty = $result['score'];
		$taken_by = $result['by_team'];
		$date = $result['date'];

		echo "<tr>
						<td>$team_name</td>
						<td>$victim</td>
						<td>$penalty</td>
						<td>Team $taken_by</td>
						<td>$date</td>
					</tr>";
	}



?>
		</table>
<?php
}
else
{
	echo "<script>alert('you should be authenticated as admin');</script>";
	echo "<script>document.location='admin_auth.php'</script>";
}

?>
