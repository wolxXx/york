<?php
namespace York\Database\Model;

/**
 * model manager
 *
 * @package York\Database\Model
 * @version $version$
 * @author wolxXx
 */
abstract class Manager implements ManagerInterface
{
    /**
     * name of the referring table in the database
     *
     * @var string
     */
    protected $tableName;

    /**
     * name of the blueprint class
     *
     * @var string
     */
    protected $blueprint;

    /**
     * set up
     */
    public final function __construct()
    {
        $this->model = new \York\Database\Model();
    }

    /**
     * creates a new instance
     *
     * @param string $class
     *
     * @return \York\Database\Blueprint\ItemInterface
     *
     * @throws \York\Exception\Autoload
     */
    protected static function getClassInstance($class)
    {
        if (false === \York\Autoload\Manager::isLoadable($class)) {
            throw new \York\Exception\Autoload(sprintf('cannot load %s as blueprint in %s', $class, __CLASS__));
        }


        return new $class();
    }

    /**
     * match the declared variable type
     * grab a new instance of the blueprint if needed
     *
     * @param \ReflectionProperty   $property
     * @param array                 $data
     * @param boolean               $preventReferencing
     *
     * @return mixed
     */
    protected function matchDeclaredVar(\ReflectionProperty $property, array $data, $preventReferencing = false)
    {
        $name = $property->getName();

        $comment = str_replace('*', '', $property->getDocComment());
        $comment = str_replace('/', '', $comment);
        $comment = trim($comment);

        $type = null;
        $identifiedBy = null;

        foreach (explode(PHP_EOL, $comment) as $part) {
            $part = trim($part);

            if (false !== strstr($part, '@var')) {
                $type = explode(' ', str_replace('@var', '', $part));
                $type = $type[1];

                continue;
            }

            if (false !== strstr($part, '@identifiedBy')) {
                $identifiedBy = explode(' ', str_replace('@identifiedBy', '', $part));
                $identifiedBy = $identifiedBy[1];

                continue;
            }
        }

        if (true === array_key_exists($name, $data)) {
            $value = $data[$name];

            if ('string' === $type) {
                return $value;
            }

            if ('integer' === $type) {
                return (int)$value;
            }

            if ('\DateTime' === $type) {
                if (null === $value || '0000-00-00 00:00:00' === $value) {
                    return null;
                }

                return new \DateTime($value);
            }

            if ('boolean' === $type) {
                return '1' === $value ? true : false;
            }
        }

        if (true === $preventReferencing || false === isset($identifiedBy) || null === $identifiedBy || false === isset($data[$identifiedBy])) {
            return null;
        }

        $blueprintSave = $this->blueprint;
        $this->blueprint = $type;


        $tableNameSave = $this->tableName;
        $this->tableName = str_replace('id_', '', $identifiedBy);


        $instance = $this->getById($data[$identifiedBy], true);

        $this->tableName = $tableNameSave;
        $this->blueprint = $blueprintSave;

        return $instance;
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        $cached = \York\Dependency\Manager::getModelCache()->get($this->blueprint, $id);

        if (null !== $cached) {
            return $cached;
        }

        $result = $this->model->findOne($this->tableName, $id);

        if (null === $result) {
            return null;
        }

        $created = $this->createByResult($result);
        \York\Dependency\Manager::getModelCache()->set($created);

        return $created;
    }

    /**
     * @param integer[] $ids
     *
     * @return \York\Database\Blueprint\ItemInterface[]
     */
    public function findByIds(array $ids)
    {
        $results = $this->find(new \York\Database\QueryBuilder\Select(array(
            'from' => array(
                $this->getTableName()
            ),
            'where' => array(
                'IN' => array(
                    'id' => $ids
                )
            )
        )));

        \York\Dependency\Manager::getModelCache()->addMultiple($results);

        return $results;
    }

    /**
     * @param string    $field
     * @param mixed     $value
     *
     * @return null | \York\Database\Blueprint\ItemInterface
     */
    public function getBy($field, $value)
    {
        $results = $this->findBy($field, $value);

        if (true === empty($results)) {
            return null;
        }

        return $results[0];
    }

    /**
     * @param string    $field
     * @param mixed     $value
     *
     * @return \York\Database\Blueprint\ItemInterface[]
     */
    public function findBy($field, $value)
    {
        return $this->find(new \York\Database\QueryBuilder\Select(
            array(
                'from' => array(
                    $this->getTableName()
                ),
                'where' => array(
                    $field => $value
                )
            )
        ));
    }

    /**
     * create a model by the fetch result from the database
     *
     * @param \York\Database\FetchResult    $result
     * @param boolean                       $preventReferencing
     *
     * @return null|\York\Database\Blueprint\ItemInterface
     */
    protected function createByResult($result, $preventReferencing = false)
    {
        if (null === $result) {
            return null;
        }

        $resultData = $result->getData();

        /**
         * @var \York\Database\Model\Item $instance
         */
        $instance = null;

        try {
            $instance = $this->getClassInstance($this->blueprint);
        } catch (\York\Exception\Autoload $exception) {
            return null;
        }

        $reflection = new \ReflectionClass($instance);

        foreach ($reflection->getProperties() as $property) {
            if (true === in_array($property->getName(), array('flatMembers', 'referencedMembers'))) {
                continue;
            }

            $name = $property->getName();
            $instance->set($name, $this->matchDeclaredVar($property, $resultData, $preventReferencing));
        }


        //@todo check if needed xD //uhm.. does seem to.... oO
        #foreach ($instance->referencedMembers as $referenced) {
            #$instance->setReferenced($referenced, $this->matchDeclaredVar($reflection->getProperty($referenced), $resultData));
        #}

        $instance->validate();
        $instance->setIsModified(false);

        return $instance;
    }

    /**
     * @param \York\Database\QueryBuilder $query
     *
     * @return \York\Database\Blueprint\ItemInterface | null
     */
    public function findOne(\York\Database\QueryBuilder $query)
    {
        $result = $this->find($query);

        if (true === empty($result)) {
            return null;
        }

        $result = reset($result);

        return $result;
    }

    /**
     * @param \York\Database\QueryBuilder\QueryString $queryString
     *
     * @return \York\Database\Blueprint\ItemInterface[]
     */
    public function findByQueryString(\York\Database\QueryBuilder\QueryString $queryString)
    {
        $model = new \York\Database\Model();
        $results = $model->findAllByQueryString($queryString);
        $return = array();

        foreach ($results as $current) {
            $currentBlueprint = $this->createByResult($current, true);

            if (null === $currentBlueprint) {
                continue;
            }

            $return[] = $currentBlueprint;
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function find(\York\Database\QueryBuilder $query, $preventReferencing = false)
    {
        $model = new \York\Database\Model();
        $results = $model->findAllByQueryString($query->getQueryString());
        $return = array();

        foreach ($results as $current) {
            $currentBlueprint = $this->createByResult($current, $preventReferencing);

            if (null === $currentBlueprint) {
                continue;
            }

            $return[] = $currentBlueprint;
        }

        \York\Dependency\Manager::getModelCache()->addMultiple($return);

        return $return;
    }

    /**
     * finds all items
     * handle with care!
     *
     * @return \York\Database\Blueprint\ItemInterface[]
     */
    public function findAll()
    {
        $result = $this->find(new \York\Database\QueryBuilder\Select(array(
            'from' => array(
                $this->getTableName()
            ),
            'order' => 'id DESC'
        )));

        \York\Dependency\Manager::getModelCache()->addMultiple($result);

        return $result;
    }

    /**
     * checks if the requested table has a clumn named is_active, if so, select omly the items with is_active = true, otherwise select all items
     *
     * @return \York\Database\Blueprint\ItemInterface[]
     */
    public function findAllActives()
    {
        $host = \Application\Configuration\Dependency::getDatabaseConfiguration()->get('db_host');
        $table = $this->getTableName();
        $column = 'is_active';
        $exists = \York\Database\Information::columnExists($host, $table, $column);

        if (false === $exists) {
            return $this->findAll();
        }

        return $this->find(new \York\Database\QueryBuilder\Select(array(
            'from' => array(
                $this->getTableName()
            ),
            'where' => array(
                'is_active' => true
            )
        )), true);
    }

    /**
     * clear all data
     *
     * @return boolean
     */
    public function clearAll()
    {
        return \Application\Configuration\Dependency::getDatabaseManager()
            ->query(
                new \York\Database\QueryBuilder\QueryString(
                    sprintf('delete from %s', $this->getTableName()))
            )
            ->queryWasSuccessful();
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     *
     * @return $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }
}
