<?php
	define("START_YEAR", 2012);
	$distanceArray = array(200,300,400,600,1000,1200,0);

	class Club {
		var $id;
		var $name;
		var $shortName;
		var $distanceDate;

		function setClub($id, $name, $shortName){
			$this->id = $id;
			$this->name = $name;
			$this->shortName = $shortName;

			$this->distanceDate = array(array(), array(), array(), array(), array(), array());
		}

		function addDate($distance, $date){
			global $distanceArray;

			$i=0;
			while($distanceArray[$i] != 0){
				if($distanceArray[$i] == $distance){
					$this->distanceDate[$i][] = $date;
					break;
				}
				$i++;
			}
		}

		function getBRMData($date, $distanceId, &$BRMDatalist){
			global $distanceArray;

			for($j=0; $j<count($this->distanceDate[$distanceId]); $j++){
				if($this->distanceDate[$distanceId][$j] == $date){
					$BRMDatalist[] = $this->shortName . $distanceArray[$distanceId];
				}
			}
		}
	}

	class BRM {
		var $year;
		var $dateList;
		var $clubList;

		function initialize($year){
			$lastClubId = 0;
			$this->year = $year;
			$this->dateList = array();

			if($handle = fopen($year . ".txt", "r")){
				while(($line = fgetcsv($handle)) !== FALSE){
					if($line[0][0] == ';'){
						continue;
					}

					$clubId = intVal($line[0]);
					$clubName = $line[1];
					$clubShortName = $line[2];
					$distance = intVal($line[3]);
					if($lastClubId != $clubId){
						$lastClubId = $clubId;
						$club = new Club();
						$club->setClub($clubId, $clubName, $clubShortName);
						$this->clubList[] = $club;
					}
					$latestIndex = count($this->clubList)-1;
					for($i=4; $i<count($line); $i++){
						$date = intval($line[$i]);
						$this->clubList[$latestIndex]->addDate($distance, $date);
						$this->addDateList($date);
						if($distance == 600){
							$date = $this->plusDate($date);
							$this->addDateList($date);
						}
						if($distance == 1000){
							$date = $this->plusDate($date);
							$this->addDateList($date);
							$date = $this->plusDate($date);
							$this->addDateList($date);
						}
					}
				}
				fclose($handle);
			}
			sort($this->dateList);
		}

		function addDateList($date){
			if(array_search($date, $this->dateList) === false){
				$this->dateList[] = $date;
			}
		}

		function plusDate($date){
			$today = mktime(0,0,0,intVal($date/100), $date%100, $this->year);
			$tomorrow = $today + 86400;
			return intVal(date("md",$tomorrow ));
		}

		function getYear(){
			return $this->year;
		}

		function getDateCount(){
			return count($this->dateList);
		}

		function getMonth($id){
			return intval($this->dateList[$id] / 100);
		}

		function getDay($id){
			return $this->dateList[$id] % 100;
		}

		function getWeekDay($id){
			$dateValue = strtotime($this->year*10000 + $this->getMonth($id)*100 + $this->getDay($id));
			$weekDayIndex = date("w", $dateValue);
			$weekDayArray = array("日", "月", "火", "水", "木", "金", "土");
			return $weekDayArray[$weekDayIndex];
		}

		function getBRMData($date){
			global $distanceArray;

			$BRMDatalist = array();
			for($i=0; $i<count($distanceArray)-1; $i++){
				foreach($this->clubList as &$club){
					$club->getBRMData($date, $i, $BRMDatalist);
				}
			}

			return $BRMDatalist;
		}


	};

	class BRMData {
		var $brmArray;

		// 初期化
		function initialize(){
			$this->brmArray = array();

			$year=START_YEAR;
			for(;;){
				if(file_exists($year . ".txt") == false){
					break;
				}
				$brm = new BRM();
				$brm->initialize($year);
				array_push($this->brmArray, $brm);
				$year++;
			}
		}

		function getYearCount(){
			return count($this->brmArray);
		}

		function getYear($id){
			return $this->brmArray[$id]->getYear();
		}

		function getDateCount($year){
			return $this->brmArray[$year-START_YEAR]->getDateCount();
		}

		function getMonth($year, $id){
			return $this->brmArray[$year-START_YEAR]->getMonth($id);
		}

		function getDay($year, $id){
			return $this->brmArray[$year-START_YEAR]->getDay($id);
		}

		function getWeekDay($year, $id){
			return $this->brmArray[$year-START_YEAR]->getWeekDay($id);
		}

		function getBRMData($year, $date){
			return $this->brmArray[$year-START_YEAR]->getBRMData($date);
		}
	}
?>
