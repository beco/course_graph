<?php
/*

var edges = new vis.DataSet([
  {from: 63, to: 24, arrows:'to', dashes:true},
  {from: 1, to: 3, arrows:'to'},

var nodes = new vis.DataSet([
  {id: 39, label:'programacion-basica'},

27,programacion-android,[1004],[1005,1009,45]
*/

class Node {
  var $id;
  var $name;
  var $required;
  var $optional;

  public function __construct($id = 0, $name = "", $rq = "", $op = "") {
    $this->id = $id;
    $this->name = $name;
    $this->required = $this->extract($rq);
    $this->optional = $this->extract($op);
    echo sprintf("New node: %d - %s\n", $this->id, $name);
    //print_r($this->required);
    //print_r($this->optional);
  }

  private function extract($st = "") {
    if(preg_match('/\[([\d,]+)\]/', $st, $m)) {
      return split(",", $m[1]);
    }
    return Array();
  }

  public function get_json_node () {
    return sprintf("{id: %d, label:'%d-%s'}",
      $this->id,
      $this->id,
      $this->name);
  }

  public function get_json_edges() {
    $ret = Array();

    foreach($this->required as $r) {
      $ret[] = sprintf("{from: %d, to: %d, arrows:'from'}",
        $this->id, $r);
    }

    foreach($this->optional as $o) {
      $ret[] = sprintf("{from: %d, to: %d, arrows:'from', dashes:true}",
        $this->id, $o);
    }
    return join(",\n", $ret);
  }
}


$file = fopen("matrix.txt", "r");

$nodes = Array();

while(!feof($file)) {
  $line = fgets($file);
  if(!preg_match('/^(\d+),([\w\-]+)(,\[.*?\])?(,\[.*?\])?$/', $line, $m)) {
    echo "error\n- $line \n";
    continue;
  }
  $nodes[] = new Node($m[1], $m[2], $m[3], $m[4]);
}

$jnodes = Array();
$jedges = Array();

foreach($nodes as $n) {
  $jnodes[] = $n->get_json_node();
  $jedges[] = $n->get_json_edges();
}

echo "var nodes = new vis.DataSet([";
echo join(",\n", $jnodes);
echo "]);";
echo "\n";
echo "var edges = new vis.DataSet([";
echo join(",\n", $jedges);
echo "]);";
