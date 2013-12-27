<?php
namespace York\Storage;
/**
 * interface for storages
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Storage
 */
interface StorageInterface {
	/**
	 * getter for data
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);

	/**
	 * getter for all data
	 *
	 * @return array
	 */
	public function getAll();

	/**
	 * setter for data
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return StorageInterface
	 */
	public function set($key, $value);

	/**
	 * checker if the data exists for the given key
	 *
	 * @param $key
	 * @return boolean
	 */
	public function hasDataForKey($key);

	/**
	 * get safely data from the storage
	 * if no data exists for the key, the given default value is returned
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getSafely($key, $default);

	/**
	 * remove the set data for the key
	 *
	 * @param string $key
	 * @return StorageInterface
	 */
	public function remove($key);

	/**
	 * clears all set data
	 *
	 * @return StorageInterface
	 */
	public function clear();
}
