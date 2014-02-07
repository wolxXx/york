<?php
namespace York\Database\Model;
use York\Database\Model;
use York\Exception\Autoload;

/**
 * model manager
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\Model
 */
abstract class Manager implements ManagerInterface{
	/**
	 * name of the referring table in the database
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 * name of the blueprint class
	 *
	 * @var string
	 */
	protected $blueprint;

	/**
	 * set up
	 */
	public final function __construct(){
		$this->model = new Model();
	}

	/**
	 * creates a new instance
	 *
	 * @param $class
	 * @throws Autoload
	 * @return \York\Database\Blueprint\ItemInterface
	 */
	protected static function getClassInstance($class){
		if(false === \York\Autoload\Manager::isLoadable($class)){
			throw new Autoload(sprintf('cannot load %s as blueprint in %s', $class, __CLASS__));
		}

		return new $class();
	}

	/**
	 * match the declared variable type
	 * grab a new instance of the blueprint if needed
	 *
	 * @param \ReflectionProperty $property
	 * @param array $data
	 * @return mixed
	 */
	protected function matchDeclaredVar(\ReflectionProperty $property, array $data){
		$name = $property->getName();

		$comment = str_replace('*', '', $property->getDocComment());
		$comment = str_replace('/', '', $comment);
		$comment = trim($comment);

		$type = null;
		$identifiedBy = null;

		foreach(explode(PHP_EOL,$comment) as $part){
			$part = trim($part);
			if(false !== strstr($part, '@var')){
				$type = explode(' ', str_replace('@var', '', $part))[1];
				continue;
			}
			if(false !== strstr($part, '@identifiedBy')){
				$identifiedBy = explode(' ', str_replace('@identifiedBy', '', $part))[1];
				continue;
			}
		}

		if(true === array_key_exists($name, $data)){
			$value = $data[$name];

			if('string' === $type){
				return $value;
			}

			if('integer' === $type){
				return (int) $value;
			}

			if('\DateTime' === $type){
				if(null === $value || '0000-00-00 00:00:00' === $value){
					return null;
				}
				return new \DateTime($value);
			}

			if('boolean' === $type){
				return '1' === $value? true : false;
			}
		}

		if(false === isset($identifiedBy) || null === $identifiedBy || false === isset($data[$identifiedBy])){

			return null;
		}

		$blueprintSave = $this->blueprint;
		$this->blueprint = $type;


		$tableNameSave = $this->tableName;
		$this->tableName = str_replace('id_', '', $identifiedBy);


		$instance = $this->getById($data[$identifiedBy], true);

		$this->tableName = $tableNameSave;
		$this->blueprint = $blueprintSave;

		return $instance;
	}

	/**
	 * find one by its id
	 *
	 * @param integer $id
	 * @param bool $preventLoadReferences
	 * @return null | \York\Database\Model\Item
	 */
	public function getById($id, $preventLoadReferences = false){
		$result = $this->model->findOne($this->tableName, $id);
		if(null === $result){
			return null;
		}

		return $this->createByResult($result);
	}

	/**
	 * create a model by the fetch result from the database
	 *
	 * @param \York\Database\FetchResult $result
	 * @return null|\York\Database\Blueprint\ItemInterface
	 */
	protected function createByResult($result, $preventReferencing = false){
		if(null === $result){
			return null;
		}

		$resultData = $result->getData();

		/**
		 * @var \York\Database\Model\Item $instance
		 */
		$instance = null;
		try{
			$instance = $this->getClassInstance($this->blueprint);
		}catch(Autoload $exception){
			return null;
		}

		$reflection = new \ReflectionClass($instance);

		foreach($reflection->getProperties() as $property){
			if(true === in_array($property->getName(), array('flatMembers', 'referencedMembers'))){
				continue;
			}
			$name = $property->getName();
			$instance->set($name, $this->matchDeclaredVar($property, $resultData));
		}

		foreach($instance->referencedMembers as $referenced){
			$instance->setReferenced($referenced, $this->matchDeclaredVar($reflection->getProperty($referenced), $resultData));
		}

		$instance->validate();

		return $instance;
	}

	/**
	 * @param \York\Database\QueryBuilder $query
	 * @return \York\Database\Blueprint\ItemInterface | null
	 */
	public function findOne(\York\Database\QueryBuilder $query){
		$result = $this->find($query);
		if(null !== $result){
			$result = reset($result);
		}

		return $result;
	}

	/**
	 * find all by the the query builder data
	 *
	 * @param \York\Database\QueryBuilder $query
	 * @return \York\Database\Blueprint\ItemInterface[]
	 */
	public function find(\York\Database\QueryBuilder $query, $preventReferencing = false){
		\York\Helper\Application::debug($preventReferencing);
		$model = new Model();
		$results = $model->findAllByQueryString($query->getQueryString());
		$return = array();

		foreach($results as $current){
			$currentBlueprint = $this->createByResult($current, $preventReferencing);

			if(null === $currentBlueprint){
				continue;
			}

			$return[] = $currentBlueprint;
		}

		return $return;
	}

	/**
	 * @return string
	 */
	public function getTableName(){
		return $this->tableName;
	}

	/**
	 * @param string $tableName
	 * @return \York\Database\Model\Manager
	 */
	public function setTableName($tableName){
		$this->tableName = $tableName;
		return $this;
	}
}
