<?php

require_once 'db.php';

$link = open_database_connection();
$num =  maxId_unique_drug_name();
 //$num = 8;

$i = 1;
while ($i <= $num) {
$drugName = getUniqueDrugNname($i);
$arrLastRevised = getList_last_revised($drugName);
$maxLastRevised = max($arrLastRevised);
$arr = getUniqueDrug($drugName, $maxLastRevised);
insertDrug($arr['drug_name'], $arr['drug_reference'], $arr['last_revised']);

  $i++;
}

echo 'END';
