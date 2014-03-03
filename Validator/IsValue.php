<?php
namespace York\Validator;
use York\Exception\Validator;

class IsValue implements ValidatorInterface{

	protected $compare;

	public function __construct($compare){
		$this->compare = $compare;
	}

	/**
	 * @param mixed $data
	 * @return boolean
	 * @throws \York\Exception\Validator
	 */
	public function isValid($data){
		if($data !== $this->compare){
			throw new Validator('given data does not match the compare data');
		}

		return true;
	}
}
