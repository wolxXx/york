<?php
namespace York\Helper;
/**
 * array helper utilities class
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Helper
 */
class Set{
	/**
	 * merges all given arrays
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 */
	public static function merge(array $array1, array $array2){
		$result = array();
		foreach($array1 as $key => $value){
			$result[$key] = $value;
		}
		foreach($array2 as $key => $value){
			$result[$key] = $value;
		}
		return $result;
	}

	/**
	 * splits an $array into $slots
	 * the first y items go to the first array, the z next to the second array, ...
	 * let's call it the arrayToWurstMachineHellYeahBitch :)
	 * $slots indicates how many arrays should be returned back
	 *
	 * @param array $array
	 * @param integer $slots
	 * @return array
	 */
	public static function array_split($array, $slots){
		return array_chunk($array, ceil(count($array) / $slots));
	}

	/**
	 * repeat an array $times times
	 *
	 * @param $array
	 * @param int $times
	 * @return array
	 */
	public static function array_repeat($array, $times = 1){
		$result = array();
		foreach(range(0, $times) as $counter){
			foreach($array as $value){
				$result[] = $value;
			}
		}

		return $result;
	}

	/**
	 * decorator for arrays
	 * runs through the array and decorates all string values with the given decorate string
	 *
	 * @param array $array
	 * @param string $stringBefore
	 * @param null $stringAfter
	 * @return array
	 */
	public static function decorate(array $array, $stringBefore = '', $stringAfter = null){
		if(null === $stringAfter){
			$stringAfter = $stringBefore;
		}
		foreach($array as $index => $current){
			if(true === is_array($current)){
				$array[$index] = self::decorate($current, $stringBefore, $stringAfter);
				continue;
			}
			if(true === is_string($current)){
				$array[$index] = $stringBefore.$current.$stringAfter;
			}
		}
		return $array;
	}

	/**
	 * removes a value from a flat array
	 * no multi-dimensional arrays are supported here!
	 *
	 * @param $array
	 * @param $value
	 * @param boolean $strict
	 * @return array
	 */
	public static function removeValue($array, $value, $strict = false){
		if(false === in_array($value, $array)){
			return $array;
		}
		$result = array();
		foreach($array as $key => $current){
			if(true === $strict){
				if($current === $value){
					continue;
				}
				$result[$key] = $current;
				continue;
			}
			if($current == $value){
				continue;
			}
			$result[$key] = $current;
		}
		return $result;
	}

	/**
	 * decorator for arrays
	 * runs through the array and decorates all string values with the given decorate string
	 *
	 * @param array $array
	 * @param string $stringBefore
	 * @param null $stringAfter
	 * @return array
	 * @deprecated use \York\Helper\Set::decorate
	 */
	public static function array_decorate($array, $stringBefore = '', $stringAfter = null){
		return self::decorate($array, $stringBefore, $stringAfter);
	}



	/**
	 * computes the difference of two arrays recursively
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 * @see http://www.php.net/manual/en/function.array-diff.php#91756
	 * @deprecated use \York\Helper\Set::recursiveDiff
	 */
	public static function array_diff_recursive($array1, $array2){
		return self::recursiveDiff($array1, $array2);
	}

	/**
	 * computes the difference of two arrays recursively
	 *
	 * @param array $array1
	 * @param array $array2
	 * @return array
	 * @see http://www.php.net/manual/en/function.array-diff.php#91756
	 */
	public static function recursiveDiff($array1, $array2){
		$result = array();
		foreach($array1 as $key => $value){
			if(true === array_key_exists($key, $array2)){
				if(is_array($value)){
					$recursiveDiff = self::recursiveDiff($value, $array2[$key]);
					if(count($recursiveDiff) > 0){
						$result[$key] = $recursiveDiff;
					}
				}else{
					if($value != $array2[$key]){
						$result[$key] = $value;
					}
				}
			}else{
				$result[$key] = $value;
			}
		}
		return $result;
	}
}
