<?php
namespace York\Database;
/**
 * the plain object item
 * simple database row to object wrapper
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database
 */
class FetchResult{
	/**
	 * global setter for object properties
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value){
		$this->$key = $value;
	}

	/**
	 * getter for a key
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key){
		return $this->$key;
	}

	public function getData(){
		return get_object_vars($this);
	}

	/**
	 * global getter for object properties
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key){
		if(false === property_exists($this, $key)){
			$properties =  implode(', ', array_keys(get_object_vars($this)));
			$message = sprintf('warning: "%s" not found. only got %s', $key, $properties);
			\York\Dependency\Manager::get('logger')->log($message, \York\Logger\Manager::LEVEL_DEBUG);
			return null;
		}
		return $this->$key;
	}

	/**
	 * constructor
	 * needed for serialization
	 */
	public function __construct(){
	}

	/**
	 * needed for serialization
	 */
	public function __wakeup(){
		$this->__construct();
	}

	/**
	 * needed for serialization
	 *
	 * @return array
	 */
	public function __sleep(){
		return array_keys(get_object_vars($this));
	}
}
