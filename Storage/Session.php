<?php
namespace York\Storage;

/**
 * session storage
 * useful for user / login data
 *
 * @package \York\Storage
 * @version $version$
 * @author wolxXx
 */
class Session extends \York\Storage\StorageAbstract implements \York\Storage\StorageInterface
{
    /**
     * constructor
     * starts a session
     * only callable from here
     */
    public function __construct()
    {
        if (false === defined('STDIN') && '' === session_id()) {
            session_start();
        }

        $this->data =& $_SESSION['York'];

        if (null === $this->data) {
            $this->data = array();
        }
    }

    /**
     * @inheritdoc
     */
    public function shutDown()
    {
        if ('' !== session_id()) {
            session_destroy();
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function hasDataForKey($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @inheritdoc
     */
    public function get($key, $default = null)
    {
        if (true === array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $default;
    }

    /**
     * @inheritdoc
     */
    public function getSafely($key, $default = null)
    {
        return $this->get($key, $default);
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
    public function remove($key)
    {
        return $this->unsetKey($key);
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
        if (true === array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        }

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
