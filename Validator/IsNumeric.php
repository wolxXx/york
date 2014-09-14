<?php
namespace York\Validator;
/**
 * validator for checking that the given data is numeric
 *
 * @author wolxXx
 * @version 3.1
 * @package York\Validator
 */
class IsNumeric implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === is_numeric($data)){
			throw new \York\Exception\Validator('given data is not numeric');
		}

		return true;
	}
}
