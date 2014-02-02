<?php
namespace York\Database;
use York\Database\QueryBuilder\QueryStringInterface;
use York\Database\QueryBuilder\Select;
use York\Database\QueryResultList;
use York\Dependency\Manager as Dependency;
use York\Exception\Apocalypse;
use York\Storage\Application;

/**
 * object for finding items
 * implented as singleton
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database
 */
class Model{
	/**
	 * an instance of the stack
	 *
	 * @var \York\Stack
	 */
	protected $stack;

	/**
	 * an instance of the databaseManager
	 *
	 * @var Manager
	 */
	protected $databaseManager;

	/**
	 * all available models
	 * they are called if the requested function does not exist
	 *
	 * @var array
	 */
	private $availableModels = array();

	/**
	 * tries to call a moved or refactored model function
	 *
	 * @param string $function
	 * @param array $params
	 * @return mixed
	 * @throws Apocalypse
	 */
	public function __call($function, $params){
		foreach($this->availableModels as $current){
			if(true === method_exists($current, $function)){
				return call_user_func_array(array($current, $function), $params);
			}
		}
		throw new Apocalypse($function.' not found for any model');
	}


	/**
	 * factory shortcut for models
	 *
	 * @param string $type
	 * @param Manager $manager
	 * @return \York\Database\Model
	 * @throws Apocalypse
	 */
	public static function Factory($type = 'Model', Manager $manager = null){
		$type = ucfirst($type);
		$instance = new $type($manager);

		if(false === $instance instanceof \York\Database\Model\Item){
			throw new Apocalypse(sprintf('Model "%s" not an instance of the core model!!', $type));
		}

		return $instance;
	}

	/**
	 * @param Manager $manager
	 */
	public function __construct(Manager $manager = null){
		if(null === $manager){
			$manager = Dependency::get('databaseManager');
		}
		$this->databaseManager = $manager;
		$this->stack = Dependency::get('applicationConfiguration');
	}

	/**
	 * fires a query
	 *
	 * @param \York\Database\QueryBuilder\QueryString $queryStringObject
	 * @return \York\Database\QueryResult
	 */
	protected function query(QueryStringInterface $queryStringObject){
		$result = $this->databaseManager->find($queryStringObject);
		if('' !== $result->getError()){
			\York\Dependency\Manager::get('logger')->log(sprintf('query: %s | message: %s', $queryStringObject->getQueryString(true), $result->getError()),  \York\Logger\Manager::LEVEL_DATABASE_ERROR);
		}
		return new \York\Database\QueryResult($result, $queryStringObject->getQueryString(), $result->getError());
	}

	/**
	 * finds one item by a query string object
	 *
	 * @param QueryStringInterface $queryString
	 * @return \York\Database\QueryResult | null
	 */
	public function findOneByQueryString(QueryStringInterface $queryString){
		$result = $this->query($queryString);
		$resultList = new QueryResultList();
		$resultList->injectResultsViaQueryResult($result->getResult()->getResult());
		$results = $resultList->getResults();
		if(true === empty($results)){
			return null;
		}
		return $results[0];
	}

	/**
	 * finds all occurences by a query string
	 *
	 * @param QueryStringInterface $queryString
	 * @return array
	 */
	public function findAllByQueryString(QueryStringInterface $queryString){
		$result = $this->query($queryString);
		$resultList = new QueryResultList();
		//@fixme uhm... getResult->getResult seems to be broken....
		$resultList->injectResultsViaQueryResult($result->getResult()->getResult());
		$results = $resultList->getResults();
		return $results;
	}

	/**
	 * builds a query with the given conditions
	 *
	 * @param array $conditions
	 * @return null | \York\Database\QueryResult | array
	 */
	public function find($conditions){
		$queryBuilder = new Select();
		$queryBuilder->setConditions($conditions);
		if(true === $queryBuilder->isQueryForAll()){
			return $this->findAllByQueryString($queryBuilder->getQueryString());
		}
		return $this->findOneByQueryString($queryBuilder->getQueryString());
	}

	/**
	 * shortcut for just finding one item for a table
	 * if no $field is provided, it takes the id-field
	 *
	 * @param string $model
	 * @param string | integer $key
	 * @param string $field
	 * @return \York\Database\FetchResult | null
	 */
	public function findOne($model, $key, $field = null){
		if(null === $field){
			$field = 'id';
		}
		return $this->find(array(
			'from' => $model,
			'method' => 'one',
			'where' => array(
				$field => $key
			)
		));
	}

	/**
	 * returns the amount of found elems
	 *
	 * @param array $conditions
	 * @return integer
	 */
	public function count($conditions){
		$conditions['fields'] = array('COUNT(*) as count');
		$conditions['method'] = 'one';
		$result = $this->find($conditions);
		return $result->count;
	}

	/**
	 * usort by given key
	 *
	 * @param string $key
	 * @param \York\Database\FetchResult $one
	 * @param \York\Database\FetchResult $two
	 * @return integer
	 */
	protected function sortBy(\York\Database\FetchResult $key, \York\Database\FetchResult $one, $two){
		if($one->get($key) === $two->get($key)){
			return 0;
		}

		if($one->get($key) >= $two->get($key)){
			return 1;
		}

		return -1;
	}

	/**
	 * usort for id property
	 *
	 * @param \York\Database\FetchResult $one
	 * @param \York\Database\FetchResult $two
	 * @return integer
	 */
	protected function sortById($one, $two){
		return $this->sortBy('id', $one, $two);
	}

	/**
	 * usort for created property
	 *
	 * @param \York\Database\FetchResult $one
	 * @param \York\Database\FetchResult $two
	 * @return integer
	 */
	protected function sortByCreated($one, $two){
		return $this->sortBy('created', $one, $two);
	}

	/**
	 * usort for start date property
	 *
	 * @param \York\Database\FetchResult $one
	 * @param \York\Database\FetchResult $two
	 * @return integer
	 */
	protected function sortByStartDate($one, $two){
		return $this->sortBy('start_date', $one, $two);
	}
}
