<?php
namespace York\Database\Accessor;
/**
 * OO-Wrapper for having a savable object for the core model update function
 * this is only usable for one table. no composed objects are supported.. yet!
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\Accessor
 */
class Update{
	/**
	 * instance of a database manager
	 *
	 * @var \York\Database\Manager
	 */
	private $databaseManager;

	/**
	 * the id of the to save object
	 *
	 * @var integer
	 */
	private $rowId;

	/**
	 * the table name
	 *
	 * @var string
	 */
	private $table;

	/**
	 * data set
	 *
	 * @var array
	 */
	private $data;

	/**
	 * constructor
	 *
	 * @param string $table
	 * @param null $rowId
	 */
	public function __construct($table = null, $rowId= null){
		$this
			->setData(array())
			->setTable($table)
			->setId($rowId)
		;
		$this->databaseManager = \York\Database\Manager::getInstance();
	}

	/**
	 * setter for values
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \York\Database\Accessor\Update
	 */
	public function __set($key, $value){
		return $this->set($key, $value);
	}

	/**
	 * getter for values
	 *
	 * @param string $key
	 * @return null | mixed
	 */
	public function __get($key){
		if(!isset($this->data[$key])){
			return null;
		}
		return $this->data[$key];
	}

	/**
	 * setter for values
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \York\Database\Accessor\Update
	 */
	public function set($key, $value){
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * setter for the data array
	 * caution: overwrites data set before!
	 * for merging use addData function!!
	 *
	 * @param array $data
	 * @return \York\Database\Accessor\Update
	 */
	public function setData($data){
		$this->data = $data;
		return $this;
	}

	/**
	 * merges data with data that was set before
	 * values with the same key will be overwritten by the new data array
	 *
	 * @param array $data
	 * @return \York\Database\Accessor\Update
	 */
	public function addData($data){
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	/**
	 * resets the internal data array
	 *
	 * @return \York\Database\Accessor\Update
	 */
	public function resetData(){
		$this->data = array();
		return $this;
	}

	/**
	 * getter for the data array
	 *
	 * @return array
	 */
	public function getData(){
		return $this->data;
	}

	/**
	 * setter for table name
	 *
	 * @param string $table
	 * @return \York\Database\Accessor\Update
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * setter for row id
	 *
	 * @param integer $rowId
	 * @return \York\Database\Accessor\Update
	 */
	public function setId($rowId){
		$this->rowId = $rowId;
		return $this;
	}

	/**
	 * updates the set data to the set table
	 *
	 * @return \York\Database\Accessor\Update
	 */
	public function update(){
		$queryBuilder = new \York\Database\QueryBuilder\Update($this->table, $this->data, $this->rowId);
		return $this->databaseManager->update($queryBuilder->getQueryString());
	}
}
