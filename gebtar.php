<?php

include('functions.php');

clearcmd();
slow_read("damage rules: \nwhite does 1 damage, green does 5 damage,\nyellow does 45 damage, red does 125 damage,\nstaying on the same tile does 25 damage.\npurple does 0 damage, BUT the next damage you take will be doubled. \n\ntile conversion rules: \nyellow becomes red.\nred becomes green.\ngreen becomes yellow on odd turns, or green, yellow, or purple on even turns.\nwhite becomes green, yellow, or purple.\npurple becomes any random color.\n", 30);

$player_actions = [];
$arena_size = 2;
$grid_size = 2;
$player_stats = [
    "health" => [1300,1300],//health -> [current|max]
];
$boss_stats = ['health' => 1000];
$boss_stats_static = ['health' => 1000];
$arena = [];
$arena_pieces = ['red', 'green', 'yellow', 'purple', 'white'];
// make an arena with multiple spots varying in damage
$turn = 0;
$player_pos = [1,0];
//▛▚▞▜
//▙▀▀▟

//▒┃┃▒
//▙▚▞▟
$boss_graphics = [blue('▛▚▞▜'), blue('▙') . red('▘▝') . blue('▟')];
$player_graphics = [blue('╔') . blue('██') . blue('╗'), blue('▟▀▀▙')];
$boss_pos = 2;
$poisoned = false;
$seed = $argv[1] ?? rand(PHP_INT_MIN, PHP_INT_MAX);
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
        echo str_pad("", ($boss_pos * 2 * $grid_size) - ($grid_size * 2), '  ', STR_PAD_LEFT) . $boss_graphics[$boss_looks] . PHP_EOL;
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
    echo 'possible actions: WASD, X to stay, and F/H to hit' . PHP_EOL;
    while (true) {
        if (strtolower(substr(PHP_OS, 0, 3)) === 'win')  {
            $action = trim(shell_exec('choice /C WASXDHF1234 /N /M ""'));
        } else if(exec("stty") !== false) {
            exec("stty cbreak -echo");
            $action = strtoupper(fgetc(STDIN));
            exec("stty -cbreak echo");
        } else {
            echo "Unsupported platform >:( (this is shitty code lmfao)";
            exit(1);
        }
        $player_actions[$arena_size-3][] = $action;
        switch ($action) {
            case 'F':
            case 'H':
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
            case 'W':
                if ($player_pos[0] == 0) {
                    echo 'cant go outside the area, if you wanna go outside go touch grass' . PHP_EOL;
                    continue 2;
                }
                $player_pos[0]--;
            break 2;
            case 'S':
                if ($player_pos[0] == $arena_size - 1) {
                    echo 'cant go outside the area, if you wanna go outside go touch grass' . PHP_EOL;
                    continue 2;
                }
                $player_pos[0]++;
            break 2;
            case 'A':
                if ($player_pos[1] == 0) {
                    echo 'cant go outside the area, if you wanna go outside go touch grass' . PHP_EOL;
                    continue 2;
                }
                $player_pos[1]--;
            break 2;
            case 'D':
                if ($player_pos[1] == $arena_size - 1) {
                    echo 'cant go outside the area, if you wanna go outside go touch grass' . PHP_EOL;
                    continue 2;
                }
                $player_pos[1]++;
            break 2;
            case 'X':
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

    clearcmd();


}
if ($player_stats['health'][0] < 1) {
    echo "boss hp remaining: " . $boss_stats['health'] . PHP_EOL;
} else {
    echo 'you won! ' . $player_stats['health'][0] . 'hp left' . PHP_EOL;
}
$total_file_content = [$seed, $player_actions];
file_put_contents('recorded_actions'.time().'.txt', serialize($total_file_content));
