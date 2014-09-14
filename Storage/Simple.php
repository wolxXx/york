<?php
namespace York\Storage;
/**
 * simple storage
 *
 * @author wolxXx
 * @version 3.1
 * @package York\Storage
 */
class Simple implements StorageInterface{
	/**
	 * the data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * constructor
	 */
	public function __construct(){
		$this->data = array();
	}

	/**
	 * @inheritdoc
	 */
	public function get($key){
		if(false === isset($this->data[$key])){
			throw new \York\Exception\KeyNotFound(sprintf('key %s not set', $key));
		}

		return $this->data[$key];
	}

	/**
	 * @inheritdoc
	 */
	public function getAll(){
		return $this->data;
	}

	/**
	 * @inheritdoc
	 */
	public function set($key, $value){
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function hasDataForKey($key){
		return isset($this->data[$key]);
	}

	/**
	 * @inheritdoc
	 */
	public function getSafely($key, $default = null){
		try{
			return $this->get($key);
		}catch (\York\Exception\KeyNotFound $exception){
			return $default;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function removeKey($key){
		unset($this->data[$key]);
	}

	/**
	 * @inheritdoc
	 */
	public function remove($key){
		$this->data = \York\Helper\Set::removeValue($this->data, $key);
	}

	/**
	 * @inheritdoc
	 */
	public function clear(){
		$this->data = array();

		return $this;
	}

	/**
	 * removes the data if set
	 *
	 * @param mixed $data
	 * @return StorageInterface
	 */
	public function removeData($data)
	{
		// TODO: Implement removeData() method.
	}
}
