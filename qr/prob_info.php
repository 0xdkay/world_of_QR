<?php
if (isset($_SESSION['admin']) || isset($_SESSION['team']))
{
	include_once "conn.php";
?>
		<h3>Problem you bought</h3>
<?php
	$my_team = $_SESSION['team'];

	$query = "select a.name, a.score, b.solved, a.auth
						from $prob_table a, $prob_auth b
						where team=$my_team and a.id=b.pid";
	$res = mysql_query($query) or die("ERROR");
?>
	<table style="text-align: center" class="ranktable">
		<tr>
			<th width='100px'>Prob</th>
			<th width='50px'>GotoSolve</th>
			<th width='100px'>Score</th>
			<th width='50px'>Solved</th>
		</tr>
<?php
	while (($result = mysql_fetch_array($res)))
	{
		$prob_name = $result['name'];
		$prob_score = $result['score'];
		$prob_auth = $result['auth'];
		$prob_solved = $result['solved'] ? "Yes" : "No";
		echo "<tr> 
						<td>$prob_name</td>
						<td><a href='?mode=prob_show&auth=$prob_auth'>Go</a></td>
						<td>$prob_score</td>
						<td>$prob_solved</td>
					</tr>";
	}
?>
	</table><br>
	<hr>
	<h3>You can get more probs here:</h3>
	<ul>
	<li><b>상점</b><br>
	아침의 나라, <br>
	최고의 학자의 총애를 받았던 그는 <br>
	총명한 눈을 들어 시간과 별, <br>
	천하의 근본을 바라보았고,<br>
	그 이치를 통달하였으며 <br>
	그로써 태어나면서 짊어졌던 굴레를 벗어났다. <br>
	그가 있는 곳으로 가라<br>
	</li><br>

	<li><b>상점</b><br>
	3월 벚꽃이 아름답게 내리는, <br>
	수 많은 연인이 스쳐지나간 곳.<br>
	오늘도 한 커플이 지나간다.<br>
	남자 "오르막길 걷기 힘들다 <br>
	하지만 너와 함께라면 어디든지 갈수 있어"<br>
	여자 "으 근데 여기 새똥냄새가 너무 심해."<br>
	</li><br>

	<li><b>문제</b><br>
	우리 학교에서 땅에서 가장 가까우면서<br>
	가장 큰 KAIST SYMBOL이 있는 곳<br>
	</li><br>

	<li><b>문제</b><br>
	해가 떠오르는 지평선을 바라보라,<br>
	스파르타의 후예를 꿈꾸는 자들이 <br>
	땀흘리는 모습이 보이는가.<br>
	</li><br>

	<li><b>문제</b><br>
	전산동 근처의 가장 큰 우산<br>
	</li><br>

	<li><b>문제</b><br>
	세상을 비추는 거울, <br>
	그 옆에 신성한 형상을 가진 것의 흔적 <br>
	실제로 이는 10M가 넘는 학교내에 있는 <br>
	가장 큰 악기이다.<br>
	</li><br>

	<li><b>문제</b><br>
	그들은 항상 큰 물체를 감시한다. <br>
	그들의 감시하에 당신은 선택할 수 있다. <br>
	밖을 향할것이냐 안에 갇힐것이냐 <br>
	하지만 그들의 노련한 눈은 항상 지켜볼 것이다.<br>
	당신은 그들의 뒤로 가야 한다. <br>
	</li><br>

	</ul>
<?php
}
else
{
	echo "<script>alert('you should be authenticated first');</script>";
	echo "<script>document.location='./'</script>";
}

?>



