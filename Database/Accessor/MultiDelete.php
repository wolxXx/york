<?php
namespace York\Database\Accessor;
/**
 * class for deleting multiple items from a database table
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\Accessor
 */
class MultiDelete{
	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * the conditions, which items should be deleted
	 *
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
	 * constructor
	 *
	 * @param string $table
	 * @param array $conditions
	 */
	public function __construct($table = null, $conditions = array()){
		$this->databaseManager = \York\Dependency\Manager::get('databaseManager');
		$this
			->setConditions($conditions)
			->setTable($table);
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
	 * setter for the conditions array
	 *
	 * @param array $conditions
	 * @return \York\Database\Accessor\MultiDelete
	 */
	public function setConditions($conditions){
		$this->conditions = $conditions;
		return $this;
	}

	/**
	 * sends conditions and table to query builder and sends the query to the database manager
	 *
	 * @return \York\Database\QueryResult
	 */
	public function delete(){
		$queryBuilder = new \York\Database\QueryBuilder\MultiDelete();
		$queryBuilder
			->setConditions($this->conditions)
			->setTable($this->table);
		$queryString = $queryBuilder->getQueryString();
		return $this->databaseManager->delete($queryString);
	}

}
