<?php
namespace York\Logger;
use York\Database\Accessor\Factory;
use York\Helper\Date;
/**
 * database logger
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Logger
 */
class Database extends LoggerAbstract{
	/**
	 * name of the table where the logs should be put to
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * new instance of the database logger
	 *
	 * @param string $table
	 * @param string $level
	 */
	public function __construct($table, $level = Manager::LEVEL_ALL){
		$this->setTable($table);
	}

	/**
	 * setter for the table name
	 *
	 * @param string $table
	 * @return \York\Logger\Database
	 */
	public function setTable($table){
		$this->table = $table;
		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function log($message){
		Factory::getSaveObject($this->table)
			->set('created', Date::getDate())
			->set('message', $message)
			->save();
		return $this;
	}
}
