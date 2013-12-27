<?php
/**
 * blueprint system, usually knwon as models
 *
 * @author wolxXx
 * @version 1.0
 * @package wolxXxMVC
 * @subpackage Database
 *
 */
abstract class Blueprint implements BlueprintInterface{
	/**
	 * creates a new instance
	 * @param $class
	 * @return BlueprintInterface
	 */
	protected static function getClassInstance($class){
		return new $class();
	}
	/**
	 * retrieves a model by its id
	 *
	 * @param integer $id
	 * @param boolean $preventLoadReferences
	 * @return null | Blueprint
	 */
	public static function getById($id, $preventLoadReferences = false){
		$class = get_called_class();
		$obj = DatabaseObjectFactory::getModel()->findOne(Helper::pascalCaseToUnderscores($class), $id);
		if(null === $obj){
			return null;
		}

		$blueprint = self::getClassInstance($class);
		$reflection = new ReflectionClass($blueprint);
		foreach($reflection->getProperties() as $current){
			$name = $current->name;

			if(true === $preventLoadReferences){
				$blueprint->$name = $obj->get($name);
				continue;
			}

			$docBlock = $reflection->getProperty($name)->getDocComment();

			$declaredVar = str_replace('*', '', $docBlock);
			$declaredVar = str_replace('/', '', $declaredVar);
			$declaredVar = str_replace('@var', '', $declaredVar);
			$declaredVar = trim($declaredVar);

			if(true === in_array($declaredVar, array('integer', 'string'))){
				$blueprint->$name = $obj->get($name);
				continue;
			}

			if('DateTime' === $declaredVar){
				$blueprint->$name = new DateTime($obj->get($name));
				continue;
			}

			if('is_' === substr($name, 0, 3)){
				$blueprint->$name = '1' === $obj->get($name)? true : false;
				continue;
			}

			if(AutoLoader::isLoadable($declaredVar)){
				if(false === $reflection->implementsInterface('BlueprintInterface')){
					$blueprint->$name = $obj->get($name);
				}
				$newObj = call_user_func_array(array($declaredVar, 'getById'), array($obj->get('id_'.strtolower($declaredVar))));
				$blueprint->$name = $newObj;
				continue;
			}

			$blueprint->$name = $obj->get($name);
		}
		$blueprint->initReferencing();
		return $blueprint;
	}

	/**
	 * retrieves several blueprint by their ids
	 *
	 * @param array $ids
	 * @param boolean $preventLoadReferences
	 * @return array
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
