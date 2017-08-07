<?php
/**
 * Created by PhpStorm.
 * User: helei
 * Date: 2017/5/15
 * Time: 下午4:55
 */

namespace XApi\Utils;


class Console
{
    // foreground color control codes
    const FG_BLACK  = 30;
    const FG_RED    = 31;
    const FG_GREEN  = 32;
    const FG_YELLOW = 33;
    const FG_BLUE   = 34;
    const FG_PURPLE = 35;
    const FG_CYAN   = 36;
    const FG_GREY   = 37;
    // background color control codes
    const BG_BLACK  = 40;
    const BG_RED    = 41;
    const BG_GREEN  = 42;
    const BG_YELLOW = 43;
    const BG_BLUE   = 44;
    const BG_PURPLE = 45;
    const BG_CYAN   = 46;
    const BG_GREY   = 47;
    // fonts style control codes
    const RESET       = 0;
    const NORMAL      = 0;
    const BOLD        = 1;
    const ITALIC      = 3;
    const UNDERLINE   = 4;
    const BLINK       = 5;
    const NEGATIVE    = 7;
    const CONCEALED   = 8;
    const CROSSED_OUT = 9;
    const FRAMED      = 51;
    const ENCIRCLED   = 52;
    const OVERLINED   = 53;

    /**
     * 输出信息到命令行
     * @param string $string
     * @param string $color
     * @return int
     */
    public static function stdout(string $string, string $color = '')
    {
        $code = implode(';', [$color]);

        $return = "\033[0m" . ($code !== '' ? "\033[" . $code . 'm' : '') . $string . "\033[0m";

        return fwrite(\STDOUT, $return);
    }

    public static function stderr(string $string, string $color = '')
    {
        $code = implode(';', [$color]);

        $return = "\033[0m" . ($code !== '' ? "\033[" . $code . 'm' : '') . $string . "\033[0m";

        return fwrite(\STDERR, $return);
    }
}