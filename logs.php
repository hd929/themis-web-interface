<?php
include("init.php");
include("config.php");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$dir = opendir($logsDir);
$session = $user['username'];
while ($file = readdir($dir)) {
  $pos = strpos($file, "[" . $session . "]");
  if ($pos > 0) {
    if ($publish == 1)
      echo "<a onclick=wload('" . $logssubDir . rawurlencode($file) . "') class='list-group-item list-group-item-action list-group-item-primary'><span class = 'glyphicon glyphicon-hand-right glyphicon-hand-right-animate'></span> <b>" . substr($file, $pos + strlen($session) + 2) . "</b>";
    else
      echo "<a href='#' class='list-group-item list-group-item-action list-group-item-primary'><span class = 'glyphicon glyphicon-hand-right glyphicon-hand-right-animate'></span> <b>" . substr($file, $pos + strlen($session) + 2) . "</b>";
    if (strpos($file, ".log") > 0 || strpos($file, ".LOG") > 0) {
      if ($publish == 1) {
        $finp = fopen($logsDir . "/" . $file, "r");
        $str = substr(fgets($finp), strlen($session) + 3);
        $st = 0;
        for ($i = 0; $i < strlen($str); $i++)
          if ($str[$i] == ':') {
            $st = $i + 2;
            break;
          }
        $str = trim(substr($str, $st));
        fclose($finp);
      } else
        $str = "Đã chấm xong!";
    } else
      $str = "Đang đợi chấm...";
    if (floatval($str)) {
      $point = floatval($str);
      if ($point <= 1)
        echo ' <span class="label label-danger label-small" style="position: relative; top: 4px">' . $str . '</span>';
      else if ($point <= 5)
        echo ' <span class="label label-warning label-small" style="position: relative; top: 4px">' . $str . '</span>';
      else
        echo ' <span class="label label-info label-small" style="position: relative; top: 4px">' . $str . '</span>';
    } else
      echo ' <span class="label label-danger label-small" style="position: relative; top: 4px">' . $str . '</span>';
    $length = strlen($file);
    $tenfile = substr($file, 0, $length - 4);
    $filesub = $uploadDir . "/" . $tenfile;
    if (!file_exists($filesub)) {
      if ($str == "10,00") {
        echo '<span class = "badge" style="background-color: transparent; color: green;"><span class="glyphicon glyphicon-ok"></span></span>';
      } else if ($str == "ℱ Dịch lỗi")
        echo '<span class = "badge" style="background-color: transparent; color: #f0ad4e;"><span class="glyphicon glyphicon-warning-sign"></span></span>';
      else {
        echo '<span class = "badge" style="background-color: transparent; color: red;"><span class="glyphicon glyphicon-remove"></span></span>';
      }
    } else echo '<span class = "badge" style="background-color: transparent; color: grey;"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span></span>';

    echo '</a>';
  }
}
