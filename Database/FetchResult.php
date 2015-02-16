<?php
namespace York\Database;

/**
 * the plain object item
 * simple database row to object wrapper
 *
 * @package York\Database
 * @version $version$
 * @author wolxXx
 */
class FetchResult
{
    /**
     * global setter for object properties
     *
     * @param string    $key
     * @param mixed     $value
     */
    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * getter for a key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->$key;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return get_object_vars($this);
    }

    /**
     * global getter for object properties
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        if (false === property_exists($this, $key)) {
            $properties = implode(', ', array_keys(get_object_vars($this)));
            $message = sprintf('warning: "%s" not found. only got %s', $key, $properties);
            \York\Dependency\Manager::getLogger()->log($message, \York\Logger\Level::DEBUG);

            return null;
        }

        return $this->$key;
    }

    /**
     * constructor
     * needed for serialization
     */
    public function __construct()
    {
    }

    /**
     * needed for serialization
     */
    public function __wakeup()
    {
        $this->__construct();
    }

    /**
     * needed for serialization
     *
     * @return array
     */
    public function __sleep()
    {
        return array_keys(get_object_vars($this));
    }
}
