<?php
namespace York\Helper;

/**
 * password helper utilities class
 *
 * @package York\Helper
 * @version $version$
 * @author wolxXx
 */
class Password
{
    /**
     * @param string $password
     *
     * @return string
     */
    public static function hashPassword($password)
    {
        if (true === function_exists('password_hash')) {
            return password_hash($password, PASSWORD_DEFAULT);
        }

        return md5($password, PASSWORD_DEFAULT);
    }

    /**
     * @param string    $password
     * @param string    $hash
     *
     * @return boolean
     */
    public static function verifyPassword($password, $hash)
    {
        if(true ===function_exists('password_verify')){
            return password_verify($password, $hash);
        }

        return md5($password) === $hash;
    }

    /**
     * generates a numeric password, PIN, whatever you wanna call it..
     *
     * @param integer $length
     *
     * @return string
     */
    public static function generateNumericPassword($length = 4)
    {
        $password = '';

        while (strlen($password) < $length) {
            $password .= '' . rand(0, 9);
        }

        return $password;
    }

    /**
     * generates a password with length = $length
     * takes all possible ascii numbers and creates characters of them
     * every character is only one time in the password
     *
     * @param integer $length
     *
     * @return string
     */
    public static function generatePassword($length = 9)
    {
        $alphabet = array();

        foreach (range(97, 122) as $ascii) {
            $alphabet[] = chr($ascii);
            $alphabet[] = strtoupper(chr($ascii));
        }

        foreach (range(0, 9) as $number) {
            $alphabet[] = '' . $number;
        }

        foreach (array(range(33, 64), range(91, 96), range(123, 126)) as $array) {
            foreach ($array as $ascii) {
                $alphabet[] = chr($ascii);
            }
        }

        $pass = '';

        while (strlen($pass) < $length) {
            $offset = rand(0, sizeof($alphabet) - 1);
            $pass .= $alphabet[$offset];
            $alphabet = array_merge(array_slice($alphabet, 0, $offset), array_slice($alphabet, $offset + 1, sizeof($alphabet)));
        }

        return $pass;
    }
}
