<?php
namespace York\Helper;

/**
 * translator class for translating
 * usage as static helper methods
 *
 * @package York\Helper
 * @version $version$
 * @author wolxXx
 *
 * @todo provide database, file, json, whatever for translation files
 * @todo do not return just the same string ;)
 */
class Translator
{
    /**
     * translates the given string. accepts sprintf args
     * like %s for replacing arguments
     *
     * @param string            $string
     * @param string | array    $args
     *
     * @return string
     *
     * @throws \York\Exception\Translator
     */
    public static function translate($string, $args = array())
    {
        if (true === empty($args)) {
            return $string;
        }

        try {
            $return = call_user_func_array('sprintf', func_get_args());
        } catch (\Exception $x) {
            $return = false;
        }

        if (false === $return) {
            throw new \York\Exception\Translator('Translator failed! args = ' . implode(' | ', func_get_args()));
        }

        return $return;
    }
}
