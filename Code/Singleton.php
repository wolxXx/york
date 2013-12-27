<?php
namespace York\Code;
/**
 * abstract class for singleton pattern
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Code
 */
abstract class Singleton{
	/**
	 * the instance
	 *
	 * @var Singleton
	 */
	protected static $instance;

	/**
	 * get the instance
	 *
	 * @return Singleton
	 */
	public static function getInstance(){
		if(null === self::$instance){
			$class = get_called_class();
			self::$instance = new $class;
		}
		return self::$instance;
	}

	/**
	 * get a fresh instance
	 *
	 * @return Singleton
	 */
	public static function getClearInstance(){
		if(null !== self::$instance){
			self::$instance->shutdown();
		}
		self::$instance = null;
		return self::getInstance();
	}

	/**
	 * shutdown yourself
	 *
	 * @return Singleton
	 */
	abstract public function shutDown();
}
