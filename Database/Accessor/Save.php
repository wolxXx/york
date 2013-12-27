<?php
namespace York\Database\Accessor;
/**
 * OO-Wrapper for having a saveable object for the york core model save
 * this is only usable for one table. no composed objects are supported.. yet!
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\Accessor
 *
 */
class Save{
	/**
	 * instance of a database manager
	 *
	 * @var \York\Database\Manager
	 */
	private $databaseManager;

	/**
	 * name of the table whrere the set should be saved
	 *
	 * @var string
	 */

	private $table;
	/**
	 * data to save
	 *
	 * @var array
	 */
	private $data;

	/**
	 * constructor
	 *
	 * @param string $table
	 * @return \York\Database\Accessor\Save
	 */
	public function __construct($table){
		$this->databaseManager = \York\Database\Manager::getInstance();
		$this
			->reset()
			->setTable($table);
	}

	/**
	 * setter for keys and values
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \York\Database\Accessor\Save
	 */
	public function set($key, $value){
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * setter for values
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \York\Database\Accessor\Save
	 */
	public function __set($key, $value){
		return $this->set($key, $value);
	}

	/**
	 * getter for key
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key){
		if(!isset($this->data[$key])){
			$message = sprintf('warning: property "%s" not set in saveobject!', $key);
			\York\Logger\Manager::getInstance()->log($message, \York\Logger\Manager::TYPE_ALL, \York\Logger\Manager::LEVEL_WARN);
			return null;
		}
		return $this->data[$key];
	}

	/**
	 * getter for values
	 *
	 * @param string $key
	 * @return null | mixed
	 */
	public function __get($key){
		return $this->get($key);
	}

	/**
	 * deletes a set field, so it is not saved to database
	 *
	 * @param string $key
	 * @return \York\Database\Accessor\Save
	 */
	public function unsetField($key){
		unset($this->data[$key]);
		return $this;
	}

	/**
	 * unsets all set datas
	 *
	 * @return \York\Database\Accessor\Save
	 */
	public function reset(){
		$this->data = array();
		return $this;
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return \York\Database\Accessor\Save
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * setter for the data array
	 * caution: overwrites data set before!
	 * for merging use addData function!!
	 *
	 * @param array $data
	 * @return \York\Database\Accessor\Save
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
	 * @return \York\Database\Accessor\Save
	 */
	public function addData($data){
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	/**
	 * saves the set data to the set table
	 *
	 * @return \York\Database\QueryResult
	 */
	public function save(){
		$queryBuilder = new \York\Database\QueryBuilder\Insert($this->table, $this->data);
		return $this->databaseManager->save($queryBuilder->getQueryString());
	}
}