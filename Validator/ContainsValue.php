<?php
namespace York\Validator;
/**
 * validator for checking if the data is in the needle
 *
 * @package York\Validator
 * @version 3.1
 * @author wolxXx
 */
class ContainsValue implements ValidatorInterface{
	/**
	 * @var string
	 */
	protected $needle;

	/**
	 * @param string $needle
	 */
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
			throw new \York\Exception\Validator('given data does not contain the needle');
		}

		return true;
	}
}
