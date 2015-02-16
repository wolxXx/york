<?php
namespace York\Database\Accessor;

/**
 * class for update multiple items from a database table
 *
 * @package York\Database\Accessor
 * @version $version$
 * @author wolxXx
 */
class MultiUpdate
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $conditions;

    /**
     * an instance of the databaseManager
     *
     * @var \York\Database\Manager
     */
    protected $databaseManager;

    /**
     * @param string    $table
     * @param array     $data
     * @param array     $conditions
     */
    public function __construct($table, $data = array(), $conditions = array())
    {
        $this->databaseManager = \York\Dependency\Manager::getDatabaseManager();
        $this->setTable($table);
        $this->setData($data);
        $this->setConditions($conditions);
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string    $key
     * @param mixed     $value
     *
     * @return $this
     */
    public function addData($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $conditions
     *
     * @return $this
     */
    public function setConditions(array $conditions)
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /***
     * @return \York\Database\QueryResult
     */
    public function update()
    {
        $queryBuilder = new \York\Database\QueryBuilder\MultiUpdate($this->getTable(), $this->getData(), $this->getConditions());

        return $this->databaseManager->update($queryBuilder->getQueryString());
    }
}
