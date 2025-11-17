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
function set_tty_icanon(bool $val): void {
    if(PHP_OS === 'Linux') {
        static $termios = null;
        $echo = 10;
        $icanon = 2;
        if($termios === null) {
            $termios = FFI::cdef('
                struct termios
                {
                    unsigned int c_iflag;
                    unsigned int c_oflag;
                    unsigned int c_cflag;
                    unsigned int c_lflag;
                    unsigned char c_line;
                    unsigned char c_cc[32];
                    unsigned int c_ispeed;
                    unsigned int c_ospeed;
                };

                int tcgetattr (int __fd, struct termios *__termios_p);
                int tcsetattr (int __fd, int __optional_actions,
                          const struct termios *__termios_p);', 'libc.so.6');
        }
        $termios_data = $termios->new('struct termios');
        $err = $termios->tcgetattr(1, FFI::addr($termios_data));
        assert($err == 0);
        $lflag = $termios_data->c_lflag;
        $termios_data->c_lflag = ($lflag & ~($icanon | $echo)) | ($val ? ($icanon | $echo) : 0);
        $err = $termios->tcsetattr(1, 0, FFI::addr($termios_data));
        assert($err == 0);
    } else if(strncasecmp(PHP_OS, 'WIN', 3) === 0) {
        static $console = null;
        $echo = 4;
        $line_input = 2;
        if($console === null) {
            $console = FFI::cdef('
                void* GetStdHandle(unsigned long nStdHandle);
                int GetConsoleMode(void* hConsoleHandle, unsigned long* lpMode);
                int SetConsoleMode(void* hConsoleHandle, unsigned long dwMode);', 'kernel32.dll');
        }
        $handle = $console->GetStdHandle(-10);
        assert(!FFI::isNull($handle));
        $mode = $console->new('unsigned long');
        $err = $console->GetConsoleMode($handle, FFI::addr($mode));
        assert($err != 0);
        $mode['cdata'] = ($mode['cdata'] & ~($line_input | $echo)) | ($val ? ($line_input | $echo) : 0);
        $err = $console->SetConsoleMode($handle, $mode);
        assert($err != 0);
    } else {
        throw new Exception("Unsupported platform");
    }
}
function read_char(): string {
    set_tty_icanon(false);
    $char = fgetc(STDIN);
    set_tty_icanon(true);
    return $char;
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