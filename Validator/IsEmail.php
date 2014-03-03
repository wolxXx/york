<?php
namespace York\Validator;
use York\Exception\Validator;

/**
 * validator for checking that the given data has valid email syntax
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class IsEmail implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === filter_var($data, FILTER_VALIDATE_EMAIL)){
			throw new Validator('given data has no valid email syntax!');
		}

		return true;
	}
}
