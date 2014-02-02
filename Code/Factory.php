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
	}

	/**
	 * constructor cannot be public...
	 */
	protected function __construct(){

	}
}
