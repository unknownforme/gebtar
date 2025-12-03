<?php

include_once 'functions.php';

$samples = 100;
$radius = 16;
//amount of times a character gets printed on the thing
$precision = 360;

cursorDown($radius);
$y = 0;
for ($deg = 0; $deg < $precision; $deg += 5) {
    if ($y > 1){
        cursorUp((int)round($y));
    } elseif ($y==0){

    } else{
        cursorDown(abs((int)round($y)));
    }
    $rad = deg2rad($deg);
    $x = (2 * cos($rad) * $radius);
    $y = (sin($rad) * $radius);
//    echo "coordinates: x = $x, y = $y\n"; continue;
    if ($y > 1){
        cursorDown((int)round($y));
    } elseif ($y==0){

    } else {
        cursorUp(abs((int)round($y)));
    }
    echo "\r"."\033[" . ((int)round($x) + $radius*2) . "G##";
//    exit;
}
cursorDown($radius* 2 + 1);