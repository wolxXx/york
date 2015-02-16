<?php
namespace York\Console;

/**
 * styles for console
 *
 * @package York\Console
 * @version $version$
 * @author wolxXx
 */
class Style
{
    const FOREGROUND_BLACK = '0;30';
    const FOREGROUND_DARK_GRAY = '1;30';
    const FOREGROUND_BLUE = '0;34';
    const FOREGROUND_LIGHT_BLUE = '1;34';
    const FOREGROUND_GREEN = '0;32';
    const FOREGROUND_LIGHT_GREEN = '1;32';
    const FOREGROUND_CYAN = '0;36';
    const FOREGROUND_LIGHT_CYAN = '1;36';
    const FOREGROUND_RED = '0;31';
    const FOREGROUND_LIGHT_RED = '1;31';
    const FOREGROUND_PURPLE = '0;35';
    const FOREGROUND_LIGHT_PURPLE = '1;35';
    const FOREGROUND_BROWN = '0;33';
    const FOREGROUND_YELLOW = '1;33';
    const FOREGROUND_LIGHT_GRAY = '0;37';
    const FOREGROUND_WHITE = '1;37';

    const BACKGROUND_BLACK = '40';
    const BACKGROUND_RED = '41';
    const BACKGROUND_GREEN = '42';
    const BACKGROUND_YELLOW = '43';
    const BACKGROUND_BLUE = '44';
    const BACKGROUND_MAGENTA = '45';
    const BACKGROUND_CYAN = '46';
    const BACKGROUND_LIGHT_GRAY = '47';

    const STYLE_NORMAL = '0';
    const STYLE_BOLD = '1';
    const STYLE_UNDERLINE = '4';
    const STYLE_INVERSE = '6';
    const STYLE_HIDDEN = '8';

    /**
     * styles a string
     *
     * @param string            $string
     * @param string | null     $foreGround
     * @param string | null     $background
     * @param string | null     $intense
     *
     * @return string
     */
    public static function styleString($string, $foreGround = null, $background = null, $intense = null)
    {
        $foreGround = null === $foreGround ? '' : sprintf("\033[%sm", $foreGround);
        $background = null === $background ? '' : sprintf("\033[%sm", $background);
        $intense = null === $intense ? '' : sprintf("\033[%sm", $intense);

        return sprintf("%s%s%s%s\033[0m", $foreGround, $background, $intense, $string);
    }
}
































