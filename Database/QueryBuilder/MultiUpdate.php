<?php
namespace York\Database\QueryBuilder;
/**
 * query builder for a multiple update query string
 *
 * @author wolxXx
 * @version 3.0
 * @package \York\Database\QueryBuilder
 */
class MultiUpdate extends \York\Database\QueryBuilder{
	/**
	 * the data that should be set
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * the conditions which items should be updated
	 *
	 * @var array
	 */
	protected $conditions;

	/**
	 * an instance of the DatabaseManager
	 *
	 * @var \York\Database\Manager
	 */
	protected $databaseManager;

	/**
	 * constructor
	 *
	 * @param string $table
	 * @param array $data
	 * @param array $conditions
	 */
	public function __construct($table = null, $data = null, $conditions = null, $databaseManager = null){
		$this
			->setTable($table)
			->setConditions($conditions)
			->setData($data)
			->setDatabaseManager($databaseManager);
	}

	/**
	 * @param null $databaseManager
	 * @return \York\Database\QueryBuilder\MultiUpdate
	 */
	public function setDatabaseManager($databaseManager = null){
		if(null === $databaseManager){
			$databaseManager = \York\Database\Manager::getInstance();
		}
		$this->databaseManager = $databaseManager;
		return $this;
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return \York\Database\QueryBuilder\MultiUpdate
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::setConditions()
	 */
	public function setConditions($conditions){
		$this->conditions = array('where' => $conditions);
		return $this;
	}

	/**
	 * setter for the data array
	 *
	 * @param array $data
	 * @return \York\Database\QueryBuilder\MultiUpdate
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
		if(null === $this->conditions){
			throw new \York\Exception\QueryGenerator('please specify conditions');
		}

		if(true === empty($this->conditions)){
			throw new \York\Exception\QueryGenerator('please specify conditions');
		}

		if(null === $this->data){
			throw new \York\Exception\QueryGenerator('please specify data');
		}

		if(true === empty($this->data)){
			throw new \York\Exception\QueryGenerator('please specify data');
		}

		if(null === $this->table){
			throw new \York\Exception\QueryGenerator('please specify table');
		}

		if('' === $this->table){
			throw new \York\Exception\QueryGenerator('please specify table');
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::generateQuery()
	 */
	public function generateQuery(){
		$this->checkConditions();
		$where = $this->generateWhere();

		$text = '';
		$connection = $this->databaseManager->getConnection();
		foreach ($this->data as $key => $value){
			$text .= sprintf("`%s` = '%s', ", $key, $connection->escape($value));
		}
		$text = rtrim($text, ' ,');
		$query = sprintf("UPDATE `%s` SET %s WHERE %s;", $this->table, $text, $where);
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