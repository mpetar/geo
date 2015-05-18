<html>
<head>
  <link href='http://fonts.googleapis.com/css?family=Hind:400,600|Playfair+Display:400,700,400italic|Lato:400,700|Roboto:400,900' rel='stylesheet' type='text/css'>
  <style>
  * { font-family: 'Roboto', serif; }
  b { font-weight: 900; }
  </style>
</head>
<body>

  <div id="log">
    <b>Gemeinde:</b> <span name="data-name"></span> <br>
    <b>Area:</b> <span name="data-ha"></span> m<sup>2</sup>
  </div>

<?php
  mysql_connect("localhost", "root", "");
  mysql_select_db("ddj");
  mysql_set_charset("utf8");
error_reporting(0);
  include("wgs84_ch1903.php");

  function l($t) { /*echo $t."\n";*/ }

  $width = 600; /* x */

  $where = ' ';



  /* ermittle dimension der gewünschten region */
  $qr = mysql_query("SELECT MIN(X) as x_min, MAX(X) as x_max,
    MIN(Y) as y_min, MAX(Y) as y_max FROM coordinates WHERE GMDNR IN (SELECT GMDNR FROm gemeinden $where);");
  $data = mysql_fetch_object($qr);

  $__x_min = $data->x_min;
  $__x_max = $data->x_max;
  $__y_min = $data->y_min;
  $__y_max = $data->y_max;

  l("x:min $__x_min");
  l("y:min $__y_min");


  $__dim_x = $__x_max - $__x_min;
  $__dim_y = $__y_max - $__y_min;
  l("x:dim $__dim_x");
  l("y:dim $__dim_y");
  $__scale = $width / $__dim_x;
  l("scale $__scale");
  $__height = round($__scale * $__dim_y); 



  /* mapping */
  $qr = mysql_query("SELECT * FROM gemeinden  $where;");
  while($data = mysql_fetch_object($qr)) {
    /* scale coordinates, remove z */

    $vectors = "";
    $sets = explode(" ", $data->coordinates);
    unset($sets[0]);
    foreach($sets AS $vec) {
      $ordinantes = explode(",", $vec);
      l(WGStoCHx($ordinantes[1], $ordinantes[0]));
      $x = round((WGStoCHx($ordinantes[1], $ordinantes[0])-$__x_min)*$__scale,2);
      $y = round((WGStoCHy($ordinantes[1], $ordinantes[0])-$__y_min)*$__scale,2);

      if($x < 0 OR $y < 0) continue;

      l($x);
      $vectors .= "$x,$y ";
    }

    if($data->lake == 1) $class = 'lake';
    $polygon .= '<polygon points="'.$vectors.'" data-ha="'.$data->AREA.'" name="'.$data->name.'" class="g-'.$data->GMDNR.' k-'.$data->KTNR.' '.$class.'" />';
    unset($lake);
  }


echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<svg xmlns="http://www.w3.org/2000/svg"
     xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:ev="http://www.w3.org/2001/xml-events"
     version="1.1" baseProfile="full"
     width="<?=$width;?>" height="<?=$__height;?>"
     style="transform: rotate(-90deg);padding-left: 390px;
  margin-top: 17px;">
     <style>polygon {   fill: #cdcdcd;
  stroke: #fff;
  stroke-width: 0.3; }
  .lake { fill: #1E90FF; }</style>
<?=$polygon;?>
</svg>
<script src="jquery-2.1.4.js"></script>
<script type="text/javascript">
   $(window).load(function () {
      $('polygon').mouseover(function() {
        $('[name=data-name]').html($(this).attr("name"));
        $('[name=data-ha]').html($(this).attr("data-ha"));
        $(this).attr("style", "fill:red;");
        console.log($(this).attr("class"))
      }).mouseout(function () {
        $(this).attr("style", "");
      });

    });
</script>

</body>
</html>