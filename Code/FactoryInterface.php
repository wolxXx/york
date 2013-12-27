<?php
namespace York\Code;
/**
 * factory pattern interface
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Code
 */
interface FactoryInterface {
	/**
	 * factory function
	 *
	 * @return FactoryInterface
	 */
	public static function Factory();
}
