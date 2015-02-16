<?php
namespace York\Database\QueryBuilder;

/**
 * query builder for deleting items in the database
 *
 * @package \York\Database\QueryBuilder
 * @version $version$
 * @author wolxXx
 */
class MultiDelete extends \York\Database\QueryBuilder
{
    /**
     * the name of the table
     *
     * @var string
     */
    protected $table;

    /**
     * an instance of the DatabaseManager
     *
     * @var \York\Database\Manager
     */
    protected $databaseManager;

    /**
     * the conditions array
     *
     * @var array
     */
    protected $conditions;

    /**
     * constructor
     *
     * @param string    $table
     * @param array     $conditions
     */
    public function __construct($table = null, $conditions = null, $databaseManager = null)
    {
        $this
            ->setTable($table)
            ->setConditions($conditions)
            ->setDatabaseManager($databaseManager);

    }

    /**
     * @param \York\Database\Manager | null $databaseManager
     *
     * @return \York\Database\QueryBuilder\MultiDelete
     */
    public function setDatabaseManager($databaseManager = null)
    {
        if (null === $databaseManager) {
            $databaseManager = \York\Dependency\Manager::getDatabaseManager();
        }

        $this->databaseManager = $databaseManager;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function checkConditions()
    {
        if (null === $this->conditions) {
            throw new \York\Exception\QueryGenerator('please specify conditions');
        }

        if (true === empty($this->conditions)) {
            throw new \York\Exception\QueryGenerator('please specify conditions');
        }
    }

    /**
     * @inheritdoc
     */
    public function generateQuery()
    {
        $this->checkConditions();
        $where = $this->generateWhere();
        $query = sprintf("DELETE FROM `%s` WHERE %s;", $this->table, $where);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function getQueryString()
    {
        return new \York\Database\QueryBuilder\QueryString($this->generateQuery());
    }

    /**
     * setter for the table name
     *
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
     * @inheritdoc
     */
    public function setConditions($conditions)
    {
        $this->conditions = array('where' => $conditions);

        return $this;
    }
}
