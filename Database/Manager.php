<?php
namespace York\Database;
use York\Dependency\Manager as Dependency;

/**
 * accepts QueryString objects and delegates them to the database connection
 * implemented as singleton pattern
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database
 */
class Manager{
	/**
	 * instance of a Connection
	 *
	 * @var \York\Database\Connection
	 */
	protected $connection;

	/**
	 * if the current query shall be logged
	 *
	 * @var boolean
	 */
	protected $forceLogging = false;

	/**
	 * constructor
	 *
	 * sets up a new Connection instance
	 */
	public function __construct(){
		$this->connection = Dependency::get('databaseConnection');
		$this->connection
			->setHost(Dependency::get('databaseConfiguration')->get('db_host'))
			->setUser(Dependency::get('databaseConfiguration')->get('db_user'))
			->setPassword(Dependency::get('databaseConfiguration')->get('db_pass'))
			->setSchema(Dependency::get('databaseConfiguration')->get('db_schema'))
			->connect();
	}

	/**
	 * enables or disables forced logging
	 *
	 * @param boolean $force
	 * @return \York\Database\Manager
	 */
	public function setForceLogging($force = true){
		$this->forceLogging = $force;
		return $this;
	}

	/**
	 * queries a query. sounds good, eh
	 * takes the execution time
	 * calls the logger
	 *
	 * @param \York\Database\QueryBuilder\QueryStringInterface $queryStringObject
	 * @return \York\Database\QueryResult
	 */
	public function query(\York\Database\QueryBuilder\QueryStringInterface $queryStringObject){
		$start = microtime(true);
		$result = $this->connection->query($queryStringObject->getQueryString());
		$end = microtime(true);
		$resultObject = new \York\Database\QueryResult($result, $queryStringObject->getQueryString(), $this->connection->getError());
		try{
			$this->log($resultObject, $start, $end);
		}catch (\York\Exception\York $exception){
			Dependency::get('logger')->log('query exception: '.$exception->getMessage(), \York\Logger\Manager::LEVEL_DATABASE_ERROR);
		}
		return $resultObject;
	}

	/**
	 * logs a query
	 * as default, it only logs if there was an error while querying or the execution of the query took more than one second
	 * to enable forced logging, set DatabaseManager->setForceLogging(true)
	 *
	 * @param QueryResultObject $queryResultObject
	 * @param float $start
	 * @param float $end
	 */
	protected function log($queryResultObject, $start, $end){
		$result = $queryResultObject->getResult();
		$query = $queryResultObject->getQuery();
		$execution = $end - $start;
		$execution = sprintf("%000002.6f", $execution);
		$execution = str_pad($execution, 9, 0, STR_PAD_LEFT);
		$errorNumber = $this->connection->getErrno();
		$errorString = $this->connection->getError();
		$errorString = '' === $errorString? '-none-' : $errorString;
		$success = true === $queryResultObject->queryWasSuccessful()? 'successfull' : 'failed';
		if(false === is_bool($result)){
			$resultsValue = $result->num_rows;
			$resultsText = "Results:\t";
		}else{
			$resultsValue = $this->connection->getAffectedRows();
			$resultsText = "Affected rows:\t";
		}
		$originalTrace = debug_backtrace(0);
		$start = 100;
		while(false === isset($originalTrace[$start])){
			$start--;
		}
		$trace = $originalTrace[$start];
		while(false === isset($trace['file'])){
			$trace = $originalTrace[--$start];
		}
		$file = str_replace(\York\Helper\Application::getDocRoot(), '', $trace['file']);

		$class = isset($trace['class'])? $trace['class'].' at ' : '';
		$occurenced = $class.$file.' at line '.$trace['line'];
		$date = \York\Helper\Date::getDate();
		$userIP = \York\Helper\Net::getUserIP();
		$userName = \York\Auth\Manager::isLoggedIn()? \York\Auth\Manager::getUserNick() : 'arno nym';
		$url = \York\Helper\Net::getCurrentURL();

		$logtext = PHP_EOL.'____________________________________________'.PHP_EOL;
		$logtext .= "Query string:\n\t$query".PHP_EOL;
		$logtext .= "Execution time:\t$execution s".PHP_EOL;
		$logtext .= "Error string:\t$errorString".PHP_EOL;
		$logtext .= "Error number:\t$errorNumber".PHP_EOL;
		$logtext .= "Query result:\t$success".PHP_EOL;
		$logtext .= "$resultsText$resultsValue".PHP_EOL;
		$logtext .= "Occurenced:\t$occurenced".PHP_EOL;
		$logtext .= "Date:\t\t$date".PHP_EOL;
		$logtext .= "User IP:\t$userIP".PHP_EOL;
		$logtext .= "User name:\t$userName".PHP_EOL;
		$logtext .= "current URL:\t$url".PHP_EOL;
		$logtext .= '____________________________________________'.PHP_EOL;

		if(false === $queryResultObject->queryWasSuccessful()){
			\York\Dependency\Manager::get('logger')->log($logtext, \York\Logger\Manager::LEVEL_DATABASE_ERROR);
		}
		if('1' !== Dependency::get('applicationConfiguration')->getSafely('disable_db_log') || true === $this->forceLogging || (int)$execution > 1){
			\York\Dependency\Manager::get('logger')->log($logtext, \York\Logger\Manager::LEVEL_DATABASE_DEBUG);
		}
	}

	/**
	 * saves a new item in the database
	 *
	 * @param QueryStringInterface $queryStringObject
	 * @return \York\Database\QueryResult
	 */
	public function save(\York\Database\QueryBuilder\QueryStringInterface $queryStringObject){
		$result = $this->query($queryStringObject);
		$result->setLastInsertId($this->connection->getLastInsertId());
		return $result;
	}

	/**
	 * updates an item in the database
	 *
	 * @param QueryStringInterface $queryStringObject
	 * @return \York\Database\QueryResult
	 */
	public function update(\York\Database\QueryBuilder\QueryStringInterface $queryStringObject){
		return $this->query($queryStringObject);
	}

	/**
	 * deletes items in the database
	 *
	 * @param QueryStringInterface $queryStringObject
	 * @return \York\Database\QueryResult
	 */
	public function delete(\York\Database\QueryBuilder\QueryStringInterface $queryStringObject){
		return $this->query($queryStringObject);
	}

	/**
	 * finds items in the database
	 *
	 * @param QueryStringInterface $queryStringObject
	 * @return \York\Database\QueryResult
	 */
	public function find(\York\Database\QueryBuilder\QueryStringInterface $queryStringObject){
		return $this->query($queryStringObject);
	}

	/**
	 * getter for the connection
	 *
	 * @return \York\Database\Connection
	 */
	public function getConnection(){
		return $this->connection;
	}
}
