<?php
namespace York\Helper;
/**
 * string helper utilities class
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Helper
 */
class String {
	/**
	 * checks if a string starts with another string
	 *
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	public static function startsWith($haystack, $needle){
		return $needle === substr($haystack, 0, strlen($needle));
	}

	/**
	 * checks if a string ends with another string
	 *
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	public static function endsWith($haystack, $needle){
		return $needle === substr($haystack,  strlen($haystack) - strlen($needle));
	}

	/**
	 * retrieves the class name from a namespace string
	 *
	 * @param string $className
	 * @return string
	 */
	public static function getClassNameFromNamespace($className){
		$className = explode('\\', $className);
		$className = end($className);
		return $className;
	}

	/**
	 * normalizes a string
	 * clears german umlauts for international ascii compatible keyboards
	 *
	 * @param string $input
	 * @return string
	 */
	public static function normalizeString($input){
		return lcfirst(str_replace(array('ä', 'ö', 'ü', 'ß'), array('ae', 'oe', 'ue', 'ss'), $input));
	}

	/**
	 * cleans a string for german only characters and whitespaces
	 *
	 * @param string $input
	 * @return mixed
	 */
	public static function cleanString($input){
		return str_replace(array('ä', 'ö', 'ü', ' ', 'ß'), array('ae', 'oe', 'ue', '_', 'ss'), $input);
	}

	/**
	 * removes a single html tag from a given string
	 * @param string $text
	 * @param string $tag
	 * @return string
	 */
	public static function removeSingleTagFromText($text, $tag) {
		$str = preg_replace("#\<".$tag."(.*)>#iUs", "", $text);
		$tag = '/'.$tag;
		$str = preg_replace("#\<".$tag."(.*)>#iUs", "", $str);
		return $str;
	}

	/**
	 * removes all html tags from a given string
	 * @param string $text
	 * @return string
	 */
	public static function removeTagsFromText($text) {
		$result = preg_replace("#<(.*)>#iUs", "", $text);
		return $result;
	}

	/**
	* converts a pascal or camel case string into lowercase underscored string
	*
	* @param string $string
	* @return string
	*/
	public static function pascalCaseToUnderscores($string){
		$return = '';
		for($i = 0; $i < strlen($string); $i++){
			if($i > 0 && ord($string[$i]) > 64 && ord($string[$i]) < 91){
				$return .= '_';
			}
			$return .= strtolower($string[$i]);
		}
		return $return;
	}

	/**
	 * converts a underscore string to a pascal string
	 * like google_sucks => GoogleSucks
	 * @param string $string
	 * @return string
	 */
	public static function underscoresToPascalCase($string){
		$return = '';
		$nextBigThing = true;
		for($i = 0; $i < strlen($string); $i++){
			if('_' === $string[$i]){
				$nextBigThing = true;
				continue;
			}
			if(true === $nextBigThing){
				$return .= strtoupper($string[$i]);
				$nextBigThing = false;
				continue;
			}
			$return .= strtolower($string[$i]);
		}
		return $return;
	}

	/**
	 * adds a tailing slash to the string if it does not have one
	 *
	 * @param string $string
	 * @return string
	 */
	public static function addTailingSlashIfNeeded($string){
		if(DIRECTORY_SEPARATOR !== $string[strlen($string)-1]){
			return $string.DIRECTORY_SEPARATOR;
		}
		return $string;
	}


	/**
	 * checks if the syntax of the given email adress is ok
	 * @param string $mail
	 * @return boolean
	 */
	public static function isMailSyntaxOk($mail){
		return false !== filter_var($mail, FILTER_VALIDATE_EMAIL);
	}

	/**
	 * checks if the syntax of the given string is a valid url
	 * @param string $url
	 * @return boolean
	 */
	public static function isURLSyntaxOk($url){
		return false !== filter_var($url, FILTER_VALIDATE_URL);
	}


	/**
	 * converts the float point to comma
	 * @param string $value
	 * @return number
	 */
	public static function floatToDecimal($value){
		return str_replace('.', ',', $value);
	}
}
