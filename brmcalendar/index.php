<?php
	include 'brmdata.php';

	$brm = new BRMData();
	$brm->initialize();
	$yearCount = $brm->getYearCount();
	if($yearCount == 0){
		exit;
	}
	$year = $brm->getYear(0);
	$dayCount = $brm->getDateCount($year);
?>
<!doctype html>
<html manifest="cache.manifest">
<head>
<meta charset="utf-8">
<meta name="description" content="BRM Calendar">
<meta name="author" content="MM">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="apple-touch-icon" href="icon.png" />
<link rel="stylesheet" href="index.css">
<style type='text/css'>
<?php
	for($i=0; $i<$dayCount; $i++){
		$top = $i*26+6;
		echo "#pos$i {position:absolute; top:" . $top . "px; left:55px;}\n";
	}
?>
</style>
<script type="text/javascript">
	function logEvent(event) {
		console.log(event.type);
	}
	window.applicationCache.addEventListener('checking',logEvent,false);
	window.applicationCache.addEventListener('noupdate',logEvent,false);
	window.applicationCache.addEventListener('downloading',logEvent,false);
	window.applicationCache.addEventListener('cached',logEvent,false);
	window.applicationCache.addEventListener('updateready',logEvent,false);
	window.applicationCache.addEventListener('obsolete',logEvent,false);
	window.applicationCache.addEventListener('error',logEvent,false);
</script>
<title>BRM Calendar</title>
</head>
<body>
<div id="calendar">
<table>
<?php
	for($i=0; $i<$dayCount; $i++){
		$month = $brm->getMonth($year, $i);
		$day = $brm->getDay($year, $i);
		$weekDay = $brm->getWeekDay($year, $i);

		if($weekDay == "土"){
			echo "<tr class=\"sat\">\n";
		}
		else if($weekDay == "日"){
			echo "<tr class=\"holiday\">\n";
		}
		else{
			echo "<tr class=\"other\">\n";
		}
		echo "<td class=\"date\">$month</td>\n";
		echo "<td class=\"date\">$day</td>\n";
		echo "<td class=\"date\">$weekDay</td>\n";
		echo "<td class=\"brm\"></td>\n";
		echo "</tr>\n";
	}
?>
</table>
</div>
<?php
	$prevBRMData = array();
	for($i=0; $i<$dayCount; $i++){
		$month = $brm->getMonth($year, $i);
		$day = $brm->getDay($year, $i);

		echo "<div id=\"pos$i\">\n";
		$BRMData = $brm->getBRMData($year, $month*100+$day);
		for($j=0; $j<count($prevBRMData); $j++){
			if($prevBRMData[$j] > 0){
				$prevBRMData[$j]--;
			}
		}
		for($j=0, $k=0; $j<count($BRMData); $j++, $k++){
			while($prevBRMData[$k] > 0){
				echo "<span class=\"empty\"></span>\n";
				$k++;
			}
			foreach($distanceArray as &$distance){
				if($distance == 0){
					break;
				}
				if(strstr($BRMData[$j], "" . $distance)){
					echo "<span class=\"event d$distance\">$BRMData[$j]</span>\n";

					if($distance==1000){
						$prevBRMData[$k] += 3;
					}
					elseif($distance==600){
						$prevBRMData[$k] += 2;
					}
					else{
						$prevBRMData[$k] += 1;
					}
					break;
				}
			}
		}
		echo "</div>\n";
	}
?>
</body>
</html>
