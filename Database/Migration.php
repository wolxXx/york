<?php
namespace York\Database;

/**
 * pattern for a migration
 * does a single migration
 *
 * @author wolxXx
 * @package York\Database
 * @version 3.0
 *
 */
abstract class Migration{
	/**
	 * continuous number
	 * make sure, the revision is really continuous!!
	 *
	 * @var integer
	 */
	protected $revision;

	/**
	 * a direct connection to the database
	 *
	 * @var Connection
	 */
	protected $connection;

	/**
	 * constructor
	 * @var
	 */
	public function __construct($revision){
		$this->setRevision($revision);
		$this->connection = \York\Dependency\Manager::get('databaseManager')->getConnection();
	}

	/**
	 * setter for the revision number
	 *
	 * @param integer $revision
	 * @return Migration
	 */
	public function setRevision($revision){
		$this->revision = $revision;
		return $this;
	}

	/**
	 * getter for the revision number
	 *
	 * @return integer
	 */
	public function getRevision(){
		return $this->revision;
	}

	/**
	 * after run hook
	 *
	 * @return Migration
	 */
	public final function afterRun(){
		$this->insertMigrationToDB();
		return $this;
	}

	/**
	 * inserts migration number on top into database table migrations
	 *
	 * @return Migration
	 */
	protected final function insertMigrationToDB(){
		\York\Database\Accessor\Factory::getSaveObject('migrations')
			->set('number', $this->getRevision())
			->set('created', \York\Helper\Date::getDate())
			->save();
		return $this;
	}

	/**
	 * this is where the main procedure takes place. drop your code here!
	 */
	public abstract function run();

	/**
	 * bridge to the database manager
	 *
	 * @param string $query
	 * @return QueryResultObject
	 */
	protected function query($query){
		$result = null;
		try{
			$result = DatabaseManager::getInstance()->query(new QueryString($query));
		}catch(Exception $x){
			Helper::debug($x, $result);
		}
		return $result;
	}

	/**
	 * returns a new instance of a migration
	 *
	 * @param integer $revision
	 */
	public static function getInstance($revision){
		$className = 'Migration'.$revision;
		if(false === class_exists($className, false)){
			require_once 'application/migrations/'.$revision.'.php';
		}
		$instance = new $className();
		$instance->setRevision($revision);
		return $instance;
	}
}
