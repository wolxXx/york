<?php
namespace York\Storage;

/**
 * a simple key value data storage
 *
 * @package \York\Storage
 * @version $version$
 * @author wolxXx
 */
class Application extends \York\Storage\StorageAbstract implements \York\Storage\StorageInterface
{
    /**
     * @var \York\Storage\Application
     */
    protected static $instance;

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        if (false === array_key_exists($key, $this->data)) {
            throw new \York\Exception\KeyNotFound('key "' . $key . '" not found in data');
        }

        return $this->data[$key];
    }

    /**
     * @inheritdoc
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * determines if there exist a key
     * in the data array
     *
     * @param string $key
     *
     * @return boolean
     */
    public function hasKey($key)
    {
        try {
            $this->get($key);

            return true;
        } catch (\York\Exception\KeyNotFound $x) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function hasDataForKey($key)
    {
        return $this->hasKey($key);
    }

    /**
     * overwrites the whole current data array
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * adds data to the current data array
     * if overwrite is set to true, it overwrite existing data keys
     *
     * @param array     $data
     * @param boolean   $overwrite
     *
     * @return $this
     */
    public function addData(array $data, $overwrite = true)
    {
        $array1 = $data;
        $array2 = $this->data;

        if (true === $overwrite) {
            $array1 = $this->data;
            $array2 = $data;
        }

        $this->data = \York\Helper\Set::merge($array1, $array2);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        return $this->removeData($key);
    }

    /**
     * @inheritdoc
     */
    public function getSafely($key, $default = null)
    {
        try {
            return $this->get($key);
        } catch (\Exception $exception) {

        }

        return $default;
    }

    /**
     * @inheritdoc
     */
    public function removeData($key)
    {
        unset($this->data[$key]);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        $this->data = array();

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeKey($key)
    {
        return $this->remove($key);
    }
}
