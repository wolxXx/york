<?php
namespace York\Storage;

/**
 * abstract class for storages
 *
 * @package \York\Storage
 * @version $version$
 * @author wolxXx
 */
abstract class StorageAbstract implements StorageInterface
{
    /**
     * the stored data
     *
     * @var array
     */
    protected $data = array();

    /**
     * retrieve the hole data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * usually this is not needed, but sometimes, so overwrite this if you need!
     */
    public function shutDown()
    {
    }

    /**
     * @inheritdoc
     */
    public function setFromArray($array)
    {
        foreach ($array as $key => $value) {
            $this->set($key, $value);
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
}
