<?php
namespace York\Validator;
use York\Exception\Validator;

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
			throw new Validator('given data is not empty');
		}

		return true;
	}
}
