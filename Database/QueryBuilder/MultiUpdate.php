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
	 * @param null $databaseManager
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
	 * @return $this
	 */
	public function setDatabaseManager($databaseManager = null){
		if(null === $databaseManager){
			$databaseManager = \York\Dependency\Manager::get('databaseManager');
		}

		$this->databaseManager = $databaseManager;

		return $this;
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return $this
	 */
	public function setTable($table){
		$this->table = $table;

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function setConditions($conditions){
		$this->conditions = array('where' => $conditions);

		return $this;
	}

	/**
	 * setter for the data array
	 *
	 * @param array $data
	 * @return $this
	 */
	public function setData($data){
		$this->data = $data;
		return $this;
	}

	/**
	 * @inheritdoc
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
	 * @inheritdoc
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
