<html>
    <head></head>
    <body style="font-family:sans-serif">



    <style style="text/css">
      	.hoverTable{
		    width:100%; 
		    border-collapse:collapse; 
	    }
	    .hoverTable td{ 
		    padding:7px; border:#4e95f4 1px solid;
	    }
	    /* Define the default color for all the table rows */
	    .hoverTable tr{
		    background: #b8d1f3;
	    }
	    /* Define the hover highlight color for the table row */
        .hoverTable tr:hover {
              background-color: #ffff99;
        }
    </style>

<?php
//Parameter Stations-ID auslesen

//Datei laden file_get_contents
    $file = file_get_contents('https://opendata.dwd.de/weather/weather_reports/poi/10170-BEOB.csv');

//explode(\n)
    $zeilen = explode("\n", $file);
    
//remove table header
    unset($zeilen[0]);
    unset($zeilen[1]);
    unset($zeilen[2]);
    $zeilen = array_values($zeilen);


//prepare output

    echo "<a href=\"https://opendata.dwd.de/weather/webcam/Warnemuende-NW/Warnemuende-NW_latest_full.jpg\" target=\"_blank\">Warnemünde</a><p>";

    echo "<p>aktuelle/letzte Messwerte</p>";
    
    echo "<table style=\"width:100% font-family:sans-serif\">";
    
    
    foreach ($zeilen as $zeile) {
        $ar_zeile = explode(";", $zeile);

/*        
        echo "<tr>";
        
        foreach ($ar_zeile as $zelle) {
            echo    "<td>";
            echo    $zelle;
            echo    "</td>";
        }
        
        echo "</tr>";
*/
        $ar_zeile = explode(";", $zeile);
        $i = 0;
        $date[i] = $ar_zeile[0];
        $time[i] = $ar_zeile[1];
        $temp_2m[i] = $ar_zeile[9];
        $tem_5cm[i] = $ar_zeile[39];
        $cloud_height[i] = $ar_zeile[13];
        $wind_boe_1h[i] = $ar_zeile[21];
        $wind_mean[i] = $ar_zeile[23];
        $wind_dir[i] = $ar_zeile[22];
        $cloudcover[i] = $ar_zeile[2];
        $hpa[i] = $ar_zeile[36];
        $moist[i] = $ar_zeile[37];

        echo "<tr>";
        echo    "<td>Datum</td>";
        echo    "<td>".$date[i]."</td>";
        echo "</tr>";
        //
        echo "<tr>";
        echo    "<td>Uhrzeit UTC</td>";
        echo    "<td>".$time[i]."</td>";
        echo "</tr>";
        //
        echo "<tr>";
        echo    "<td>Temperatur in 2m</td>";
        echo    "<td>".$temp_2m[i]." °C</td>";
        echo "</tr>";
        //
        echo "<tr>";
        echo    "<td>Temperatur in 5cm</td>";
        echo    "<td>".$tem_5cm[i]." °C</td>";
        echo "</tr>";
        //
        echo "<tr>";
        echo    "<td>Wolkenhöhe</td>";
        echo    "<td>".$cloud_height[i]." m</td>";
        echo "</tr>";
        //
        echo "<tr>";
        echo    "<td>Windböen der letzten 1h</td>";
        echo    "<td>".$wind_boe_1h[i]. " km/h</td>";
        echo "</tr>";
        //
        echo "<tr>";
        echo    "<td>Wind Durchschnitt letzte 1h</td>";
        echo    "<td>".$wind_mean[i]." km/h</td>";
        echo "</tr>";
        //
        echo "<tr>";
        echo    "<td>Windrichtung</td>";
        echo    "<td>".$wind_dir[i]."°</td>";
        echo "</tr>";
        //        
        echo "<tr>";
        echo    "<td>Bewökung</td>";
        echo    "<td>".$cloudcover[i]."%</td>";
        echo "</tr>";
        //
        echo "<tr>";
        echo    "<td>Luftdruck</td>";
        echo    "<td>".$hpa[i]." hpa</td>";
        echo "</tr>";
        //
        echo "<tr>";
        echo    "<td>rel. Luftfeuchte</td>";
        echo    "<td>".$moist[i]."%</td>";
        echo "</tr>";
        //

        break;
    }

    echo  "</table>";

///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

    echo "<p>Forecast</p>";    

//Datei laden file_get_contents
    $file = file_get_contents('https://opendata.dwd.de/weather/local_forecasts/poi/10170-MOSMIX.csv');

//explode(\n)
    $zeilen = explode("\n", $file);
    
//remove table header
    unset($zeilen[0]);
    unset($zeilen[1]);
    unset($zeilen[2]);
    $zeilen = array_values($zeilen);

//prepare output
    $i = 0;
    
    echo "<table class=\"hoverTable\">";
    echo "<tr>";
    echo    "<th>Datum</th>";
    echo    "<th>Uhreit UTC</th>";
    echo    "<th>Temperatur</th>";
    echo    "<th>Windrichtung</th>";
    echo    "<th>Wind mean</th>";
    echo    "<th>wind max</th>";
    echo    "<th>Niederschlag letzte 3h</th>";
    echo    "<th>chance of rain for past 3h</th>";
    echo    "<th>chance of rain for past 6h</th>";
    echo    "<th>Bedeckung 1-8</th>";
    echo    "<th>Luftdruck</th>";
    echo "</tr>";

    foreach ($zeilen as $zeile) {
        $ar_zeile = explode(";", $zeile);
        
        $date[i] = $ar_zeile[0];
        $time[i] = $ar_zeile[1];
        $temp[i] = $ar_zeile[2];
        $wind_dir[i] = $ar_zeile[8];
        $wind_mean[i] = $ar_zeile[9];
        $wind_max[i] = $ar_zeile[10];
        $perc_3h[i] = $ar_zeile[15];
        $ch_rain_3h[i] = $ar_zeile[18];        
        $ch_rain_6h[i] = $ar_zeile[19];
        $cloudcover[i] = $ar_zeile[27];
        $hpa[i] = $ar_zeile[31];
        $i++;

        echo "<tr>";
        echo    "<td>".$date[i]."</td>";
        echo    "<td>".$time[i]."</td>";
        echo    "<td>".$temp[i]."°C</td>";
        echo    "<td>".$wind_dir[i]."°</td>";
        echo    "<td>".$wind_mean[i]." km/h</td>";
        echo    "<td>".$wind_max[i]." km/h</td>";
        echo    "<td>".$perc_3h[i]." mm</td>";
        echo    "<td>".$ch_rain_3h[i]."%</td>";
        echo    "<td>".$ch_rain_6h[i]."%</td>";
        echo    "<td>".$cloudcover[i]."</td>";
        echo    "<td>".$hpa[i]." hpa</td>";
        echo "</tr>";
    }

    echo  "</table>";
?>
    </body>
</html>
