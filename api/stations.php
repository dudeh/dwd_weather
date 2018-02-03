<?php

/*

	PARAMETERS
		STATION_ID

*/

	error_reporting(E_ALL);	
	ini_set("display_errors","On");

/* connect to DB */
	$mysql_conn = new mysqli('localhost','hrodude','amQgFkKqc,RlXliNOHki','hrodude_wetter');

	if ($mysql_conn->connect_error) {
		die('Connect Error (' . $mysql_conn->connect_errno . ') '
		        . $mysql_conn->connect_error);
	}


    function get_station_details($station_id, $mysql_conn){
		$sql = "SELECT	`stationsname`
						,`longitude`
						,`latitude`
						,`elevation`
						,`land`
						,`kontinent`
				FROM `stationen`
				WHERE `stations_id` = ".$station_id;

		$result = $mysql_conn->query($sql);

		if ($result === FALSE){
			echo "Fehler beim Ausführen von ... SQL eben ...";
		}
			while($row = $result->fetch_array()) {

			$station = array("station_id" => $station_id
							,"stationsname" => $row['stationsname']
							,"latitude" => $row['longitude']
							,"longitude" => $row['latitude']
							,"elevation" => $row['elevation']
							,"country" => $row['land']
							,"continent" => $row['kontinent']
							);

		}

		return $station;
	}	//function get_station_details

	function get_stationen($mysql_conn){
		$sql = "SELECT	stationsname
						,stations_id
						,latitude
						,longitude
				FROM	stationen
				WHERE	land_kurz = 'DEU'
				ORDER BY stationsname ASC";

		$result = $mysql_conn->query($sql);

		if ($result === FALSE){
			//echo "Fehler beim Ausführen von ".$sql;
		}
		while($row = $result->fetch_array()) {
			$stationen[$row['stations_id']] = array("stationsname" => $row['stationsname']
													,"latitude" => $row['latitude']
													,"longitude" => $row['longitude']
													);
		}

		return $stationen;

	}

	
	if ( isset($_GET['station_id']) && is_numeric($_GET['station_id']) )
	{
		echo json_encode(get_station_details($_GET['station_id'], $mysql_conn));
	} else 
	{
		echo json_encode(get_stationen($mysql_conn));
	}

    $mysql_conn->close();

?>
