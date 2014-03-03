<?php
namespace York\Helper;
/**
 * date helper utilities class
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Helper
 */
class Date{
	/**
	 * default date format
	 *
	 * @var string
	 */
	public static $format = 'Y-m-d H:i:s';

	/**
	 * calculates the difference from to times in seconds
	 *
	 * @param string $time1
	 * @param string $time2
	 * @return integer
	 */
	public static function getRemainingSecondsFromDates($time1, $time2){
		return abs(self::dateToTimestamp($time1) - self::dateToTimestamp($time2));
	}

	/**
	 * checks if the given date is in the past
	 *
	 * @param \DateTime $date
	 * @return boolean
	 */
	public static function isDateInPast($date){
		return self::dateToTimestamp($date) < time();
	}

	/**
	 * echoes a previously formatted given date
	 *
	 * @param string $date
	 */
	public static function renderDate($date){
		echo self::formatDate($date);
	}

	/**
	 *
	 * formats a date to d.m.Y H:i
	 *
	 * @param string $date
	 * @return string
	 */
	public static function formatDate($date){
		$pattern = 'd.m.Y, H:i';
		if(true === $date instanceof \DateTime){
			return $date->format($pattern);
		}
		return date($pattern, self::dateToTimestamp($date));
	}

	/**
	 * returns a date with given format and time
	 * if $format is null, the default is taken
	 * if $time is null, the current timestamp is taken
	 *
	 * @param string $format
	 * @param integer $time
	 * @return string
	 */
	public static function getDate($format = null, $time = null){
		if(null === $format){
			$format = self::$format;
		}
		if(null === $time){
			$time = time();
		}
		return date($format, $time);
	}

	public static function getDateTime($time = null){
		return new \DateTime(self::getDate(null, $time));
	}

	/**
	 * returns a date for a timestamp
	 *
	 * @param string $format
	 * @param integer $time
	 * @return string
	 */
	public static function getDateByTimestamp($format = null, $time = null){
		if(null === $time){
			$time = time();
		}

		if(null === $format){
			$format = self::$format;
		}

		return date($format, $time);
	}

	/**
	 * converts a string (2009-04-24 18:04:12) into an unix timestamp (1234567890)
	 *
	 * @param string $date
	 * @return integer
	 */
	public static function dateToTimestamp($date){
		return strtotime($date);
	}

	/**
	 * makes an unix-timestamp from an utc date and formats it
	 *
	 * @param string $date
	 */
	public static function renderUTCDate($date){
		self::renderDate(date('Y-m-d H:i:s', self::dateToTimestamp($date)));
	}

	/**
	 * creates an utc timestamp
	 *
	 * @param string $date
	 * @return string
	 */
	public static function createUTCDate($date){
		return gmdate('D, d M Y H:i:s +0000', self::dateToTimestamp($date));
	}

	/**
	 * displays the remaining time
	 *
	 * @param integer $seconds
	 * @param boolean $clearLeadingZeros
	 * @return string
	 */
	public static function secondsToRemainingTime($seconds, $clearLeadingZeros = true){
		if(0 == $seconds){
			return 0;
		}

		$date = gmdate ('H:i:s', $seconds);
		if(false === $clearLeadingZeros){
			return $date;
		}

		$split = explode(':', $date);
		$offset = 0;

		while('00' === $split[$offset]){
			unset($split[$offset++]);
		}

		return implode(':', $split);
	}
}
