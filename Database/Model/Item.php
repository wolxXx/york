<?php
namespace York\Database\Model;
use York\Database\Accessor\Factory;

abstract class Item{
	/**
	 * name of the representing table
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * id of the set in the database
	 *
	 * @var integer
	 */
	protected $id;

	/**
	 * dirty-flag
	 *
	 * @var boolean
	 */
	protected $isModified;

	/**
	 * the data
	 *
	 * @var \York\Storage\Simple
	 */
	protected $data;

	/**
	 * list of class members that have direct correspondence to the database
	 * @var array
	 */
	protected $flatMembers;

	/**
	 * @param string $table
	 * @param integer $id
	 */
	public function __construct($table, $id = null){
		$this->data = new \York\Storage\Simple();
		$this->table = $table;
		$this->id = $id;
		$this->isModified = false;
	}

	/**
	 * @return \York\Database\Blueprint\ItemInterface
	 */
	public function validate(){

	}

	/**
	 * @param array $data
	 * @return \York\Database\Blueprint\ItemInterface
	 */
	public function setFromArray(array $data){
		foreach($data as $key => $value){
			$this->set($key, $value);
		}
		return $this;
	}

	/**
	 * setter for data
	 * sets the dirty-flag if data is modified
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \York\Database\Model\Item
	 */
	public function set($key, $value){
		if($value === $this->$key){
			return $this;
		}
		if(true === in_array($key, $this->flatMembers)){
			$this->data->set($key, $value);
		}
		$this->$key = $value;
		$this->isModified = true;

		return $this;
	}

	/**
	 * magic method overwriting for having dedicated data storage
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return \York\Database\Model\Item
	 */
	public function __set($key, $value){
		return $this->set($key, $value);
	}

	/**
	 * getter for data
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key){
		return $this->data->getSafely($key, null);
	}

	/**
	 * overwrite the class getter with magic method for having dedicated data storage
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key){
		return $this->get($key);
	}

	/**
	 * getter for the table name
	 *
	 * @return string
	 */
	public function getTable(){
		return $this->table;
	}

	/**
	 * getter for the id
	 *
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * checker for the dirty-flag
	 *
	 * @return boolean
	 */
	public function isModified(){
		return true === $this->isModified;
	}

	/**
	 * delete the set in the database
	 *
	 * @throws \York\Exception\ModelNotSaved
	 * @return boolean
	 */
	public function delete(){
		if(null === $this->getId()){
			throw new \York\Exception\ModelNotSaved();
		}
		return Factory::getDeleteObject($this->getTable(), $this->getId())->delete()->queryWasSuccessful();
	}

	/**
	 * save the data to the database
	 *
	 * @return \York\Database\Model\Item
	 *
	 */
	public function save(){
		if(false === $this->isModified()){
			return $this;
		}

		if(null === $this->getId()){
			$this->id = Factory
				::getSaveObject($this->getTable())
				->setData($this->data->getAll())
				->save()
				->getLastInsertId();

			$this->isModified = false;
			$this->validate();

			return $this;
		}

		Factory::getUpdateObject($this->getTable(), $this->getId())
			->setData($this->data->getAll())
			->update();

		$this->isModified = false;
		$this->validate();

		return $this;
	}
}
