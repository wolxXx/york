<?php
namespace York\Storage;
/**
 * session storage
 * useful for user / login data
 * @package York\Storage
 */
class Session extends StorageAbstract implements StorageInterface{
	/**
	 * constructor
	 * starts a session
	 * only callable from here
	 */
	protected function __construct(){
		if(false === defined('STDIN') && '' === session_id()){
			session_start();
		}
		$this->data =& $_SESSION['York'];
		if(null === $this->data){
			$this->data = array();
		}
	}

	/**
	 * shutdown the session on destroy
	 *
	 * @return \York\Storage\Session
	 */
	public function shutDown(){
		if('' !== session_id()){
			session_destroy();
		}
		return $this;
	}

	/**
	 * checks if the key exists in the storage
	 *
	 * @param $key
	 * @return boolean
	 */
	public function hasDataForKey($key){
		return isset($this->data[$key]);
	}

	/**
	 * gets the value for the provided key or null if not exists
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null){
		if(true === array_key_exists($key, $this->data)){
			return $this->data[$key];
		}

		return $default;
	}

	/**
	 * shortcut for getter
	 *
	 * @param $key
	 * @param $default
	 * @return mixed
	 */
	public function getSafely($key, $default){
		return $this->get($key, $default);
	}

	/**
	 * returns the whole stack
	 *
	 * @return array
	 */
	public function getAll(){
		return $this->data;
	}

	/**
	 * set a value for a key
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \York\Storage\Session
	 */
	public function set($key, $value){
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * unset a key in the stack
	 *
	 * @param string $key
	 * @return \York\Storage\Session
	 */
	public function unsetKey($key){
		if(true === array_key_exists($key, $this->data)){
			unset($this->data[$key]);
		}

		return $this;
	}

	/**
	 * shortcut for unsetKey
	 *
	 * @param $key
	 * @return \York\Storage\Session
	 */
	public function remove($key){
		return $this->unsetKey($key);
	}

	/**
	 * clears the stack
	 * caution: will clear everything!
	 *
	 * @return \York\Storage\Session
	 */
	public function clear(){
		$this->data = array();
		return $this;
	}
}
