<?php
  mysql_connect("localhost", "root", "");
  mysql_select_db("ddj");
  mysql_set_charset("utf8");

  $file = file_get_contents("GG14.csv");
  foreach(explode("\r", $file) as $line) {
    // GMDNR;GMDNAME;BZNR;KTNR;GRNR;AREA_HA;X_MIN;
    // X_MAX;Y_MIN;Y_MAX;X_CNTR;Y_CNTR;Z_MIN;Z_MAX;Z_AVG;Z_MED
    $data = explode(";", $line);

    print_r($data);
    $GMDNR = $data[0];
    $GMDNAME = $data[1];
    $BZNR = $data[2];
    $KTNR = $data[3];
    $GRNR = $data[4];
    $AREA = $data[5];
    $X_MIN = $data[6];
    $X_MAX = $data[7];
    $Y_MIN = $data[8];
    $Y_MAX = $data[9];
    $Z_MIN = $data[12];
    $Z_MAX = $data[13];

    mysql_query("UPDATE gemeinden SET 
      GMDNAME = '".mysql_real_escape_string($GMDNAME)."',
      GMDNR = $GMDNR,
      BZNR = $BZNR,
      KTNR = $KTNR,
      AREA = $AREA,
      GRNR = $GRNR,
      X_MIN = $X_MIN,
      X_MAX = $X_MAX,
      Y_MIN = $Y_MIN,
      Y_MAX = $Y_MAX,
      Z_MIN = $Z_MIN,
      Z_MAX = $Z_MAX
      WHERE name = '".mysql_real_escape_string($GMDNAME)."';");

  }