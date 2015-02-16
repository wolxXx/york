<?php
namespace York\Helper;

/**
 * array helper utilities class
 *
 * @package York\Helper
 * @version $version$
 * @author wolxXx
 */
class Set
{
    /**
     * returns the subset of an array
     *
     * @param array     $array
     * @param integer   $items
     * @param integer   $offset
     *
     * @return array
     */
    public static function subSet($array, $items = 3, $offset = 0)
    {
        return array_slice($array, $offset, $items, false);
    }

    /**
     * merges all given arrays
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     */
    public static function merge(array $array1, array $array2)
    {
        return array_merge($array1, $array2);
    }

    /**
     * splits an $array into $slots
     * the first y items go to the first array, the z next to the second array, ...
     * let's call it the arrayToWurstMachineHellYeahBitch :)
     * $slots indicates how many arrays should be returned back
     *
     * @param array     $array
     * @param integer   $slots
     *
     * @return array
     */
    public static function array_split(array $array, $slots)
    {
        if (true === empty($array)) {
            return array();
        }

        return array_chunk($array, ceil(count($array) / $slots));
    }

    /**
     * repeat an array $times times
     *
     * @param array     $array
     * @param integer   $times
     *
     * @return array
     */
    public static function array_repeat($array, $times = 1)
    {
        $result = array();

        foreach (range(0, $times) as $counter) {
            foreach ($array as $value) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * decorator for arrays
     * runs through the array and decorates all string values with the given decorate string
     *
     * @param array         $array
     * @param string        $stringBefore
     * @param null | string $stringAfter
     *
     * @return array
     */
    public static function decorate(array $array, $stringBefore = '', $stringAfter = null)
    {
        if (null === $stringAfter) {
            $stringAfter = $stringBefore;
        }

        foreach ($array as $index => $current) {
            if (true === is_array($current)) {
                $array[$index] = self::decorate($current, $stringBefore, $stringAfter);

                continue;
            }

            if (true === is_string($current)) {
                $array[$index] = $stringBefore . $current . $stringAfter;
            }
        }

        return $array;
    }

    /**
     * removes a value from a flat array
     * no multi-dimensional arrays are supported here!
     *
     * @param array     $array
     * @param mixed     $value
     * @param boolean   $strict
     *
     * @return array
     */
    public static function removeValue($array, $value, $strict = false)
    {
        if (false === in_array($value, $array)) {
            return $array;
        }

        $result = array();

        foreach ($array as $key => $current) {
            if (true === $strict) {
                if ($current === $value) {
                    continue;
                }

                $result[$key] = $current;

                continue;
            }
            if ($current == $value) {
                continue;
            }

            $result[$key] = $current;
        }

        return $result;
    }

    /**
     * computes the difference of two arrays recursively
     *
     * @param array $array1
     * @param array $array2
     *
     * @return array
     *
     * @see http://www.php.net/manual/en/function.array-diff.php#91756
     */
    public static function recursiveDiff($array1, $array2)
    {
        $result = array();

        foreach ($array1 as $key => $value) {
            if (true === array_key_exists($key, $array2)) {
                if (is_array($value)) {
                    $recursiveDiff = self::recursiveDiff($value, $array2[$key]);

                    if (count($recursiveDiff) > 0) {
                        $result[$key] = $recursiveDiff;
                    }
                } else {
                    if ($value != $array2[$key]) {
                        $result[$key] = $value;
                    }
                }
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
