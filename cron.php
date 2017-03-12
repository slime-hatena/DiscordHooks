<?php
echo str_pad(" ",4096)."\n";
ob_end_flush();
ob_start('mb_output_handler');

echo date("Y/m/d H:i:s") . "<br>";
echo 'Current PHP version: ' . phpversion() . "<br>";

// users.jsonをパース
$json = file_get_contents('users.json');
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$arr = json_decode($json,false);


foreach ($arr->Procedure as $value) {
   // var_dump($value);
    echo "<b>" . $value->Function . "(" . $value->Param . ")</b><br>";
    include(dirname(__FILE__) . '/includes/' . $value->Function . '.php');
    echo "<hr>";

    ob_flush();
    flush();
}