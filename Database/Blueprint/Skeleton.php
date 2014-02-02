<?php
namespace York\Database\Blueprint;
use York\Database\Model;
use York\Helper\String;

/**
 * blueprint system, usually known as models
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Database\Blueprint
 *
 */
abstract class Skeleton implements \York\Database\Blueprint\ItemInterface{
	/**
	 * creates a new instance
	 * @param $class
	 * @return \York\Database\Blueprint\ItemInterface
	 */
	protected static function getClassInstance($class){
		return new $class();
	}
	/**
	 * retrieves a model by its id
	 *
	 * @param integer $id
	 * @param boolean $preventLoadReferences
	 * @return null | \York\Database\Blueprint\ItemInterface
	 */
	public static function getById($id, $preventLoadReferences = false){
		$class = get_called_class();
		$instance = Model::Factory()->findOne(String::pascalCaseToUnderscores($class), $id);
		if(null === $instance){
			return null;
		}

		$blueprint = self::getClassInstance($class);
		$reflection = new \ReflectionClass($blueprint);
		foreach($reflection->getProperties() as $current){
			$name = $current->name;

			if(true === $preventLoadReferences){
				$blueprint->$name = $instance->get($name);
				continue;
			}

			$docBlock = $reflection->getProperty($name)->getDocComment();

			$declaredVar = str_replace('*', '', $docBlock);
			$declaredVar = str_replace('/', '', $declaredVar);
			$declaredVar = str_replace('@var', '', $declaredVar);
			$declaredVar = trim($declaredVar);

			if(true === in_array($declaredVar, array('integer', 'string'))){
				$blueprint->$name = $instance->get($name);
				continue;
			}

			if('DateTime' === $declaredVar){
				$blueprint->$name = new \DateTime($instance->get($name));
				continue;
			}

			if('is_' === substr($name, 0, 3)){
				$blueprint->$name = '1' === $instance->get($name)? true : false;
				continue;
			}

			if(\York\AutoLoad\Manager::isLoadable($declaredVar)){
				if(false === $reflection->implementsInterface('\York\Database\Blueprint\ItemInterface')){
					$blueprint->$name = $instance->get($name);
				}
				$newObj = call_user_func_array(array($declaredVar, 'getById'), array($instance->get('id_'.strtolower($declaredVar))));
				$blueprint->$name = $newObj;
				continue;
			}

			$blueprint->$name = $instance->get($name);
		}
		$blueprint->initReferencing();
		return $blueprint;
	}

	/**
	 * retrieves several blueprint by their ids
	 *
	 * @param array $ids
	 * @param boolean $preventLoadReferences
	 * @return \York\Database\Blueprint\ItemInterface[]
	 */
	public static function getByIds(array $ids, $preventLoadReferences = false){
		$return = array();
		foreach($ids as $id){
			$obj = self::getById($id, $preventLoadReferences);
			if(null === $obj){
				continue;
			}
			$return[] = $obj;
		}
		return $return;
	}

	/**
	 * overwrite me!
	 */
	public function initReferencing(){}
}
