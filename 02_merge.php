<?php
foreach(glob(__DIR__ . '/raw/*.json') AS $jsonFile) {
  $json = json_decode(file_get_contents($jsonFile), true);
  print_r($json); exit();
}
