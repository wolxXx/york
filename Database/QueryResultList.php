<?php
namespace York\Database;
/**
 * list of fetched results
 *
 * @author wolxXx
 * @version 3.0
 * @subpackage Database\York
 */
class QueryResultList{
	/**
	 * the list
	 *
	 * @var array
	 */
	protected $results;

	/**
	 * constructor
	 *
	 * @param array $results
	 */
	public function __construct($results = array()){
		$this->results = $results;
	}

	/**
	 * clears all results
	 *
	 * @return \York\Database\QueryResultList
	 */
	protected function clearResults(){
		$this->results = array();
		return $this;
	}

	/**
	 * fetches objects from a query result
	 *
	 * @param \York\Database\QueryResult $result
	 * @return \York\Database\QueryResultList
	 */
	public function injectResultsViaQueryResult($result){
		$this->clearResults();
		if(false !== $result && 0 !== $result->num_rows){
			while($obj = $result->fetch_object('\York\Database\FetchResult')){
				$this->results[] = $obj;
			}
			$result->close();
		}
		return $this;
	}

	/**
	 * setter for results
	 *
	 * @param array $results
	 * @return \York\Database\QueryResultList
	 */
	public function setResults($results){
		$this->results = $results;
		return $this;
	}

	/**
	 * returns all results
	 *
	 * @return array
	 */
	public function getResults(){
		return $this->results;
	}
}