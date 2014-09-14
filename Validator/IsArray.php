<?php
namespace York\Validator;
/**
 * validator for checking that the given data is an array
 *
 * @author wolxXx
 * @version 3.1
 * @package York\Validator
 */
class IsArray implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === is_array($data)){
			throw new \York\Exception\Validator('given data is not an array!');
		}

		return true;
	}
}
