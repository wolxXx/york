<?php
namespace York\Validator;
/**
 * validator for checking that the given data is empty
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class IsEmpty implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === empty($data)){
			throw new \York\Exception\Validator('given data is not empty');
		}

		return true;
	}
}
