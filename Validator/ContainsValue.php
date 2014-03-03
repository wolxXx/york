<?php
namespace York\Validator;
use York\Exception\Validator;

class ContainsValue implements ValidatorInterface{

	protected $needle;

	public function __construct($needle){
		$this->needle = $needle;
	}

	/**
	 * @param mixed $data
	 * @return boolean
	 * @throws \York\Exception\Validator
	 */
	public function isValid($data){
		if(false === strstr($data, $this->needle)){
			throw new Validator('given data does not contain the needle');
		}

		return true;
	}
}
