<?php

	error_reporting(E_ALL);	
	ini_set("display_errors","On");

/* Use internal libxml errors -- turn on in production, off for debugging */
    libxml_use_internal_errors(true);

/* Createa a new DomDocument object */
    $dom = new DomDocument;

/* Load the HTML */

	$html = file_get_contents("https://www.dwd.de/DE/leistungen/klimadatenweltweit/stationsverzeichnis.html");
	$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

//    $dom->loadHTMLFile("https://www.dwd.de/DE/leistungen/klimadatenweltweit/stationsverzeichnis.html"); //fucks up UTF8-encoding
	

/* Create a new XPath object */
    $xpath = new DomXPath($dom);

/* Query all <td> nodes containing specified class name */
    $nodes = $xpath->query("/html/body/main/section/div/article/div/div/table/thead/tr");

/* Set HTTP response header to plain text for debugging output */
    header("Content-type: text/plain");

/* open DB connection  */
    $mysql_conn = new mysqli('localhost','hrodude','amQgFkKqc,RlXliNOHki','hrodude_wetter');

    if ($mysql_conn->connect_error) {
        die('Connect Error (' . $mysql_conn->connect_errno . ') '
                . $mysql_conn->connect_error);
    }

	if (!$mysql_conn->set_charset("utf8")) {
		printf("Error loading character set utf8: %s\n", $mysql_conn->error);
		exit();
	} else {
		printf("Current character set: %s\n", $mysql_conn->character_set_name());
	}

/* Traverse the DOMNodeList object to output each DomNode's nodeValue */
    foreach ($nodes as $i => $node) {	//tr

		if ($i != 0){ //alles bis auf den header

		    $children = $node->childNodes;
			//$children[0]->nodeValue."<br>";
		    foreach($children as $j => $child) {	//td
		        if ($j == 0) 	{$name 			= $child->nodeValue;}
				elseif ($j == 2){$stat_id 		= $child->nodeValue;}
				elseif ($j == 4){$long 			= $child->nodeValue;}
				elseif ($j == 6){$lat 			= $child->nodeValue;}
				elseif ($j == 8){$hoehe 		= $child->nodeValue;}
				elseif ($j == 10){$land_kurz 	= $child->nodeValue;}
				elseif ($j == 12){$land 		= $child->nodeValue;}
				elseif ($j == 14){$kontinent 	= $child->nodeValue;}
			
		
				echo $j." ".$child->nodeValue."|"."";
			}
			$sql = "INSERT INTO `stationen` 
					(`stat_pk`, `stationsname`, `stations_id`, `longitude`, `latitude`, `elevation`, `land_kurz`, `land`, `kontinent`) VALUES 
					(NULL
					, '".$mysql_conn->real_escape_string($name)."'
					, '".$mysql_conn->real_escape_string($stat_id)."'
					, '".$mysql_conn->real_escape_string($long)."'
					, '".$mysql_conn->real_escape_string($lat)."'
					, '".$mysql_conn->real_escape_string($hoehe)."'
					, '".$mysql_conn->real_escape_string($land_kurz)."'
					, '".$mysql_conn->real_escape_string($land)."'
					, '".$mysql_conn->real_escape_string($kontinent)."');";
			$result = $mysql_conn->query($sql);
			if ($result === FALSE) {
				//echo	"Fehler bei Ausführung von ".$sql."\n";		
			}
			echo $sql;

		}
    }

    echo "fertig";
    $mysql_conn->close();
?>
