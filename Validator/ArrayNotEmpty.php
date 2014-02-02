<?php
namespace York\Validator;
use York\Exception\Validator;

/**
 * validator for checking that the given data is an array and not empty
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class ArrayNotEmpty implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === is_array($data)){
			throw new Validator('given data is not an array!');
		}

		if(true === empty($data)){
			throw new Validator('array is empty!');
		}

		return true;
	}
}
