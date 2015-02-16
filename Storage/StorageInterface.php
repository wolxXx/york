<?php
namespace York\Storage;
/**
 * interface for storage
 *
 * @package \York\Storage
 * @version $version$
 * @author wolxXx
 */
interface StorageInterface
{
    /**
     * getter for data
     *
     * @param string $key
     *
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
     * @param string    $key
     * @param mixed     $value
     *
     * @return $this
     */
    public function set($key, $value);

    /**
     * @param array $array
     *
     * @return $this
     */
    public function setFromArray($array);

    /**
     * checker if the data exists for the given key
     *
     * @param string $key
     *
     * @return boolean
     */
    public function hasDataForKey($key);

    /**
     * get safely data from the storage
     * if no data exists for the key, the given default value is returned
     *
     * @param string    $key
     * @param mixed     $default
     *
     * @return mixed
     */
    public function getSafely($key, $default = null);

    /**
     * remove the set data for the key
     *
     * @param string $key
     *
     * @return $this
     */
    public function remove($key);

    /**
     * removes the data for the key
     *
     * @param string $key
     *
     * @return $this
     */
    public function removeKey($key);

    /**
     * removes the data if set
     *
     * @param array $data
     *
     * @return $this
     */
    public function removeData($data);

    /**
     * clears all set data
     *
     * @return $this
     */
    public function clear();
}
