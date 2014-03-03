<?php
namespace York\Validator;
use York\Exception\Validator;

/**
 * validator for checking that the given data is numeric
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class IsNumeric implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === is_numeric($data)){
			throw new Validator('given data is not numeric');
		}

		return true;
	}
}
