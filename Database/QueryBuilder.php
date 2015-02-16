<?php
namespace York\Database;

/**
 * abstract query builder
 * needs a connection. you can set it via dic or dis.
 * needs conditions as array. you can set it via dic or dis.
 * provides functions for transforming a condition array to a query string
 *
 * @package York\Database
 * @version $version$
 * @author wolxXx
 */
abstract class QueryBuilder
{
    /**
     * connection to the database
     *
     * @var \York\Database\Connection
     */
    protected $connection;

    /**
     * array of conditions
     *
     * @var array
     */
    protected $conditions;

    /**
     * constructor. you can use dependency injection via constructor (dic)
     * returns this in pattern of fluent interface
     *
     * @param array | null      $conditions
     * @param \mysqli | null    $connection
     */
    public function __construct($conditions = null, $connection = null)
    {
        $this->conditions = $conditions;
        $this->connection = $connection;

        if (null === $this->connection) {
            $this->connection = \York\Dependency\Manager::getDatabaseManager()->getConnection();
        }
    }

    /**
     * checks if the set conditions fulfill the needed minimal expectations
     *
     * @throws \York\Exception\QueryGenerator
     * @throws \York\Exception\General
     */
    abstract protected function checkConditions();

    /**
     * generates the query from the set conditions
     *
     * @return string
     *
     * @throws \York\Exception\QueryGenerator
     * @throws \York\Exception\General
     */
    abstract public function generateQuery();

    /**
     * returns an instance of a QueryString
     *
     * @return \York\Database\QueryBuilder\QueryStringInterface
     */
    abstract public function getQueryString();

    /**
     * setter for the connection
     * returns this in pattern of fluent interface
     *
     * @param \mysqli $connection
     *
     * @return $this
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * getter for the connection
     *
     * @return \York\Database\Connection
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = new \York\Database\Connection();
        }

        return $this->connection;
    }

    /**
     * setter for conditions
     * returns this in pattern of fluent interface
     *
     * @param array $conditions
     *
     * @return \York\Database\QueryBuilder
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
        $this->mergeConditions($conditions);

        return $this;
    }

    /**
     * merges the default conditions with the provided
     * it ensures that the minimal requirements are set
     *
     * @param array $conditions
     */
    protected function mergeConditions($conditions)
    {
        $myconditions = array(
            'method' => 'all', #all | one -> returns array or object
            'from' => null, #table names or join clauses. the only field that is required!!
            'limit' => null, # integer, null, array{1}, array{2} -> int => limit i, null => no limit, array{1} => limit array[0], array{2} => limit array[0], array[1]
            'fields' => '*', # string, array:  string = explicit this field, array => elements will be imploded to string, can contain as clauses
            'where' => null, # array only! used for prepared statements
            'order' => null, # string
            'group' => null, # string
            'distinct' => false, #boolean
        );
        //overwrite my defaults with your conditions
        $this->conditions = array_merge($myconditions, $conditions);
    }

    /**
     * converts signs as < or > to string like LESS or MORE
     *
     * @param string $sign
     *
     * @return string
     */
    public function mapSignsToString($sign)
    {
        switch ($sign) {
            case '<':
                return 'LESS';

            case '<=':
                return 'SAMEORLESS';

            case '>':
                return 'MORE';

            case '>=':
                return 'SAMEORMORE';

            default:
                return $sign;
        }
    }


    /**
     * generates the limit string
     * you can provide null, an array or a string or an integer
     *
     * @return string
     */
    protected function generateLimit()
    {
        if (null === $this->conditions['limit']) {
            if ('one' === $this->conditions['method']) {
                return 'LIMIT 1';
            }

            return '';
        }

        $limit = 'LIMIT ';

        if (false === is_array($this->conditions['limit'])) {
            return $limit . $this->conditions['limit'];
        }

        $limit .= $this->conditions['limit'][0];

        if (true === isset($this->conditions['limit'][1])) {
            $limit .= sprintf(', %s', $this->conditions['limit'][1]);
        }

        return $limit;
    }

    /**
     * fields may be null, then select *, but fields may be a string,
     * then select that, otherwise it should be an array, then implode to string
     *
     * @return string
     */
    protected function generateFields()
    {
        $fields = '*';

        if (null !== $this->conditions['fields'] && false === empty($this->conditions['fields'])) {
            $fields = $this->conditions['fields'];

            if (true === is_array($this->conditions['fields'])) {
                $fields = implode(', ', $this->conditions['fields']);
            }
        }

        return $fields;
    }

    /**
     * insert an order statement. comes as string from conditions, just concat
     *
     * @return string
     */
    protected function generateOrder()
    {
        $order = '';

        if (null !== $this->conditions['order'] && false === empty($this->conditions['order'])) {
            if (true === is_array($this->conditions['order'])) {
                $this->conditions['order'] = implode(',', $this->conditions['order']);
            }

            $order = sprintf('ORDER BY %s', $this->conditions['order']);
        }

        return $order;
    }

    /**
     * insert a group by statement. comes as string from conditions, just concat
     *
     * @return string
     */
    protected function generateGroup()
    {
        if (null === $this->conditions['group']) {
            return '';
        }

        return $group = sprintf('GROUP BY %s', $this->conditions['group']);
    }

    /**
     * if boolean field distinct is set to true, insert distinct statement
     *
     * @return string
     */
    protected function generateDistinct()
    {
        if (false !== $this->conditions['distinct']) {
            return 'DISTINCT';
        }

        return '';
    }

    /**
     * param can be array or string
     *
     * @throws \York\Exception\QueryGenerator
     *
     * @return string
     */
    protected function generateFrom()
    {
        $from = $this->conditions['from'];
        $exception = new \York\Exception\QueryGenerator('no from selected');

        if(null === $from) {
            throw $exception;
        }

        if('' === $from) {
            throw $exception;
        }

        if(false === is_string($from) && false === is_array($from)){
            throw $exception;
        }

        if (true === is_array($this->conditions['from'])) {
            if (true === empty($this->conditions['from'])) {
                throw new \York\Exception\QueryGenerator('from is array and empty');
            }

            $from = implode(', ', $this->conditions['from']);
        }

        return $from;
    }

    /**
     * creates the query part for a simple comparison
     *
     * @param string            $where
     * @param string            $left
     * @param string | number   $right
     * @return string
     */
    protected function generateWhereSimple($where, $left, $right)
    {
        $and = '';

        if ('' !== $where) {
            $and = ' AND ';
        }

        $right = $this->getStringForType($right);
        $right = $this->getConnection()->escape($right);
        $where .= sprintf('%s %s = \'%s\'', $and, $left, $right);

        return trim($where);
    }

    /**
     * creates the query part for the or query
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereOR($where, $right)
    {
        if ('' === $where) {
            $where .= '(';
        } else {
            $where .= ' AND (';
        }

        $blank = true;

        foreach ($right as $key => $value) {
            if (true !== $blank) {
                $where .= ' OR ';
            }

            $where .= "$key = '$value'";
            $blank = false;
        }

        $where .= ')';

        return trim($where);
    }

    /**
     * creates the query part for the or query where param is LIKEed
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereORLIKE($where, $right)
    {
        if ('' === $where) {
            $where .= '(';
        } else {
            $where .= ' AND (';
        }

        $blank = true;

        foreach ($right as $key => $value) {
            if (true !== $blank) {
                $where .= ' OR ';
            }

            $where .= "`$key` LIKE '%$value%'";
            $blank = false;
        }

        $where .= ')';

        return trim($where);
    }

    /**
     * creates the query part for a relational or comparison
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereRELOR($where, $right)
    {
        if ('' === $where) {
            $where .= '(';
        } else {
            $where .= ' AND (';
        }

        $blank = true;

        foreach ($right as $key => $value) {
            if (!$blank) {
                $where .= ' OR ';
            }

            $where .= "$key = $value";
            $blank = false;
        }

        $where .= ')';

        return trim($where);
    }

    /**
     * creates the query part for a > compparison
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereMORE($where, $right)
    {
        foreach ($right as $name => $value) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $where .= sprintf("%s %s > '%s'", $and, $name, $this->getStringForType($value));
        }

        return trim($where);
    }

    /**
     * creates the query part for a >= comparison
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereSAMEORMORE($where, $right)
    {
        foreach ($right as $name => $value) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $where .= " $and $name >= '$value'";
        }

        return trim($where);
    }

    /**
     * creates the query part for a < comparison
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereLESS($where, $right)
    {
        foreach ($right as $name => $value) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $where .= " $and $name < '$value'";
        }

        return trim($where);
    }

    /**
     * creates the query part for a <= comparison
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereSAMEORLESS($where, $right)
    {
        foreach ($right as $name => $value) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $where .= " $and $name <= '$value'";
        }

        return trim($where);
    }

    /**
     * creates the query part for a like comparison
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereLIKE($where, $right)
    {
        foreach ($right as $name => $value) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $value = $this->connection->escape($value);
            $where .= " $and $name LIKE '%$value%'";
        }

        return trim($where);
    }

    /**
     * creates the query part for null query
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereNULL($where, $right)
    {
        foreach ($right as $name) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $where .= "$and $name IS NULL";
        }

        return trim($where);
    }

    /**
     * creates the query part for a not null query
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereNOTNULL($where, $right)
    {
        foreach ($right as $name) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $where .= "$and $name IS NOT NULL";
        }

        return trim($where);
    }

    /**
     * creates the query part for a negotiated comparison
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereNOT($where, $right)
    {
        foreach ($right as $name => $value) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $where .= "$and $name != '$value'";
        }

        return trim($where);
    }

    /**
     * creates the query part for in query
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereIN($where, $right)
    {
        foreach ($right as $name => $values) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $values = array_unique($values);
            $where .= "$and $name IN ('" . implode('\', \'', $values) . '\')';
        }

        return trim($where);
    }

    /**
     * creates the query part for not in query
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereNOTIN($where, $right)
    {
        foreach ($right as $name => $values) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $values = array_unique($values);
            $where .= "$and $name NOT IN ('" . implode('\', \'', $values) . '\')';
        }

        return trim($where);
    }

    /**
     * creates the query part for relational comparisons
     *
     * @param string    $where
     * @param array     $right
     *
     * @return string
     */
    protected function generateWhereREL($where, $right)
    {
        foreach ($right as $name => $values) {
            $and = '';

            if ('' !== $where) {
                $and = ' AND';
            }

            $where .= "$and $name = $values";
        }

        return trim($where);
    }

    /**
     * this is the most interesting section..
     * it runs through the where section of the search
     * conditions an grabs the provided information
     *
     * @return string
     *
     * @throws \York\Exception\QueryGenerator
     *
     * @todo provice nested conditions !!!! REALLYY!!!!
     */
    protected function generateWhere()
    {
        if (false === isset($this->conditions['where'])) {
            return '1 = 1';

        }

        if (null === $this->conditions['where']) {
            return '1 = 1';

        }

        if (empty($this->conditions['where'])) {
            return '1 = 1';

        }

        $where = '';

        foreach ($this->conditions['where'] as $left => $right) {
            if (false === method_exists($this, 'generateWhere' . $this->mapSignsToString($left))) {
                $where = $this->generateWhereSimple($where, $left, $right);

                continue;
            }

            if (false === is_array($right)) {
                $message = sprintf('right (%s) should be an array for left (%s)!', $right, $left);

                throw new \York\Exception\QueryGenerator($message);
            }

            $where = $this->{'generateWhere' . $this->mapSignsToString($left)}($where, $right);
        }

        return trim($where);
    }

    /**
     * @param mixed $something
     *
     * @return string
     */
    protected function getStringForType($something)
    {
        if (true === is_bool($something)) {
            return true === $something ? '1' : '0';
        }

        if (true === is_array($something)) {
            return implode(',', $something);
        }

        if (true === is_a($something, '\DateTime')) {
            return $something->format('Y-m-d H:i:s');
        }

        return $something;
    }
}
