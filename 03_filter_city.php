<?php
$fh = fopen(__DIR__ . '/filtered/city.csv', 'w');
fputcsv($fh, array('area', 'type', 'count'));
$result = array();
foreach(glob(__DIR__ . '/geojson/*.json') AS $jsonFile) {
  $json = json_decode(file_get_contents($jsonFile), true);
  foreach($json['features'] AS $f) {
    $area = substr($f['properties']['地籍址'], 0, strpos($f['properties']['地籍址'], '('));
    if(!isset($result[$area])) {
      $result[$area] = array();
    }
    if(!isset($result[$area][$f['properties']['農盤分類']])) {
      $result[$area][$f['properties']['農盤分類']] = 0;
    }
    ++$result[$area][$f['properties']['農盤分類']];
  }
}

foreach($result AS $area => $data) {
  foreach($data AS $type => $count) {
    fputcsv($fh, array($area, $type, $count));
  }
}
