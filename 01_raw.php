<?php
if(!file_exists(__DIR__ . '/raw/lastId')) {
  file_put_contents(__DIR__ . '/raw/lastId', '0');
}
$lastId = intval(file_get_contents(__DIR__ . '/raw/lastId'));
$objects = array();

while(++$lastId) {
  $objects[] = $lastId;
  if($lastId % 200 === 0) {
    $q = implode(',', $objects);
    $objects = array();
    $json = shell_exec("curl -k 'https://map.coa.gov.tw/proxy/proxy.ashx?https://map.coa.gov.tw/arcgis/rest/services/Farmland_survey/Cadastral_Farmland_coa1/MapServer/0/query?objectIds={$q}&outFields=*&returnGeometry=true&f=json' -H 'Host: map.coa.gov.tw' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:54.0) Gecko/20100101 Firefox/54.0' -H 'Accept: */*' -H 'Accept-Language: en-US,en;q=0.5' -H 'Accept-Encoding: gzip, deflate, br' -H 'Content-Type: application/x-www-form-urlencoded' -H 'Referer: https://map.coa.gov.tw/farmland/survey.html' -H 'Connection: keep-alive'");
    $obj = json_decode($json, true);
    if(!isset($obj['features'][0])) {
      die('done');
    }
    echo $obj['features'][0]['attributes']['地籍址'] . " - {{$lastId}}\n";
    file_put_contents(__DIR__ . '/raw/data_' . $lastId . '.json', $json);
    file_put_contents(__DIR__ . '/raw/lastId', $lastId);

  }
}
