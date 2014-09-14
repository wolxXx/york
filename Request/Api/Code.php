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
	const SUCCESS = 0;
	const OK = 200;
	const ERROR = 500;

	/***
	 * retrieves the explaining text for the code
	 *
	 * @param $code
	 * @throws \York\Exception\General
	 * @return string
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
				$reflection = new \ReflectionClass(get_called_class());
				if(true === in_array($code, $reflection->getConstants())){
					$flip = array_flip($reflection->getConstants());
					return $flip[$code];
				}

				throw new \York\Exception\General(sprintf('ApiCode "%s" not found', $code));
			}
		}
	}
}
