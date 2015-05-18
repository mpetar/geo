<?php
  mysql_connect("localhost", "root", "");
  mysql_select_db("ddj");
  mysql_set_charset("utf8");

  $file = file_get_contents("g2s14.kml");
  $xml = new SimpleXMLElement($file);

  #mysql_query("TRUNCATE gemeinden;");

  foreach($xml->Document->Folder->Placemark as $g) {
    print_r($g->name);
    if(count($g->MultiGeometry->Polygon) > 1) {
      $coordinates = '';
      foreach($g->MultiGeometry->Polygon as $poly) {
        $coordinates = " ".$poly->outerBoundaryIs->LinearRing->coordinates;
        mysql_query("INSERT INTO gemeinden SET name = '".mysql_real_escape_string($g->name)."',
          coordinates = '".$coordinates."';");
      }

    } else {
      $coordinates = $g->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
      mysql_query("INSERT INTO gemeinden SET name = '".mysql_real_escape_string($g->name)."',
        coordinates = '".$coordinates."';");
    }



    echo mysql_error();
  }