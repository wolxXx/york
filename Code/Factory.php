<?php
namespace York\Code;
/**
 * factory pattern class
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Code
 */
class Factory {
	/**
	 * factory function
	 *
	 * @return Factory
	 */
	public static function Factory(){
		$class = get_called_class();
		return new $class();
		return call_user_func_array(array(get_called_class(), '__construct'), func_get_args());
	}

	/**
	 * constructor cannot be public...
	 */
	protected function __construct(){

	}
}
