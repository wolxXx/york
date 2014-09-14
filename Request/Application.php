<?php
namespace York\Request;
/**
 * abstract application request
 * handles validation of the request
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Request
 */
abstract class 	Application{
	/***
	 * @var array
	 */
	private $required;

	/**
	 * @var Manager
	 */
	protected $request;

	/**
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * @var \DateTime
	 */
	protected $created;

	/**
	 * set up, get up, get a request manager
	 */
	public final function __construct(){
		$this->request = new \York\Request\Manager();
		$this->created = \York\Helper\Date::getDateTime();
		$this->setup();
		$this->setDefaults();
		$this->fillValues();
	}

	/**
	 * checks if the request has post data
	 *
	 * @return boolean
	 */
	public function isPost(){
		return \York\Dependency\Manager::getRequestManager()->isPost();
	}

	/**
	 * fill the values from the request to the request object
	 */
	private function fillValues(){
		$reflection = new \ReflectionClass(get_called_class());
		foreach($reflection->getProperties() as $property){
			/**
			 * only fill the values from the request object, ignore parent or children class members
			 */
			if($property->getDeclaringClass()->getName() !== get_called_class()){
				continue;
			}

			$name = $property->getName();
			$default = isset($this->defaults[$name])? $this->defaults[$name] : null;
			$this->$name = $this->request->dataObject->getSafely($name, $default);
		}
	}

	/**
	 * force the extending class to set up
	 * add some validators, dude!
	 *
	 * @return void
	 */
	public abstract function setup();

	/**
	 * set the default params
	 */
	public function setDefaults(){
		//placeholder for children for overwriting..
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public final function setDefault($key, $value){
		$this->defaults[$key] = $value;

		return $this;
	}

	/**
	 * add one validator for multiple keys
	 *
	 * @param string[] $keys
	 * @param \York\Validator\ValidatorInterface $validator
	 * @return $this
	 */
	protected function addValidatorForMultipleKeys(array $keys, \York\Validator\ValidatorInterface $validator){
		foreach($keys as $key){
			$this->addValidator($key, $validator);
		}

		return $this;
	}

	/**
	 * add multiple validators to multiple keys
	 *
	 * @param string[] $keys
	 * @param \York\Validator\ValidatorInterface[] $validators
	 * @return $this
	 */
	protected function addValidatorsForMultipleKeys(array $keys, array $validators){
		foreach($keys as $key){
			$this->addValidators($key, $validators);
		}

		return $this;
	}

	/**
	 * add multiple validators for the key
	 *
	 * @param string $for
	 * @param array $validators
	 * @return $this
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
	 * @param \York\Validator\ValidatorInterface $validator
	 * @return $this
	 */
	protected function addValidator($for, \York\Validator\ValidatorInterface $validator){
		$this->required[$for][] = $validator;

		return $this;
	}

	/**
	 * adds validators for all keys set before. future keys will not automatically have those validators!
	 *
	 * @param array $validators
	 * @return $this
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
	 * @param \York\Validator\ValidatorInterface $validator
	 * @return $this
	 */
	protected function addValidatorForAll(\York\Validator\ValidatorInterface $validator){
		foreach(array_keys($this->required) as $key){
			$this->addValidator($key, $validator);
		}
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated(){
		return $this->created;
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
			 * @var \York\Validator\ValidatorInterface $validator
			 */
			foreach($this->required as $key => $validators){
				foreach($validators as $validator){
					$validator->isValid($this->request->dataObject->getSafely($key, null));
				}
			}
		} catch (\York\Exception\Validator $exception){
			return false;
		}

		return true;
	}
}
