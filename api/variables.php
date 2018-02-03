<?php

/*

	PARAMETERS
		real			0=exclude real variables, 1=include
        forecast        0=exclude forecast variables, 1=include
        only_unified    0=return all, 1=return only unified

*/

	error_reporting(E_ALL);	
	ini_set("display_errors","On");

/* connect to DB */
	$mysql_conn = new mysqli('localhost','hrodude','amQgFkKqc,RlXliNOHki','hrodude_wetter');

	if ($mysql_conn->connect_error) {
		die('Connect Error (' . $mysql_conn->connect_errno . ') '
		        . $mysql_conn->connect_error);
	}

	function get_variables($real, $forecast, $only_unified, $mysql_conn){
		$sql = "SELECT IFNULL(`beschreibung_extern`,`beschreibung`) `description`
						,`code`
						,`union_code`
						,`einheit`		`unit`
						,`type`			`real_or_forecast`
				FROM `messgroessen`		
				WHERE 1=1 ";
		$sql_type_in = " AND `type` IN ('#DUMMY#' ";		
		if ($real == 1){
			$sql_type_in = $sql_type_in.",'REAL'";
		}
		if ($forecast == 1){
			$sql_type_in = $sql_type_in.",'FORECAST'";
		}
		$sql_type_in = $sql_type_in.") ";

		$sql_unified = " ";		
		if ($only_unified = 1){
			$sql_unified = " AND `union_code` IS NOT NULL";
		}

		$sql = $sql . $sql_type_in . $sql_unified;

		$result = $mysql_conn->query($sql);

		if ($result === FALSE){
			echo "Fehler beim Ausführen von ".$sql;
		}
        $variables = array();
		while($row = $result->fetch_array()) {
			$variables[$row['code']] = array("description" => $row['description']
											,"union_code" => $row['union_code']
											,"unit" => $row['unit']
											,"real_or_forecast" => $row['real_or_forecast']
											);
		}

		return $variables;
	}


	$real = 1;
	$forecast = 1;
	$only_unified = 0;
	
	if ( isset($_GET['real']) && is_numeric($_GET['real']) 
		&& ($_GET['real'] == 0 || $_GET['real'] == 1) )
	{
		$real = $_GET['real'];
	}

	if ( isset($_GET['forecast']) && is_numeric($_GET['forecast']) 
		&& ($_GET['forecast'] == 0 || $_GET['forecast'] == 1) )
	{
		$forecast = $_GET['forecast'];
	}


	if ( isset($_GET['only_unified']) && is_numeric($_GET['only_unified']) 
		&& ($_GET['only_unified'] == 0 || $_GET['only_unified'] == 1) )
	{
		$only_unified = $_GET['only_unified'];
	}
    //echo "yeah";
	echo json_encode(get_variables($real, $forecast, $only_unified, $mysql_conn));

    $mysql_conn->close();

?>
