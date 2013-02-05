<?php
if (true)
{
	include_once "conn.php";
?>
	<h3>Team Score Information</h3>
<?php

	$rank = array();
	// TOTAL SCORE
	$query = "select team, (prob_score+add_score+penalty) as score from score_sum";
	$res = mysql_query($query) or die("ERROR");
	while (($result = mysql_fetch_array($res)))
	{
		$rank[$result['team']] = $result['score'];
	}
?>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/highcharts.js"></script>
	<script type="text/javascript" src="js/themes/grid.js"></script>

	<script type="text/javascript">
	var chart;
	var colors = Highcharts.getOptions().colors;
	var ranker_name = new Array(
	<?php 
		$str = "";
		foreach($rank as $key => $val){
			$str = $str."'$key',";
		}
		$str[strlen($str)-1] = "\n";
		echo $str;
	 ?>);

	var ranker_point = new Array(
	<?php 
		$str = "";
		$i=0;
		foreach($rank as $key => $val){
			$str = $str."{y:$val,name:'$key',color:colors[$i]},";
			$i++;
		}
		$str[strlen($str)-1] = "\n";
		echo $str;
	?>);

	var ranker_pie = new Array(
	<?php 
		$str = "";
		$i=0;
		foreach($rank as $key => $val){
			$str = $str."{y:$val,name:'$key',color:colors[$i]},";
			$i++;
		}
		$str[strlen($str)-1] = "\n";
		echo $str;
	?>);
	ranker_pie[0]['sliced'] = true;
	ranker_pie[0]['selected'] = true;


	function get_total_score() {
	 chart = new Highcharts.Chart({
	 credits: {enabled:false},
	 chart: {
		renderTo: 'container',
		defaultSeriesType: 'column',
		margin: [ 50, 50, 100, 80]
	 },
	 title: {text: 'Top 8 Rankers', style: { color: 'black' } },
	 xAxis: {categories: ranker_name, labels: {rotation: -20,align: 'right',style: { font: 'normal 15px Verdana, sans-serif', color: 'black'}}},
	 yAxis: {min: 0,title: {text: 'Point', style: {color: 'black'}}, labels: {style: {color: 'black'}}},
	 legend: {enabled: false},
	 tooltip: {formatter: function() {
		if(this.point.name){
		 return this.point.name + ' : '+ this.y + ' p';
		}else{
		 return '<b>'+ this.x +'</b><br/>' + 'Point : '+ this.y + ' p';
		}
	 }},
		plotOptions: {
		pie: {
			allowPointSelect: true,
			cursor: 'pointer',
		}
		},
	 series: [{
		name: 'point',
		data: ranker_point
	 },{
		type: 'pie',
		name : 'pie chart',
		data : ranker_pie,
		size : 100,
		center: [400,75],
		showInLegend : false,
		dataLabels: {enabled:true,color:'black'}
	 }]
	 });
	};

	</script>

	<?php 
		$week = array();
		$date = array();
		for($i = -7; $i <= 0; $i++) {
			$date[$i+7] = date("Y-m-d H:i:s", strtotime(($i*5)." minutes"));
			$week[$i+7] = date("H:i", strtotime(($i*5)." minutes"));
		}

		$weekrank = array();
		foreach($rank as $team => $team_score)
		{
			for($i=0; $i<8; $i++)
			{
				$query = "select b.team, sum(a.score) as score, b.date
									from $prob_table a, $prob_auth b
									where b.team=$team and a.id=b.pid and b.solved=1 and b.date<'$date[$i]'
									group by b.team";
				$res = mysql_query($query) or die ("error");
				$result = mysql_fetch_array($res);

				$score = $result['score'] ? $result['score'] : 0;

				$query = "select b.team, sum(b.score) as score, b.date
									from $score_help b
									where b.team=$team and b.date<'$date[$i]'
									group by b.team";
				$res = mysql_query($query) or die ("error");
				$result = mysql_fetch_array($res);

				$score += $result['score'] ? $result['score'] : 0;

				$weekrank[$team][$i] = $score;
			}
		}


	?>



	<script type="text/javascript">
	var colors = Highcharts.getOptions().colors;
	var time_table = [
	<?php 
		echo "'$week[0]'";
		for($i=1; $i<8; $i++){
			echo ",'$week[$i]'";
		}
	?>]


	var ranker_point2 = new Array(
	<?php 
		$i=0;
		$p=0;
		echo "{y:$p,name:'$week[$i]',color:colors[$i]}";
		for($i=1; $i<8; $i++){
			$p+=1000;
			echo ",{y:$p, name:'$i', color:colors[$i]}";
		}
	?>
	);

	var chart;
	function get_time_score() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container2',
				defaultSeriesType: 'line'
			},
			title: {
				text: '5 Minute Top 8 Rankers',
				style: {color: 'black'}
			},
			xAxis: {
				categories: time_table, labels: {
					style: { 
						font: 'normal 12px Verdana, sans-serif', color: 'black'
					}
				}
			},
			yAxis: {
				title: {
					text: 'Point',
					style: {
						color: 'black'
					}
				},
				labels: {
					style: {
						color: 'black'
					}
				}
				
			},
			tooltip: {formatter: function() {
					if(this.point.name){
						return this.point.name + ' : '+ this.y + ' p';
					}else{
						return '<b>'+ this.series.name +'</b>  '+this.x+'<br/>' + 'Point : '+ this.y + ' p';
					}
				}
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: false
					},
					enableMouseTracking: true
				}
			},
			series: [
			<?php
				$str = "";
				foreach($weekrank as $name => $weekscore){
					$str = $str."{name: '$name', data: [";
					foreach($weekscore as $num => $_score){
						$str = $str."$_score,";
					}
					$str[strlen($str)-1] = " ";
					$str = $str."]},";
				}
				$str[strlen($str)-1] = "\n";
				echo $str;
			?>
			]
		});
	};
	</script>

<?php
		if (date("s")%2==0)
		{

?>
			<div id="container" class="highcharts-container" style="height:500px; margin: 0; clear:both; width: 600px"></div>
			<script>get_total_score();</script>
<?php
		}
		else
		{
?>
			<div id="container2" class="highcharts-container2" style="height:500px; margin: 0; clear:both; width: 600px"></div>
			<script>get_time_score();</script>
<?php
		}
?>

<?php
}
else
{
	echo "<script>alert('you should be authenticated as admin');</script>";
	echo "<script>document.location='admin_auth.php'</script>";
}

?>
