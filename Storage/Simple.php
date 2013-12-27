<?php
namespace York\Storage;
use York\Exception\KeyNotFound;
use York\Helper\Set;

/**
 * simple storage
 *
 * @author wolxXx
 * @version 3.0
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
			throw new KeyNotFound(sprintf('key % not set', $key));
		}

		return $this->data['key'];
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
		}catch (KeyNotFound $exception){
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
		$this->data = Set::removeValue($this->data, $key);
	}

	/**
	 * @inheritdoc
	 */
	public function clear(){
		$this->data = array();

		return $this;
	}
}
