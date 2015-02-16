<?php
namespace York\Database\QueryBuilder;

/**
 * creates an insert into $table query string
 *
 * @package York\Database\QueryBuilder
 * @version $version$
 * @author wolxXx
 */
class Insert extends \York\Database\QueryBuilder
{
    /**
     * the name of the table
     *
     * @var string
     */
    protected $table;

    /**
     * the data to be saved
     *
     * @var array
     */
    protected $data;

    /**
     * constructor
     *
     * @param string    $table
     * @param array     $data
     */
    public function __construct($table = '', $data = array())
    {
        $this
            ->setTable($table)
            ->setData($data);
    }

    /**
     * @param string $table
     *
     * @return \York\Database\QueryBuilder\Insert
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return \York\Database\QueryBuilder\Insert
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function checkConditions()
    {
        if (true === empty($this->table)) {
            throw new \York\Exception\QueryGenerator('you need to specify a table name!');
        }

        if (false === is_array($this->data)) {
            throw new \York\Exception\QueryGenerator('could not create model if data is not an array');
        }

        if (true === empty($this->data)) {
            throw new \York\Exception\QueryGenerator('recieved no data');
        }
    }

    /**
     * runs through the data array and takes the keys for the keys
     * section and the values for the value section.
     * this might be a clause for cptn. obvious
     *
     * @todo as long as we have no multitype returns as in 5.5 it returns an array
     * @fixme but now we have ;) is called yield..
     *
     * @return array
     */
    private function generateStringFromArray()
    {
        $connection = \York\Dependency\Manager::get('databaseManager')->getConnection();
        $keys = implode(',', \York\Helper\Set::decorate(array_keys($this->data), '`', '`'));
        $values = '';

        foreach (array_keys($this->data) as $key) {
            $value = $this->data[$key];

            if (true === $this->data[$key] instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $values .= sprintf("'%s',", $connection->escape($value));
        }

        $keys = rtrim($keys, ',');
        $values = rtrim($values, ',');

        return array('keys' => $keys, 'values' => $values);
    }

    /**
     * @return string
     *
     * @throws \York\Exception\QueryGenerator
     */
    public function generateQuery()
    {
        $this->checkConditions();
        $generatedArray = $this->generateStringFromArray();
        $keys = $generatedArray['keys'];
        $values = $generatedArray['values'];
        $query = sprintf("INSERT INTO `%s` (%s) VALUES (%s);", $this->table, $keys, $values);

        return $query;
    }

    /**
     * @return \York\Database\QueryBuilder\QueryString
     */
    public function getQueryString()
    {
        return new \York\Database\QueryBuilder\QueryString($this->generateQuery());
    }
}
