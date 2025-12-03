<?php

function mb_strrev(string $str, string $encoding = null): string {
    $chars = mb_str_split($str, 1, $encoding ?: mb_internal_encoding());
    return implode('', array_reverse($chars));
}

function texture_printer($texture) {
    foreach ($texture as $texture_part) {
        echo ($texture_part) . PHP_EOL;
    }
}
$texture = [
"▗▟██▍█▖▖",
"▛▀▀▜▘▀▀▚",
"▙  ▟▚▗ ▞",
"▀█▀▙▗▜▜▘",
];
//
//$to_replace = [
//"▘", "▝",
//
//"▖", "▗",
//
//"▐", "▌",
//
//"▞", "▚",
//
//"▛", "▜",
//
//"▙", "▟"
//];
//$replace_with = [
//"▝","▘",
//
//"▗","▖",
//
//"▌","▐",
//
//"▚","▞",
//
//"▜","▛",
//
//"▟", "▙",
//];

$map = [
    "▘" => "▝",
    "▝" => "▘",
    "▖" => "▗",
    "▗" => "▖",
    "▐" => "▌",
    "▌" => "▐",
    "▞" => "▚",
    "▚" => "▞",
    "▛" => "▜",
    "▜" => "▛",
    "▙" => "▟",
    "▟" => "▙",
];

for ($i = 0; $i < count($texture); $i++) {
    $texture[$i] = strtr($texture[$i], $map);
    $texture[$i]= mb_strrev($texture[$i]);
}
texture_printer($texture);