<?php
namespace York\Database;

/**
 * Cache class for models
 *
 * @package York\Database
 * @version $version$
 * @author wolxXx
 */
class Cache
{
    /**
     * simple stack
     *
     * @var array
     */
    protected $stack;

    /**
     * init
     */
    public function __construct()
    {
        $this->stack = array();
    }

    /**
     * @param string            $class
     * @param integer | string  $id
     *
     * @return null | \York\Database\Model\Item
     */
    public function get($class, $id)
    {
        $class = ltrim($class, '\\');

        if (true === isset($this->stack[$class]) && true === isset($this->stack[$class][$id])) {
            return $this->stack[$class][$id];
        }

        return null;
    }

    /**
     * @return \York\Database\Model\Item[]
     */
    public function getAll()
    {
        return $this->stack;
    }

    /**
     * @param \York\Database\Model\Item $model
     *
     * @return $this
     */
    public function remove($model)
    {
        if (null !== $this->get(get_class($model), $model->getId())) {
            unset($this->stack[get_class($model)][$model->getId()]);
        }

        return $this;
    }

    /**
     * @param \York\Database\Model\Item $model
     *
     * @return $this
     */
    public function set($model)
    {
        $class = get_class($model);
        ltrim($class, '\\');
        $this->initForClass($class);
        $this->stack[$class][$model->getId()] = $model;

        return $this;
    }

    /**
     * @param \York\Database\Model\Item[] $models
     *
     * @return $this
     */
    public function addMultiple($models)
    {
        foreach ($models as $model) {
            $this->set($model);
        }

        return $this;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    protected function initForClass($class)
    {
        if (false === isset($this->stack[$class])) {
            $this->resetForClass($class);
        }

        return $this;
    }

    /**
     * reset a single section
     *
     * @param string $class
     *
     * @return $this
     */
    public function resetForClass($class)
    {
        $this->stack[$class] = array();

        return $this;
    }

    /**
     * reset all sections
     *
     * @return $this
     */
    public function resetAll()
    {
        $this->stack = array();

        return $this;
    }
}
