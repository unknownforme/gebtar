<?php 

function hp_bar ($current_hp, $max_hp, $additional_info = ''):string {
    $hp_percentage = (int)round(($current_hp/$max_hp) * 100);

    //adds background coloring, and do stuff w it
    $percentage_hp_left = (int)ceil($hp_percentage / 3);
    $hp_string = '(hp: ' . $current_hp . "/" . $max_hp . ")";
    $hp_string = str_pad($hp_string, 34);

    $inside_hp_bar = substr($hp_string, 0, $percentage_hp_left);
    $outside_hp_bar = substr($hp_string, $percentage_hp_left);
    if ($hp_percentage >= 60) {
        $hp_bar = "[" . bg_green($inside_hp_bar) . $outside_hp_bar . "] $additional_info" . PHP_EOL;
    } elseif ($hp_percentage >= 30) {
        $hp_bar = "[" . bg_yellow($inside_hp_bar) . $outside_hp_bar . "] $additional_info" . PHP_EOL;
    } elseif ($current_hp >= 1) {
        $hp_bar = "[" . bg_red($inside_hp_bar) . $outside_hp_bar . "] $additional_info" . PHP_EOL;
    } else {
        $hp_bar = "[" . $inside_hp_bar . $outside_hp_bar . "] $additional_info" . PHP_EOL;
    }


    return $hp_bar;
}
function clearcmd () {
    echo "\e[2J\e[H";
}
function slow_read ($text, $ms_time_between_chars= 35) {
    $length = strlen($text);
    for ($i = 0; $i < $length; $i++) {
        echo $text[$i];
        if ($text[$i] == ',') {
            usleep($ms_time_between_chars * 6000);
        }
        if ($text[$i] == '.' || $text[$i] == '!' || $text[$i] == '?') {
            usleep($ms_time_between_chars * 10000);
        }
        usleep($ms_time_between_chars * 1000);
    }
}
//colors
function red ($word) {
    return "\033[31m".$word."\033[0m";
}
function green ($word) {
    return "\033[32m".$word."\033[0m";
}
function yellow ($word) {
    return "\033[33m".$word."\033[0m";
}
function blue ($word) {
    return "\033[34m".$word."\033[0m";
}
function purple ($word) {
    return "\033[35m".$word."\033[0m";
}
function cyan ($word) {
    return "\033[36m".$word."\033[0m";
}
function white ($word) {
    return "\033[37m".$word."\033[0m";
}

function bg_red ($word) {
    return "\033[41m" . $word . "\033[0m";
}
function bg_green ($word) {
    return "\033[30;42m".$word."\033[0m";
}
function bg_yellow ($word) {
    return "\033[30;43m" . $word . "\033[0m";
}
function bg_blue ($word) {
    return "\033[44m".$word."\033[0m";
}
function bg_purple ($word) {
    return "\033[45m".$word."\033[0m";
}
function bg_cyan ($word) {
    return "\033[46m".$word."\033[0m";
}
function bg_white ($word) {
    return "\033[47m".$word."\033[0m";
}