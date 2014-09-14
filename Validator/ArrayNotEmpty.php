<?php
namespace York\Validator;
/**
 * validator for checking that the given data is an array and not empty
 *
 * @author wolxXx
 * @version 3.1
 * @package York\Validator
 */
class ArrayNotEmpty implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(false === is_array($data)){
			throw new \York\Exception\Validator('given data is not an array!');
		}

		if(true === empty($data)){
			throw new \York\Exception\Validator('array is empty!');
		}

		return true;
	}
}
