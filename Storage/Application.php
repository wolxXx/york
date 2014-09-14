<?php
namespace York\Storage;
/**
 * a simple key value data storage
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Storage
 *
 */
class Application extends StorageAbstract implements StorageInterface{
	/**
	 * @var \York\Storage\Application
	 */
	protected static $instance;

	/**
	 * setter for a single key value pair
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function set($key, $value){
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * getter for a single key value pair
	 *
	 * @param string $key
	 * @throws \York\Exception\KeyNotFound
	 * @return mixed
	 */
	public function get($key){
		if(false === array_key_exists($key, $this->data)){
			throw new \York\Exception\KeyNotFound('key "'.$key.'" not found in data');
		}

		return $this->data[$key];
	}

	/**
	 * retrieve the whole data
	 *
	 * @return array
	 */
	public function getAll(){
		return $this->data;
	}

	/**
	 * determines if there exist a key
	 * in the data array
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function hasKey($key){
		try{
			$this->get($key);

			return true;
		}catch(\York\Exception\KeyNotFound $x){
			return false;
		}
	}

	/**
	 * shortcut for hasKey
	 *
	 * @param $key
	 * @return boolean
	 */
	public function hasDataForKey($key){
		return $this->hasKey($key);
	}

	/**
	 * overwrites the whole current data array
	 *
	 * @param array $data
	 * @return \York\Storage\Application
	 */
	public function setData($data){
		$this->data = $data;

		return $this;
	}

	/**
	 * adds data to the current data array
	 * if overwrite is set to true, it overwrite existing data keys
	 *
	 * @param array $data
	 * @param boolean $overwrite
	 * @return $this
	 */
	public function addData($data, $overwrite = true){
		$array1 = $data;
		$array2 = $this->data;

		if(true === $overwrite){
			$array1 = $this->data;
			$array2 = $data;
		}

		$this->data = \York\Helper\Set::merge($array1, $array2);

		return $this;
	}


	public function remove($key){
		return $this->removeData($key);
	}

	/**
	 * try safely to get the data for the given ey
	 * if the key is not set, the default value will be returned
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getSafely($key, $default = null){
		try{
			return $this->get($key);
		}catch (\York\Exception\KeyNotFound $exception){
			return $default;
		}
	}

	/**
	 * removes a key value pair
	 *
	 * @param string $key
	 * @return $this
	 */
	public function removeData($key){
		unset($this->data[$key]);

		return $this;
	}

	/**
	 * clears the whole store
	 *
	 * @return $this
	 */
	public function clear(){
		$this->data = array();

		return $this;
	}

	/**
	 * alias for clear
	 *
	 * @return $this
	 */
	public function clearData(){
		return $this->clear();
	}

	/**
	 * removes the data for the key
	 *
	 * @param string $key
	 * @return StorageInterface
	 */
	public function removeKey($key)
	{
		// TODO: Implement removeKey() method.
	}
}
