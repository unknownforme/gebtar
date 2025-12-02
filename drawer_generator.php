<?php

echo "\033[2J\033[H";

$chars = ['▘','▝','▀','▖','▍','▞','▛','▗','▚','▐','▜','▃','▙','▟','█',' '];

function generate_drawing($chars, $size) {
    $graphics = [];
    for ($i=0; $i<$size; $i++) {
        $graphics[$i] = '';
        for ($j=0; $j<$size * 2; $j++) {
            $graphics[$i] .= $chars[array_rand($chars)];
        }
    }
    return $graphics;
}
$amount = $argv[1] ?? 1;
$size = $argv[2] ?? 2;
for ($i=0; $i<$amount; $i++) {
    $graphics = (generate_drawing($chars, $size));
    foreach ($graphics as $line) {
        echo $line . PHP_EOL;
    }
    echo PHP_EOL;
}

