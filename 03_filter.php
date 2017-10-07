<?php
$fc = array(
  'type' => 'FeatureCollection',
  'features' => array(),
);
$fh = fopen(__DIR__ . '/filtered/factories.csv', 'w');
$header = false;
foreach(glob(__DIR__ . '/geojson/*.json') AS $jsonFile) {
  $json = json_decode(file_get_contents($jsonFile), true);
  foreach($json['features'] AS $f) {
    if($f['properties']['農盤分類'] === '工廠' || $f['properties']['農盤分類'] === '工廠使用') {
      $p = $f['properties'];
      if(false !== $header) {
        $header = false;
        fputcsv($fh, array_keys($p));
      }
      fputcsv($fh, $p);
      $f['properties'] = array(
        'id' => $p['段號'],
      );
      $fc['features'][] = $f;
    }
  }
}

file_put_contents(__DIR__ . '/filtered/factories.json', json_encode($fc));
