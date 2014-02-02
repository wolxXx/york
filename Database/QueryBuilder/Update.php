<?php
namespace York\Database\QueryBuilder;
/**
 * creates an update query string
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\QueryBuilder
 */
class Update{
	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * the data array
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * the id of the element that should be updated
	 *
	 * @var integer
	 */
	protected $rowId;

	/**
	 * an instance of the databaseManager
	 *
	 * @var \York\Database\Manager
	 */
	protected $databaseManager;

	/**
	 * constructor
	 *
	 * @param string $table
	 * @param array $data
	 * @param integer $rowId
	 * @param null $databaseManager
	 */
	public function __construct($table = null, $data = null, $rowId = null, $databaseManager = null){
		$this
			->setTable($table)
			->setData($data)
			->setId($rowId)
			->setDatabaseManager($databaseManager);
	}

	/**
	 * setter for the databaseManager
	 *
	 * @param \York\Database\Manager $databaseManager
	 * @return \York\Database\QueryBuilder\Update
	 */
	public function setDatabaseManager($databaseManager = null){
		if(null === $databaseManager){
			$databaseManager = \York\Dependency\Manager::get('databaseManager');
		}
		$this->databaseManager = $databaseManager;
		return $this;
	}
	/**
	 * checks the set conditions
	 *
	 * @throws \York\Exception\QueryGenerator
	 */
	protected function checkConditions(){
		if(null === $this->table){
			throw new \York\Exception\QueryGenerator('please specify table');
		}

		if('' === $this->table){
			throw new \York\Exception\QueryGenerator('please specify table');
		}

		if(null === $this->rowId){
			throw new \York\Exception\QueryGenerator('please specify id');
		}

		if(false === is_array($this->data)){
			throw new \York\Exception\QueryGenerator('data should be an array!');
		}

		if(true === empty($this->data)){
			throw new \York\Exception\QueryGenerator('data should contain data');
		}
	}

	protected function prepareData(){
		foreach($this->data as $key => $value){
			if(true === is_array($value)){
				$this->data[$key] = implode(', ', $value);
				continue;
			}

			if(true === $value instanceof \DateTime){
				/**
				 * @var \DateTime $value
				 */
				$this->data[$key] = $value->format('Y-m-d H:i:s');
				continue;
			}
			if(true === is_bool($value)){
				$this->data[$key] = true === $value? 1 : 0;
				continue;
			}
		}
	}

	/**
	 * creates the query
	 *
	 * @return string
	 */
	public function getQuery(){
		$this->checkConditions();

		$text = '';
		$connection = $this->databaseManager->getConnection();
		$this->prepareData();
		foreach ($this->data as $key => $value){
			$text.= sprintf("`%s` = '%s', ", $key, $connection->escape($value));
		}
		$text = rtrim($text, ' ,');
		$query = sprintf("UPDATE `%s` SET %s WHERE `%s`.id = %s;", $this->table, $text, $this->table, $this->rowId);
		return $query;
	}

	/**
	 * creates an instance of a QueryString
	 *
	 * @return \York\Database\QueryBuilder\QueryStringInterface
	 */
	public function getQueryString(){
		return new \York\Database\QueryBuilder\QueryString($this->getQuery());
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return \York\Database\QueryBuilder\Update
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * setter for the data array
	 *
	 * @param array $data
	 * @return \York\Database\QueryBuilder\Update
	 */
	public function setData($data){
		$this->data = $data;
		return $this;
	}

	/**
	 * setter for the item id
	 *
	 * @param integer $rowId
	 * @return \York\Database\QueryBuilder\Update
	 */
	public function setId($rowId){
		$this->rowId = $rowId;
		return $this;
	}
}
