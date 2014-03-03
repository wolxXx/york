<?php
namespace York\Request;
use York\Validator\ValidatorInterface;

/**
 * abstract application request
 *handles validation of the request
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Request
 */
abstract class Application {
	/***
	 * @var array
	 */
	private $required;

	/**
	 * @var Manager
	 */
	protected $request;

	/**
	 * set up, get up, get a request manager
	 */
	public final function __construct(){
		$this->request = new \York\Request\Manager();
		$this->setup();
		$this->fillValues();
	}

	public function isPost(){
		return \York\Dependency\Manager::get('requestManager')->isPost();
	}

	private function fillValues(){
		$reflection = new \ReflectionClass(get_called_class());
		foreach($reflection->getProperties() as $property){
			if($property->getDeclaringClass()->getName() !== get_called_class()){
				continue;
			}

			$name = $property->getName();
			$this->$name = $this->request->dataObject->getSavely($name, null);
		}
	}

	/**
	 * force the extending class to set up
	 * add some validators, dude!
	 *
	 * @return null
	 */
	public abstract function setup();


	protected function addValidatorForMultipleKeys(array $keys, ValidatorInterface $validator){
		foreach($keys as $key){
			$this->addValidator($key, $validator);
		}
	}

	protected function addValidatorsForMultipleKeys(array $keys, array $validators){
		foreach($keys as $key){
			$this->addValidators($key, $validators);
		}
	}

	/**
	 * add multiple validators for the key
	 *
	 * @param string $for
	 * @param array $validators
	 * @return \York\Request\Application
	 */
	protected function addValidators($for, array $validators){
		foreach($validators as $validator){
			$this->addValidator($for, $validator);
		}

		return $this;
	}

	/**
	 * add a single validator for the key
	 *
	 * @param string $for
	 * @param ValidatorInterface $validator
	 * @return \York\Request\Application
	 */
	protected function addValidator($for, ValidatorInterface $validator){
		$this->required[$for][] = $validator;

		return $this;
	}

	/**
	 * adds validators for all keys set before. future keys will not automatically have those validators!
	 *
	 * @param array $validators
	 * @return \York\Request\Application
	 */
	protected function addValidatorsForAll(array $validators){
		foreach($validators as $validator){
			$this->addValidatorForAll($validator);
		}

		return $this;
	}

	/**
	 * adds a single validator for all keys set before. future keys will not automatically have this validator!
	 *
	 * @param ValidatorInterface $validator
	 * @return \York\Request\Application
	 */
	protected function addValidatorForAll(ValidatorInterface $validator){
		foreach(array_keys($this->required) as $key){
			$this->addValidator($key, $validator);
		}
		return $this;
	}

	/**
	 * runs all set validators
	 *
	 * @return boolean
	 */
	public function isValid(){
		if(true === is_null($this->required) || true === empty($this->required)){
			return true;
		}
		try{
			/**
			 * @var ValidatorInterface $validator
			 */
			foreach($this->required as $key => $validators){
				foreach($validators as $validator){
					$validator->isValid($this->request->dataObject->getSavely($key, null));
				}
			}
		} catch (\York\Exception\Validator $exception){
			return false;
		}

		return true;


	}
}
