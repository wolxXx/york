<?php
namespace York\Database\Accessor;
/**
 * class for deleteing just one item from the databae
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\Accessor
 */
class Delete{
	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * the id of the item
	 *
	 * @var integer
	 */
	protected $rowId;

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
	 * @param integer $id
	 */
	public function __construct($table, $rowId){
		$this
			->setId($rowId)
			->setTable($table);
		$this->databaseManager = \York\Database\Manager::getInstance();
	}

	/**
	 * deletes the specified item
	 *
	 * @return \York\Database\QueryResult
	 */
	public function delete(){
		$queryBuilder = new \York\Database\QueryBuilder\Delete($this->table, $this->rowId, $this->databaseManager);
		return $this->databaseManager->delete($queryBuilder->getQueryString());
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return \York\Database\Accessor\Delete
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * setter for the item id
	 *
	 * @param integer $id
	 * @return \York\Database\Accessor\Delete
	 */
	public function setId($rowId){
		$this->rowId = $rowId;
		return $this;
	}
}
