<?php
namespace York\Database;

/**
 * container for a single migration
 *
 * @package York\Database
 * @version $version$
 * @author wolxXx
 */
abstract class Migration
{
    /**
     * continuous number
     * make sure, the revision is really continuous and unique!!
     *
     * @var integer
     */
    protected $revision;

    /**
     * a direct connection to the database
     *
     * @var \York\Database\Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $tableName = 'migrations';

    /**
     * constructor
     *
     * @param integer   $revision
     */
    public function __construct($revision)
    {
        $this->setRevision($revision);
        $this->connection = \York\Dependency\Manager::getDatabaseManager()->getConnection();
    }

    /**
     * setter for the revision number
     *
     * @param integer   $revision
     *
     * @return $this
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;

        return $this;
    }

    /**
     * getter for the revision number
     *
     * @return integer
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * after run hook
     *
     * @return $this
     */
    public final function afterRun()
    {
        $this->insertMigrationToDB();

        return $this;
    }

    /**
     * inserts migration number on top into database table migrations
     *
     * @return Migration
     */
    protected final function insertMigrationToDB()
    {
        \York\Database\Accessor\Factory::getSaveObject($this->tableName)
            ->set('number', $this->getRevision())
            ->set('created', \York\Helper\Date::getDate())
            ->save();

        return $this;
    }

    /**
     * this is where the main procedure takes place. drop your code here!
     */
    public abstract function run();

    /**
     * bridge to the database manager
     *
     * @param string    $query
     *
     * @return \York\Database\QueryResult
     */
    protected function query($query)
    {
        $result = null;

        try {
            $result = \York\Dependency\Manager::getDatabaseManager()->query(new \York\Database\QueryBuilder\QueryString($query));
            \York\Helper\Application::debug($result);

            if (false === $result->queryWasSuccessful()) {
                throw new \York\Exception\General('failed to execute migration ' . $this->getRevision());
            }
        } catch (\York\Exception\General $x) {
            \York\Helper\Application::debug($x, $result);

            exit(1);
        }

        return $result;
    }
}
