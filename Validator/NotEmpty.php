<?php
namespace York\Validator;
use York\Exception\Validator;

/**
 * validator for checking that the given data is not empty
 *
 * @author wolxXx
 * @version 3.0
 * @package York\Validator
 */
class NotEmpty implements ValidatorInterface{
	/**
	 * @inheritdoc
	 */
	public function isValid($data){
		if(true === empty($data)){
			throw new Validator('given data is empty');
		}

		return true;
	}
}
