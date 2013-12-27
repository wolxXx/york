<?php
namespace York\Request\Api;
/**
 * default api codes
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Request\Api
 */
class Code implements CodeInterface{
	const OK = 200;
	const ERROR = 500;

	/***
	 * retrieves the explaining text for the code
	 *
	 * @param $code
	 * @return string
	 * @throws \York\Exception\UndefinedApiStatus
	 */
	public static function getStatusTextForCode($code){
		switch ($code){
			case self::OK:{
				return 'OK';
			}break;

			case self::ERROR:{
				return 'ERROR';
			}break;

			default:{
				throw new \York\Exception\UndefinedApiStatus(sprintf('%s is not defined! please define own codes!', $code));

			}
		}
	}
}
