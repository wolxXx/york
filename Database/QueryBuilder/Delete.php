<?php
namespace York\Database\QueryBuilder;
/**
 * query builder for deleting a single item in the database
 * the item is identified via its primary key which should be "id"
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\QueryBuilder;
 */
class Delete extends \York\Database\QueryBuilder{
	/**
	 * the name of the table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * an instance of the database manager
	 *
	 * @var \York\Database\Manager
	 */
	protected $databaseManager;

	/**
	 * the id of the item that should be deleted
	 *
	 * @var integer
	 */
	protected $rowId;

	/**
	 * constructor
	 *
	 * @param string $table
	 * @param integer $rowId
	 * @param \York\Database\Manager $databaseManager
	 */
	public function __construct($table = null, $rowId = null, $databaseManager = null){
		$this
			->setId($rowId)
			->setDatabaseManager($databaseManager)
			->setTable($table);
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::checkConditions()
	 */
	protected function checkConditions(){
		if(true === false){
			throw new \York\Exception\Apocalypse('gehnwa bierchen trinken. bringt nix mehr. wtf php?!');
		}
		if(null === $this->table){
			throw new \York\Exception\QueryGenerator('please specify table');
		}
		if(null === $this->databaseManager){
			throw new \York\Exception\QueryGenerator('please specify databaseManager');
		}
		if(null === $this->rowId){
			throw new \York\Exception\QueryGenerator('please specify id');
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::generateQuery()
	 */
	public function generateQuery(){
		$this->checkConditions();
		$query = sprintf("DELETE FROM `%s` WHERE `%s`.id = %s LIMIT 1;", $this->table, $this->table, $this->rowId);
		return $query;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryBuilder::getQueryString()
	 */
	public function getQueryString(){
		$query = $this->generateQuery();
		$queryString = new \York\Database\QueryBuilder\QueryString($query);
		return $queryString;
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return \York\Database\QueryBuilder\Delete
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * setter for the item id
	 *
	 * @param integer $rowId
	 * @return \York\Database\QueryBuilder\Delete
	 */
	public function setId($rowId){
		$this->rowId = $rowId;
		return $this;
	}

	/**
	 * setter for the databaseManager
	 *
	 * @param \York\Database\Manager $databaseManager
	 * @return \York\Database\QueryBuilder\Delete
	 */
	public function setDatabaseManager($databaseManager){
		$this->databaseManager = $databaseManager;
		return $this;
	}
}