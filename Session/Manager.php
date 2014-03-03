<?php
namespace York\Session;
/**
 * a manager for session data read and write
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Session
 */
class Manager {
	/**
	 * singleton instance of the manager
	 *
	 * @var \York\Session\Manager
	 */
	protected static $instance;

	/**
	 * storage for the data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * getter for the singleton instance
	 *
	 * @return \York\Session\Manager
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * create new instance
	 * set the internal data to session reference
	 */
	private function __construct(){
		$this->data = &$_SESSION;
	}

	/**
	 * retrieves the value for the key
	 * if no data exists for the given key, it returns the default value
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function read($key, $default = null){
		if(true === array_key_exists($key, $this->data)){
			return $this->data[$key];
		}

		return $default;
	}

	/**
	 * writes data to the session
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \York\Session\Manager
	 */
	public function write($key, $value){
		$this->data[$key] = $value;
		return $this;
	}
} 