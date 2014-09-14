<?php
namespace York\Validator;
/**
 * validator for checking that the given data is a string
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class IsString implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === is_string($data)){
			throw new \York\Exception\Validator('given data is not a string');
		}

		return true;
	}
}
