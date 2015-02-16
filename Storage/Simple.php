<?php
namespace York\Storage;

/**
 * simple storage
 *
 * @package \York\Storage
 * @version $version$
 * @author wolxXx
 */
class Simple extends \York\Storage\StorageAbstract implements \York\Storage\StorageInterface
{
    /**
     * the data
     *
     * @var array
     */
    protected $data;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->data = array();
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        if (false === isset($this->data[$key])) {
            throw new \York\Exception\KeyNotFound(sprintf('key %s not set', $key));
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
    public function getSafely($key, $default = null)
    {
        try {
            return $this->get($key);
        } catch (\York\Exception\KeyNotFound $exception) {

        }

        return $default;
    }

    /**
     * @inheritdoc
     */
    public function removeKey($key)
    {
        unset($this->data[$key]);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
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
    public function removeData($data)
    {
        unset($this->data[$key]);

        return $this;
    }
}
