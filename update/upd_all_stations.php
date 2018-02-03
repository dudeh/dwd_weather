<?php

    include(__DIR__.'/upd_one_station.php');

//loops over all stations to update FORECAST or REAL values

	error_reporting(E_ALL);	
	ini_set("display_errors","On");

/* update FORECAST or REAL values? */
	if ($_GET['type'] == 'FORECAST' || $_GET['type'] == 'REAL') {
		$type = $_GET['type'];
	} else {
		exit('Parameter fehlt oder ungültig.');
	}
    
/* connect to DB */
    $mysql_conn = new mysqli('localhost','hrodude','amQgFkKqc,RlXliNOHki','hrodude_wetter');

    if ($mysql_conn->connect_error) {
        die('Connect Error (' . $mysql_conn->connect_errno . ') '
                . $mysql_conn->connect_error);
    }

/* loop the stations */
    $sql = "SELECT `stationsname`,`stations_id`,`stat_pk` FROM `stationen` WHERE `land_kurz` = 'DEU' LIMIT 1 ";
    $result = $mysql_conn->query($sql);
    while($row = $result->fetch_array()) {
		echo	$row['stationsname']." => ";
		update_one_station($row['stations_id'] ,$row['stat_pk'] , $type);
		echo	"<br>";
	}

?>
