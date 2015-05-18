<?php
  mysql_connect("localhost", "root", "");
  mysql_select_db("ddj");
  mysql_set_charset("utf8");
  error_reporting(0);
  include("wgs84_ch1903.php");

  mysql_query("TRUNCATE coordinates;");
  /* mapping */
  $qr = mysql_query("SELECT * FROM gemeinden;");
  while($data = mysql_fetch_object($qr)) {
    /* scale coordinates, remove z */

    $vectors = "";
         echo $data->GMDNR."\n\n"; 
    $da = explode(" ", $data->coordinates);
    unset($da[0]);
    foreach($da AS $vec) {

      $ordinantes = explode(",", $vec);
      $x = WGStoCHx($ordinantes[1], $ordinantes[0]) ;
      $y = WGStoCHy($ordinantes[1], $ordinantes[0]) ;
      $z = $ordinantes[2];
      mysql_QUERY("INSERT INTO coordinates set
        GMDNR = '".$data->GMDNR."', X = $x, Y = $y;");
    }

  }