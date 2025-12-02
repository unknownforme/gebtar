<?php

function cursorUp ($amount = 1) {
    echo "\033[" . $amount . "A";
}
function cursorDown ($amount = 1) {
    echo "\033[" . $amount . "B";
}

function bg_red ($word) {
    return "\033[41m" . $word . "\033[0m";
}
function red ($word) {
    return "\033[31m".$word."\033[0m";
}
function green ($word) {
    return "\033[32m".$word."\033[0m";
}
function bg_yellow ($word) {
    return "\033[30;43m" . $word . "\033[0m";
}
function texture_printer($texture) {
    foreach ($texture as $texture_part) {
        echo $texture_part . PHP_EOL;
    }
}
$texture_1 = [
    " " . "   ▗    ▗",
    " " . green(" ▗") . "▐▞" . green("▗▄▖") . "▞▞",
    " " . green(" ▟") . bg_yellow(green("▛▟")) . green("▘ ▝▛▘"),
    " " . green("▟") . bg_yellow(green("▚")) . green("██") . red("▐") . green("▄▖▚"),
    " " . green("▘▘▘▝▀ ▄▛"),
    " " . (green("▙")) . green("▀") . (green("▙▙")) . green("▛▀▘"),
];
$texture_2 = [
    " " . "   ▗    ▗",
    " " . green(" ▗") . "▐▞" . green("▗▄▖") . "▞▞",
    " " . green(" ▟") . bg_yellow(green("▛▟")) . green("▘") . red("▖") . green("▝▛▘"),
    " " . green("▟") . bg_yellow(green("▚██")) . red(" ▘") . green("▞▚"),
    " " . green("▘▘▘▝▀▀▄▛"),
    " " . (green("▙")) . green("▀") . (green("▙▙")) . green("▛▀▘"),
];
$texture_turn = [
    " " . "   ▖     ▖",
    " " . "▗▐▞".green("▗▄▖")."▞▞",
    " " . green("▗█"). bg_yellow(green("▛▟")).green("█"). bg_red(green("▛")).green("▝▛▘"),
    " " . green("▞▛").bg_yellow(green("▙")).green("▜█").red("▝").green("▞▚"),
    " " . green("    ▘▘▄▛"),
    " " . green("▙▀▚▙▛▀▘"),
];
$texture_3 = [
    " " . green("   ▗▙▀") . bg_yellow(green("▌")) . green("▀▟▖"),
    " " . green("  ▞   ") . "▄" . green("   ▚"),
    " " . red(" ▌") . green("▚") . "━═███═─" . green("▞") . red("▐"),
    " " . green("  ▝▖  ") . "▀" . green("  ▗▘"),
    " " . green("   ▚▖   ▗▞"),
    " " . green("   ▝▟▞▀▚▙▘"),
];
$texture_4 = [
    " " . green("   ▗▙▀") . (("┃")) . green("▀▟▖"),
    " " . green("  ▞   ") . "║" . green("   ▚"),
    " " . red(" ▌") . green("▚") . "  ▐█▌  " . green("▞") . red("▐"),
    " " . green("  ▝▖  ") . "║" . green("  ▗▘"),
    " " . green("   ▚▖ ") . "┃" . green(" ▗▞"),
    " " . green("   ▝▟▞▀▚▙▘"),
];
$texture_5 = [
    " " . green("   ▗▙▀") . bg_yellow(green("▌")) . green("▀▟▖"),
    " " . green("  ▞  ") . "▄▄▄" . green("  ▚"),
    " " . " ─══█████══─",
    " " . green("  ▝▖ ") . "▀▀▀" . green(" ▗▘"),
    " " . green("   ▚▖   ▗▞"),
    " " . green("   ▝▟▞▀▚▙▘"),
];
$texture_6 = [
    " " . green("   ▗▙▀") . (("║")) . green("▀▟▖"),
    " " . green("  ▞  ") . "▗█▖" . green("  ▚"),
    " " . red(" ▌") . green("▚") . "  ███  " . green("▞") . red("▐"),
    " " . green("  ▝▖ ") . "▝█▘" . green(" ▗▘"),
    " " . green("   ▚▖ ") . "║" . green(" ▗▞"),
    " " . green("   ▝▟▞") . "┃" . green("▚▙▘"),
];
$texture_blast = [
    "    ▄▟████▙▄",
    "   ▟████████▙",
    "  ▐██████████▌",
    "  ▐██████████▌",
    "   ▜████████▛",
    "    ▀▜████▛▀",
];
texture_printer($texture_1);
sleep(1);
cursorUp(7);

texture_printer($texture_2);
sleep(1);
cursorUp(7);
texture_printer($texture_turn);
usleep(300 * 1000);
cursorUp(7);
usleep(200 * 1000);
texture_printer($texture_3);
cursorUp(7);
usleep(200 * 1000);
texture_printer($texture_4);
cursorUp(7);
usleep(200 * 1000);
texture_printer($texture_5);
cursorUp(7);
usleep(200 * 1000);
texture_printer($texture_6);
cursorUp(7);
usleep(200 * 1000);
texture_printer($texture_blast);