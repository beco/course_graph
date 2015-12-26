<?php

$file = fopen("data.json", "r");
while(!feof($file)) {
  $data .= fgets($file);
}
fclose($file);

$data = json_decode($data, true);

$matrix = Array();

$i = 0;
foreach($data["careers"] as $c) {
  foreach($c["courses"] as $u) {
    if(!$u["deprecated"] && !is_array($matrix[$u["id"]])) {
      echo sprintf("{id: %d, label:'%s'},\n", $u['id'], $u['slug']);
      $matrix[$u["id"]] = Array();
    }
  }
}

//print_r($matrix);
