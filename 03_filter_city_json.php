<?php
$result = array();
$currentCity = false;
$cityPath = __DIR__ . '/filtered/city';
if(!file_exists($cityPath)) {
  mkdir($cityPath, 0777, true);
}
$cities = array();
foreach(glob(__DIR__ . '/geojson/*.json') AS $jsonFile) {
  $json = json_decode(file_get_contents($jsonFile), true);
  foreach($json['features'] AS $f) {
    $city = mb_substr($f['properties']['地籍址'], 0, 3, 'utf-8');
    if(!isset($cities[$city])) {
      $cities[$city] = array(
        'type' => 'FeatureCollection',
        'features' => array(),
      );
    }
    if(false === $currentCity && !file_exists($jsonFile)) {
      $currentCity = $city;
    }
    if($f['properties']['農盤分類'] === '工廠' || $f['properties']['農盤分類'] === '工廠使用') {
      $cities[$city]['features'][] = $f;
    }
  }
}

foreach($cities AS $city => $fc) {
  file_put_contents($cityPath . '/' . $city . '.geo.json', json_encode($fc));
  exec("mapshaper -i {$cityPath}/{$city}.geo.json -o format=topojson {$cityPath}/{$city}.json");
  unlink($cityPath . '/' . $city . '.geo.json');
}
