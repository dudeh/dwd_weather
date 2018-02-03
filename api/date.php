<?php
    $time = '12:00';
    $datum = '26.12.2018';
    
    $date = date_create_from_format('H:i d.m.Y',$time.' '.$datum);
    date_sub($date, date_interval_create_from_date_string("6 hours"));
    echo $date->format('H:i d.m.Y') . "\n";

    
 /*   $datetime = DateTime::createFromFormate('H:i d.m.Y',$time.' '.$datum)->sub(new DateInterval("40 hours"));
    echo date_format($datetime, 'H:i d.m.Y');
  */  

?>
