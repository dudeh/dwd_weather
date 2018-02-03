<?php

/*

	PARAMETERS
		EMPTY				returns list of stations
		STATION_ID			without UNIXTIME_FROM/TO specified forecast
		UNIXTIME_FROM		for specific values (real and forecast)
		UNIXTIME_TO			for specific values (real and forecast)

*/

	error_reporting(E_ALL);	
	ini_set("display_errors","On");

/* connect to DB */
	$mysql_conn = new mysqli('002.mysql.db.fge.5hosting.com','u388_wetter','kernel2355pause','db388_wetter');

	if ($mysql_conn->connect_error) {
		die('Connect Error (' . $mysql_conn->connect_errno . ') '
		        . $mysql_conn->connect_error);
	}

    function get_messwerte($station_id, $datum, $zeit, $type, $mysql_conn){
		$sql = "SELECT DISTINCT
						m.wert
						,g.einheit
						,g.beschreibung
						,g.code
				FROM	messwerte m
						,messgroessen g
				WHERE	m.messgroesse_fk = g.mess_pk
				AND		m.station_fk = ".$station_id."
				AND		datum = '".$datum."'
				AND		zeit >= '".$zeit."'
				AND		m.type = 'FORECAST'
				ORDER BY g.mess_pk";
		//echo $sql;
		$result = $mysql_conn->query($sql);
		$messwerte = array();
		if ($result === FALSE){
			//echo "Fehler beim Ausführen von ".$sql;
		}
		while($row = $result->fetch_array()) {
			$messwerte[$row['code']] = array("wert" => $row['wert'],
											"einheit" => $row['einheit'],
											"beschreibung" => $row['beschreibung']
											);
		}

		return $messwerte;
    }

    function get_zeiten($station_id, $mysql_conn){
		$sql = "SELECT DISTINCT
						m.datum
						,m.zeit
				FROM	messwerte m
						,messgroessen g
				WHERE	m.messgroesse_fk = g.mess_pk
				AND		m.station_fk = ".$station_id."
				AND		datum >= CURRENT_DATE
				AND		zeit >= CURRENT_TIME
				AND		m.type = 'FORECAST'
				ORDER BY datum ASC, zeit ASC";

		$result = $mysql_conn->query($sql);
		$zeiten = array();
		if ($result === FALSE){
			//echo "Fehler beim Ausführen von ".$sql;
		}
		while($row = $result->fetch_array()) {

			$zeiten[$row['datum']." ".$row['zeit']] = get_messwerte($station_id, $row['datum'], $row['zeit'], 'FORECAST', $mysql_conn);
			//echo $row['datum']." ".$row['zeit']."<br>";
		}

		return $zeiten;
    
	}

	function get_stationen($mysql_conn){
		$sql = "SELECT	stationsname
						,stations_id
						,latitude
						,longitude
				FROM	stationen
				WHERE	land_kurz = 'DEU'
				ORDER BY stationsname ASC";

		$result = $mysql_conn->query($sql);
		$stationen = array();
		if ($result === FALSE){
			//echo "Fehler beim Ausführen von ".$sql;
		}
		while($row = $result->fetch_array()) {
			$stationen[$row['stations_id']] = array("stationsname" => $row['stationsname'],
													,"latitude" => $row['latitude'],
													,"longitude" => $row['longitude']
													);
		}

		return $stationen;

	}

	if ( (isset($_GET['station_id'] && is_numeric($_GET['station_id'])) ||
		 (isset($_GET['STATIONSNAME']
		){

	} else {
		echo json_encode(get_stationen($mysql_conn));
	}

    echo "fertig";
    $mysql_conn->close();

?>
