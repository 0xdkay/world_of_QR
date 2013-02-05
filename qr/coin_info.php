<?php
if (isset($_SESSION['admin']) || isset($_SESSION['team']))
{
	include_once "conn.php";
?>
		<h3>Team Coin Information</h3>
<?php
	if (isset($_SESSION['admin']))
	{
		$query = "select team, coin, used from coin_sum order by team";
		$res = mysql_query($query) or die("ERROR");
?>
		<table style="text-align: center" class="ranktable">
			<tr>
				<th width='20px'>Team</th>
				<th width='80px'>Total</th>
				<th width='50px'>Used</th>
				<th width='50px'>Left</th>
			</tr>
<?php
		while (($result = mysql_fetch_array($res)))
		{
			$team = $result['team'];
			$coins = $result['coin'];
			$used = $result['used'];
			echo "<tr> 
							<td>$team</td>
							<td>$coins</td>
							<td>$used</td>
							<td>".($coins-$used)."</td>
						</tr>";
		}
?>
		</table><br>
<?php

		$query = "select c.team, s.name as user, v.name as victim, v.team as victim_team, c.date as time
			from $coin_table c, $user_table s, $user_table v 
			where c.uid=s.id and c.victimid=v.id
			order by c.team";
	}
	else
	{
		$my_team = $_SESSION['team'];
		$query = "select team, coin, used from coin_sum where team=$my_team";
		$res = mysql_query($query) or die("ERROR");
?>
		<table style="text-align: center" class="ranktable">
			<tr>
				<th width='20px'>Team</th>
				<th width='80px'>Total</th>
				<th width='50px'>Used</th>
				<th width='50px'>Left</th>
			</tr>
<?php
		while (($result = mysql_fetch_array($res)))
		{
			$team = $result['team'];
			$coins = $result['coin'];
			$used = $result['used'];
			echo "<tr> 
							<td>$team</td>
							<td>$coins</td>
							<td>$used</td>
							<td>".($coins-$used)."</td>
						</tr>";
		}
?>
		</table><br>
<?php

		// show only team number
		$query = "select c.team, s.name as user, v.team as victim_team, c.date as time
			from $coin_table c, $user_table s, $user_table v 
			where c.uid=s.id and c.victimid=v.id and c.team=$my_team
			order by c.team";
	}

	$res = mysql_query($query) or die ("ERROR");
?>
		<table style="text-align: center" class="ranktable">
			<tr>
				<th width='20px'>Team</th>
				<th width='100px'>User</th>
				<th width='100px'>Victim_Team</th>
				<th width='100px'>Victim</th>
				<th width='150px'>Time</th>
			</tr>
<?php
	while (($result = mysql_fetch_array($res)))
	{
		$team = $result['team'];
		$user = $result['user'];
		$victim = $result['victim'] ? $result['victim'] : "-";
		$victim_team = $result['victim_team'];
		if ($victim_team==0) $victim_team = "hidden";
		$time = $result['time'];
		echo "<tr> 
						<td>$team</td>
						<td>$user</td>
						<td>$victim_team</td>
						<td>$victim</td>
						<td>$time</td>
					</tr>";
	}
?>
		</table>
<?php

	//team info with coin
}
else
{
	echo "<script>alert('you should be authenticated first');</script>";
	echo "<script>document.location='./'</script>";
}

?>
