<?php
namespace York\Database\QueryBuilder;
use York\Database\QueryBuilder;
use York\Exception\QueryGenerator;
use York\Helper\Set;

/**
 * creates an insert into $table query string
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\QueryBuilder
 */
class Insert extends QueryBuilder{
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
	 * @param string $table
	 * @param array $data
	 */
	public function __construct($table = '', $data = array()){
		$this
			->setTable($table)
			->setData($data);
	}

	/**
	 * @param $table
	 * @return \York\Database\QueryBuilder\Insert
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * @param array $data
	 * @return \York\Database\QueryBuilder\Insert
	 */
	public function setData($data){
		$this->data = $data;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::checkConditions()
	 */
	protected function checkConditions(){
		if(true === empty($this->table)){
			throw new QueryGenerator('you need to specify a table name!');
		}
		if(false === is_array($this->data)){
			throw new QueryGenerator('could not create model if data is not an array');
		}

		if(true === empty($this->data)){
			throw new QueryGenerator('recieved no data');
		}
	}

	/**
	 * runs through the data array and takes the keys for the keys
	 * section and the values for the value section.
	 * this might be a clause for cptn. obvious
	 *
	 * @todo as long as we have no multitype returns as in 5.5 it returns an array
	 * @fixme but now we have ;)
	 *
	 * @return array
	 */
	private function generateStringFromArray(){
		$connection = \York\Dependency\Manager::get('databaseManager')->getConnection();
		$keys = implode(',' , Set::decorate(array_keys($this->data), '`', '`'));
		$values = '';

		foreach(array_keys($this->data) as $key){
			$value = $this->data[$key];
			if(true === $this->data[$key] instanceof \DateTime){
				$value = $value->format('Y-m-d H:i:s');
			}
			$values .= sprintf("'%s',", $connection->escape($value));
		}

		$keys = rtrim($keys, ',');
		$values = rtrim($values, ',');
		return array('keys' => $keys, 'values' => $values);
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::generateQuery()
	 */
	public function generateQuery(){
		$this->checkConditions();
		$generatedArray = $this->generateStringFromArray();
		$keys = $generatedArray['keys'];
		$values = $generatedArray['values'];
		$query = sprintf("INSERT INTO `%s` (%s) VALUES (%s);", $this->table, $keys, $values);
		return $query;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::getQueryString()
	 */
	public function getQueryString(){
		return new \York\Database\QueryBuilder\QueryString($this->generateQuery());
	}
}
