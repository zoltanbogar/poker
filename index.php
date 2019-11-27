<?php

use AzerionAssignment\Engine;

require './vendor/autoload.php';

echo "Please add the path to the file where the input can be found!\n";

//FIXME
$filepath = 'sample/input.txt';
//$handle = fopen("php://stdin", "r");
//$filepath = fgets($handle);
$filepath = str_replace(["\"", "/"], DIRECTORY_SEPARATOR, $filepath);
$filepath = __DIR__ . DIRECTORY_SEPARATOR .  $filepath;
$filepath = trim($filepath);

if(!is_readable($filepath)){
  echo "Input file cannot be found!\n";
  exit;
}
//fclose($handle);

$index = new Engine($filepath);
$index->run();
