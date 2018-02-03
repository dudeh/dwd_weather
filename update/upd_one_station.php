<?php

//updates FORECAST or REAL values for given station

	error_reporting(E_ALL);	
	ini_set("display_errors","On");

/*
	if ($_GET['station_id'] != NULL && ($_GET['type'] == 'FORECAST' || $_GET['type'] == 'REAL' )) {
		$station_id = $_GET['station_id'];
		$type = $_GET['type'];
	} else {
		exit('Parameter fehlt oder ungültig.');
	}
*/

    function update_one_station($station_id, $stations_pk, $type) {

        $mysql_conn = new mysqli('localhost','hrodude','amQgFkKqc,RlXliNOHki','hrodude_wetter');

        if ($mysql_conn->connect_error) {
            die('Connect Error (' . $mysql_conn->connect_errno . ') '
                    . $mysql_conn->connect_error);
        }

	    if ($type == 'FORECAST') {
		    $path = "https://opendata.dwd.de/weather/local_forecasts/poi/".$station_id."-MOSMIX.csv";

	    } elseif ($type == 'REAL') {
		    $path = "https://opendata.dwd.de/weather/weather_reports/poi/".$station_id."-BEOB.csv";

	    } else {
            exit("Ungültiger Parameter 'type'!");
        }

	    $file = file_get_contents($path);

        $zeilen = explode("\n", $file);
        
        $messgr_kurz = explode(';', $zeilen[0]);        //CODE
        $einheit = explode(';', $zeilen[1]);            //EINHEIT
        $messgr_text = explode(';', $zeilen[2]);        //Beschreibung intern

	    //damit der scope stimmt, hier definiert, Werte nicht verwenden!
	    $ar_messgr[0] = 'DATE';
	    $ar_messgr[1] = 'TIME';

        //Messgrößen updaten, nur wenn Arrays gleich groß
        if ( count($messgr_kurz) == count($einheit) && count($messgr_kurz) == count($messgr_text) ) 

        {

            for ($i = 2; $i < count($messgr_text); $i++) {
                //echo $messgr_kurz[$i]." ".$einheit[$i]." ".$messgr_text[$i]."<br>";
                $sql = "INSERT INTO `messgroessen` (`mess_pk`, `beschreibung`, `code`, `einheit`,`type`) VALUES 
					    (NULL
					    , '".$messgr_text[$i]."'
					    , '".$messgr_kurz[$i]."'
					    , '".$einheit[$i]."'
					    ,'".$type."');";

			    $result = $mysql_conn->query($sql);

			    $sql = "SELECT `mess_pk`,`timeshift_hours` FROM `messgroessen` WHERE `code`= '".$messgr_kurz[$i]."';";
			    $result = $mysql_conn->query($sql);
			    if ($result === FALSE) {
				    echo	 $sql."<br>";
			    } else {
				    $result->data_seek(0);
				    $row = $result->fetch_assoc();
				    $ar_messgr[$i] = array('pk' => $row['mess_pk'],
                                           'timeshift' =>  $row['timeshift_hours']
                                            );

				    //echo	"ar_messgr[".$i."]=".$ar_messgr[$i]." SELECT <br>";
			    }

            } //for


        } else {
            exit("Fehler beim parsen");
        }

       //remove header
        unset($zeilen[0]);	//CODE
        unset($zeilen[1]);	//UNIT
        unset($zeilen[2]);	//DESCRIPTION
        $zeilen = array_values($zeilen);

        foreach ($zeilen as $zeile) {
            $ar_zeile = explode(";", $zeile);

		    //genau so viele messgrößen wie messwerte vorhanden?
		    if (count($ar_zeile) != count($ar_messgr)){
			    break;
		    }


            $datum = $ar_zeile[0]; //STR_TO_DATE('29.12.17','%d.%m.%y')
            $zeit = $ar_zeile[1]; //STR_TO_DATE('18:00','%k:%i')

            for ( $col = 2; $col < count($ar_zeile)-1; $col++){

                $wert = str_replace(",", ".", $ar_zeile[$col]);
				if (is_numeric($wert)){

                    //---------------------------------------------------------
                    //Messwerte einmal löschen
                    if ($col == 2){
                        $date = date_create_from_format('H:i d.m.Y',$zeit.' '.$datum);
                        date_sub($date, date_interval_create_from_date_string($ar_messgr[$col]['timeshift']." hours"));
                        $tmp_zeit = $date->format('H:i');
                        $tmp_datum = $date->format('d.m.Y');

                        //vorhandene Werte zu Station+Zeit+Datum löschen
                        $sql = "DELETE FROM `messwerte` WHERE
                                `station_fk` = '".$stations_pk."'
                                AND `datum` = STR_TO_DATE('" . $tmp_datum . "','%d.%m.%y')
                                AND `zeit` = STR_TO_DATE('" . $tmp_zeit  . "','%k:%i') ";
                        $result = $mysql_conn->query($sql);
                        if ($result === FALSE) {
                            echo "Fehler beim Ausführen von: ".$sql."<br>";
                        }
                    } //Messwerte löschen
                    //---------------------------------------------------------

		            $sql = "INSERT INTO `messwerte` (`pk`, `station_fk`,`messgroesse_fk`, `datum`, `zeit`,`wert`, `type`) VALUES
					(NULL,
		             '" . $stations_pk . "'
		            , '" . $ar_messgr[$col]['pk'] . "'
		            , STR_TO_DATE('" . $tmp_datum . "','%d.%m.%y') 
		            , STR_TO_DATE('" . $tmp_zeit  . "','%k:%i')
		            , '" . $wert . "'
					, '".$type."'
		            );";

					$result = $mysql_conn->query($sql);
					if ($result === FALSE) {
		                echo "Fehler beim Ausführen von: ".$sql."<br>";
					}
				}
            }

        } //foreach

        echo "fertig";
        $mysql_conn->close();

    }
?>
