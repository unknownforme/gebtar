<?php

foreach (glob('*.txt') as $file) {
    if (intval(substr($file, 16)) != 0) {
        $all_time[] = intval(substr($file, 16));
    }
}
$biggest_time = max($all_time);
$recent_file = "recorded_actions$biggest_time.txt";


if (readline("most recent file: $recent_file. play that? ")[0] == 'n') {
    $recent_file = readline('complete filename to replay then?: ');
}

$total_file_content = unserialize(file_get_contents($recent_file));
[$seed, $player_actions] = $total_file_content;

foreach ($player_actions as $action) {
    foreach ($action as $indu_action) {
        $total_actions[] = $indu_action;
    }
}

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
if (!function_exists('mb_str_pad')) {
    function mb_str_pad($input, $pad_length, $pad_string = ' ', $pad_type = STR_PAD_RIGHT, $encoding = 'UTF-8')
    {
        $input_length = mb_strlen($input, $encoding);
        $pad_string_length = mb_strlen($pad_string, $encoding);

        if ($pad_length <= 0 || ($pad_length - $input_length) <= 0) {
            return $input;
        }

        $num_pad_chars = $pad_length - $input_length;

        switch ($pad_type) {
            case STR_PAD_RIGHT:
                $left_pad = 0;
                $right_pad = $num_pad_chars;
            break;

            case STR_PAD_LEFT:
                $left_pad = $num_pad_chars;
                $right_pad = 0;
            break;

            case STR_PAD_BOTH:
                $left_pad = floor($num_pad_chars / 2);
                $right_pad = $num_pad_chars - $left_pad;
            break;
        }

        $result = '';
        for ($i = 0; $i < $left_pad; ++$i) {
            $result .= mb_substr($pad_string, $i % $pad_string_length, 1, $encoding);
        }
        $result .= $input;
        for ($i = 0; $i < $right_pad; ++$i) {
            $result .= mb_substr($pad_string, $i % $pad_string_length, 1, $encoding);
        }

        return $result;
    }
}

function clearcmd () {
    echo "\e[2J\e[H";
}
function slow_read ($text, $ms_time_between_chars= 35) {
    $length = mb_strlen($text);
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
    return "\033[42m".$word."\033[0m";
}
function bg_yellow ($word) {
    return "\033[43m" . $word . "\033[0m";
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
clearcmd();
//slow_read("damage rules: \nwhite does 1 damage, green does 5 damage,\nyellow does 45 damage, red does 125 damage,\nstaying does 25 damage.\npurple does 0 damage, BUT the next damage you take will be doubled. \ntile conversion rules: \nyellow becomes red.\nred becomes green.\ngreen becomes yellow on odd turns, or green, yellow, or purple on even turns.\nwhite becomes green, yellow, or purple.\npurple becomes any random color.\n", 1);
$player_actions = [];
$arena_size = 2;
$grid_size = 2;
$action = 'start';
$player_stats = [
    "health" => [1300,1300],//health -> [current|max]
];
$boss_stats = ['health' => 1000];
$boss_stats_static = ['health' => 1000];
$arena = [];
$arena_pieces = ['red', 'green', 'yellow', 'purple', 'white'];
// make an arena with multiple spots varying in damage
$turn = 0;
$player_turn = $turn;
$player_pos = [1,0];
$boss_graphics = [blue('▛▚▞▜'), blue('▙') . red('▘▝') . blue('▟')];
$player_graphics = [blue('╔') . blue('██') . blue('╗'), blue('▟▀▀▙')];
$boss_pos = 2;
$poisoned = false;
echo $seed . PHP_EOL;
while($player_stats['health'][0] > 0) {
    mt_srand($seed + $arena_size);
    if (!isset($additional_str)) {
        $additional_str = '';
    }
    $player_looks = 0;
    echo purple(str_pad('boss', 30)) . hp_bar($boss_stats['health'], $boss_stats_static['health']);
    echo cyan(str_pad('you', 30)) . hp_bar($player_stats['health'][0], $player_stats['health'][1], $additional_str);
    $additional_str = '';


    for($x = 0; $x < $arena_size; $x++) {
        for($y = 0; $y < $arena_size; $y++) {
            if ($x == $player_pos[0] && $y == $player_pos[1]) {
                $arena[$x][$y] = 'player';
                continue;
            }
            if (isset($arena[$x][$y])) {
                if ($arena[$x][$y] == 'yellow') {
                    $arena[$x][$y] = 'red';
                    continue;
                }
                if ($arena[$x][$y] == 'red') {
                    $arena[$x][$y] = 'green';
                    continue;
                }
                if ($arena[$x][$y] == 'green') {
                    if ($turn % 2) {
                        $arena[$x][$y] = $arena_pieces[mt_rand(1, count($arena_pieces) -2)];
                        continue;
                    }
                    $arena[$x][$y] = 'yellow';
                    continue;
                }
                if ($arena[$x][$y] == 'white') {
                    $arena[$x][$y] = $arena_pieces[mt_rand(1, count($arena_pieces) -2)];
                    continue;
                }
            }
            $pseudo_rand = mt_rand(0, count($arena_pieces) -1 );
            $arena[$x][$y] = $arena_pieces[$pseudo_rand];
        }
    }

    for ($boss_looks = 0; $boss_looks < $grid_size; $boss_looks++) {
        echo mb_str_pad("", ($boss_pos * 2 * $grid_size) - ($grid_size * 2), '  ', STR_PAD_LEFT) . $boss_graphics[$boss_looks] . PHP_EOL;
    }
    for($x = 0; $x < $arena_size; $x++) {
        for ($z = 0; $z < $grid_size; $z++) {

            for($y = 0; $y < $arena_size; $y++) {

                if ($arena[$x][$y] == 'player') {
                    echo $player_graphics[$player_looks];
                    if ($player_looks == 1) { $player_looks = 0;}
                    if ($player_looks == 0) { $player_looks = 1;}
                }
                if ($arena[$x][$y] == 'green') {
                    echo bg_green('    ');
                }
                if ($arena[$x][$y] == 'yellow') {
                    echo bg_yellow('    ');

                }
                if ($arena[$x][$y] == 'red') {
                    echo bg_red('    ');
                }
                if ($arena[$x][$y] == 'purple') {
                    echo bg_purple('    ');
                }
                if ($arena[$x][$y] == 'white') {
                    echo bg_white('    ');
                }
            }
            echo PHP_EOL;
        }
    }
    $turn++;

    //player movement
    echo 'possible actions: up, down, left, right, stay, and hit' . PHP_EOL;
    while (true) {
        echo "action: $action" . PHP_EOL;
        while (true) {
            $action = $total_actions[$player_turn];
            $player_turn++;
            if ($action == 'up' || $action == 'down' || $action == 'left' || $action == 'right' || $action == 'stay' || $action == 'hit') {
                break;
            }
        }
        switch ($action) {
            case 'hit':
                if ($player_pos[0] == 0 && $player_pos[1] == ($boss_pos - 1)) {
                    $boss_stats['health'] -= 100;
                    echo "hit boss for 100 damage " . PHP_EOL;

                    if ($boss_stats['health'] < 1) {
                        break 3;
                    }
                    slow_read('the boss fled further back' . PHP_EOL);
                    $poisoned = false;
                    $arena_size++;
                    $pseudo_rand = mt_rand(1, $arena_size - 1);
                    $boss_pos = $pseudo_rand;
                    $player_pos[0] = $arena_size-1;
                    $pseudo_rand = mt_rand(0, $arena_size - 1);
                    $player_pos[1] = $pseudo_rand;
                    sleep(1);
                    clearcmd();
                    continue 3;
                }
                echo $player_pos[1] . PHP_EOL . $player_pos[0] . PHP_EOL . $boss_pos . PHP_EOL;
                echo "can't hit the boss, you're too far away" . PHP_EOL;
            break;
            case 'up':
                if ($player_pos[0] == 0) {
                    echo 'cant go outside the area, if you wanna go outside go touch grass' . PHP_EOL;
                    continue 2;
                }
                $player_pos[0]--;
            break 2;
            case 'down':
                if ($player_pos[0] == $arena_size - 1) {
                    echo 'cant go outside the area, if you wanna go outside go touch grass' . PHP_EOL;
                    continue 2;
                }
                $player_pos[0]++;
            break 2;
            case 'left':
                if ($player_pos[1] == 0) {
                    echo 'cant go outside the area, if you wanna go outside go touch grass' . PHP_EOL;
                    continue 2;
                }
                $player_pos[1]--;
            break 2;
            case 'right':
                if ($player_pos[1] == $arena_size - 1) {
                    echo 'cant go outside the area, if you wanna go outside go touch grass' . PHP_EOL;
                    continue 2;
                }
                $player_pos[1]++;
            break 2;
            case 'stay':
            break 2;
            default:
                echo 'didnt understand that, try again' . PHP_EOL;
        }
    }
    // damage
    $dmg = 0;
    switch ($arena[$player_pos[0]][$player_pos[1]]) {
        case 'purple':
            $poisoned = true;
        break;
        case 'red':
            $dmg = 125;
        break;
        case 'green':
            $dmg = 5;
        break;
        case 'yellow':
            $dmg = 45;
        break;
        case 'white':
            $dmg = 1;
        break;
        case 'player':
            $dmg = 25;
    }
    if ($poisoned && $dmg > 0) {//just took dmg after poison
        $dmg = $dmg*2;
        $player_stats['health'][0] -= $dmg;
        $poisoned = false;
    } elseif ($poisoned) {//just got poisoned
        $player_stats['health'][0] -= $dmg;
    } else {//just damage
        $player_stats['health'][0] -= $dmg;
    }
    if ($poisoned) {
        $additional_str = purple(' +poisoned');
    }
    echo "you took $dmg damage " . PHP_EOL;

    sleep(1);

    clearcmd();//replace with clearline once movement implemented

}
if ($player_stats['health'][0] < 1) {
    echo "boss hp remaining: " . $boss_stats['health'] . PHP_EOL;
} else {
    echo 'you won! ' . $player_stats['health'][0] . 'hp left' . PHP_EOL;
}