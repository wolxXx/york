<?php
namespace York\Database;
use York\Database\Accessor\Factory;
use York\Database\QueryBuilder\QueryString;
use York\Dependency\Manager as Dependency;
use York\Exception\York;
use York\Helper\Application;
use York\Helper\Date;

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
		$this->connection = Dependency::get('databaseManager')->getConnection();
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
		Factory::getSaveObject('migrations')
			->set('number', $this->getRevision())
			->set('created', Date::getDate())
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
	 * @return QueryResult
	 */
	protected function query($query){
		$database = Dependency::get('databaseManager');

		$result = null;
		try{
			$result = $database->query(new QueryString($query));
		}catch(York $x){
			Application::debug($x, $result);
		}
		return $result;
	}
}
